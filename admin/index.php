<?php
require_once __DIR__ . '/../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Simple auth check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        $pw = $_POST['password'];
        $hash = defined('ADMIN_PASS_HASH') ? ADMIN_PASS_HASH : null;
        if ($hash && password_verify($pw, $hash)) {
            // regenerate session to avoid fixation
            session_regenerate_id(true);
            $_SESSION['is_admin'] = true;
            header('Location: /admin/index.php');
            exit;
        } else {
            $error = 'Invalid password';
        }
    }
    ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Admin Login</title>
        <link rel="stylesheet" href="/css/style.css">
        <style>body{padding:40px;font-family:Inter,system-ui;-webkit-font-smoothing:antialiased} .login{max-width:420px;margin:0 auto;background:#fff;padding:24px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,.06)} input{width:100%;padding:8px;margin:6px 0}</style>
    </head>
    <body>
    <div class="container">
        <div class="login">
            <h2>Admin Login</h2>
            <?php if (!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
            <form method="post">
                <label>Password</label>
                <input type="password" name="password" required>
                <button type="submit">Sign In</button>
            </form>
            <p style="font-size:13px;color:#666">Default password hash is in `config.php` as `ADMIN_PASS_HASH`.</p>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle actions (POST) inside same file for simplicity
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    // CSRF check
    if (!isset($_POST['csrf']) || !csrf_check($_POST['csrf'])) {
        http_response_code(400);
        die('CSRF validation failed');
    }
    if ($action === 'add' || $action === 'edit') {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? intval($_POST['category_id']) : null;
        $featuredFlag = isset($_POST['featured']) && $_POST['featured'] ? 1 : 0;

        if ($action === 'add') {
            $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
            $stmt = $pdo->prepare('INSERT INTO products (name, description, category_id, price) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $description, $category_id, $price]);
            $productId = $pdo->lastInsertId();
        } else {
            $productId = intval($_POST['id']);
            $price = isset($_POST['price']) ? (float)$_POST['price'] : 0.0;
            $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, category_id = ?, featured = ?, price = ? WHERE id = ?');
            $stmt->execute([$name, $description, $category_id, $featuredFlag, $price, $productId]);
        }

        // Handle upload or existing image selection
        $chosenImage = null;
        if (!empty($_FILES['image']['name'])) {
            $up = $_FILES['image'];
            $allowed = ['image/jpeg' => '.jpg', 'image/png' => '.png'];
            if ($up['error'] === UPLOAD_ERR_OK && isset($allowed[$up['type']])) {
                $ext = $allowed[$up['type']];
                $fname = 'product-' . substr(md5((string)$productId . time()), 0, 8) . $ext;
                $dst = __DIR__ . '/../images/' . $fname;
                if (move_uploaded_file($up['tmp_name'], $dst)) {
                    $chosenImage = $fname;
                }
            }
        }

        // If no upload, allow an explicit image URL
        if (!$chosenImage && !empty($_POST['image_url']) && preg_match('#^https?://#i', $_POST['image_url'])) {
            $chosenImage = $_POST['image_url'];
        }

        // If still no image, allow selecting an existing image name from the select box (local file)
        if (!$chosenImage && !empty($_POST['existing_image'])) {
            $candidate = basename($_POST['existing_image']);
            if (file_exists(__DIR__ . '/../images/' . $candidate)) {
                $chosenImage = $candidate;
            }
        }

        if ($chosenImage) {
            $stmt = $pdo->prepare('UPDATE products SET image = ? WHERE id = ?');
            $stmt->execute([$chosenImage, $productId]);
        }
        // Ensure featured is set for newly inserted product as well
        if ($action === 'add') {
            $stmt = $pdo->prepare('UPDATE products SET featured = ? WHERE id = ?');
            $stmt->execute([$featuredFlag, $productId]);
        }

        header('Location: /admin/index.php');
        exit;
    }

    if ($action === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        // remove image file if exists
        $row = $pdo->prepare('SELECT image FROM products WHERE id = ?');
        $row->execute([$id]);
        $r = $row->fetch(PDO::FETCH_ASSOC);
        if ($r && !empty($r['image'])) {
            $f = __DIR__ . '/../images/' . $r['image'];
            if (file_exists($f)) @unlink($f);
        }
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: /admin/index.php');
        exit;
    }

    if ($action === 'seed_products') {
        // Seed categories and products programmatically (similar to scripts/seed_products.php)
        $categories = ['Headphones','Speakers','Accessories'];
        $catIds = [];
        foreach ($categories as $c) {
                // Fetch numeric id if present, otherwise use rowid
                $stmt = $pdo->prepare('SELECT id, rowid FROM categories WHERE name = ? ORDER BY rowid DESC LIMIT 1');
                $stmt->execute([$c]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $catIds[$c] = !empty($row['id']) ? (int)$row['id'] : (int)$row['rowid'];
                } else {
                    $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
                    $stmt->execute([$c]);
                    $last = $pdo->query('SELECT rowid FROM categories ORDER BY rowid DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
                    $catIds[$c] = (int)$last['rowid'];
                }
        }
        $seed = [];
    for ($i = 1; $i <= 12; $i++) $seed[] = ['category'=>'Headphones','name'=>"Studio Headphones H-{$i}",'description'=>"High-fidelity studio headphones model H-{$i} with detailed sound and comfortable fit.",'image'=>'product-a.jpg','price'=> round(49 + ($i*8) + (($i%3)*5),2)];
    for ($i = 1; $i <= 12; $i++) $seed[] = ['category'=>'Speakers','name'=>"Home Speaker S-{$i}",'description'=>"Compact home speaker S-{$i} delivering rich bass and clear mids.",'image'=>'product-b.jpg','price'=> round(79 + ($i*10) + (($i%2)*7),2)];
    for ($i = 1; $i <= 12; $i++) $seed[] = ['category'=>'Accessories','name'=>"Accessory A-{$i}",'description'=>"Accessory A-{$i} for your devices.",'image'=>'product-c.jpg','price'=> round(9 + ($i*3) + (($i%4)*2),2)];

        foreach ($seed as $p) {
            $catId = $catIds[$p['category']];
            $exists = $pdo->prepare('SELECT id FROM products WHERE name = ?');
            $exists->execute([$p['name']]);
            if ($exists->fetch()) continue;
            $price = isset($p['price']) ? $p['price'] : 0.0;
            $stmt = $pdo->prepare('INSERT INTO products (category_id, name, description, image, price) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$catId, $p['name'], $p['description'], $p['image'], $price]);
        }
        header('Location: /admin/index.php');
        exit;
    }

    if ($action === 'logout') {
        session_destroy();
        header('Location: /admin/index.php');
        exit;
    }
}

// Fetch categories and products
$cats = $pdo->query('SELECT * FROM categories ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

// Gather existing image files for admin select
$imageFiles = [];
$imDir = __DIR__ . '/../images';
if (is_dir($imDir)) {
    $all = scandir($imDir);
    foreach ($all as $f) {
        if (preg_match('/\.(jpe?g|png|svg)$/i', $f)) $imageFiles[] = $f;
    }
}

// Prepare edit mode if requested
$editing = null;
$formAction = 'add';
$submitLabel = 'Add Product';
 $formValues = ['name' => '', 'category_id' => '', 'description' => ''];
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$editId]);
    $editing = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($editing) {
        $formAction = 'edit';
        $submitLabel = 'Save Changes';
        $formValues = [
            'name' => $editing['name'],
            'category_id' => $editing['category_id'],
            'description' => $editing['description'],
            'id' => $editing['id'],
        ];
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - San Francisco</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/admin/css/admin.css">
</head>
<body>
<div class="container admin">
    <div class="top-actions">
        <form method="post" style="display:inline">
            <input type="hidden" name="action" value="logout">
            <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
            <button type="submit">Logout</button>
        </form>
        <form method="post" style="display:inline">
            <input type="hidden" name="action" value="seed_products">
            <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
            <button type="submit">Seed Demo Products</button>
        </form>
    </div>

    <h1>Admin Panel</h1>
    <h2>Add / Edit Product</h2>
    <form id="admin-upload-form" method="post" enctype="multipart/form-data" style="margin-bottom:8px">
        <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
        <input type="file" name="image" accept="image/jpeg,image/png">
        <button type="submit">Upload Image (AJAX)</button>
    </form>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?=htmlspecialchars($formAction)?>">
        <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
        <?php if ($formAction === 'edit'): ?>
            <input type="hidden" name="id" value="<?=htmlspecialchars($formValues['id'])?>">
        <?php endif; ?>
        <div class="form-row">
            <input name="name" placeholder="Title" required value="<?=htmlspecialchars($formValues['name'])?>">
            <select name="category_id">
                <option value="">No category</option>
                <?php foreach ($cats as $c) {
                    $sel = ($formValues['category_id'] !== '' && $formValues['category_id'] == $c['id']) ? ' selected' : '';
                    echo '<option value="'.htmlspecialchars($c['id']).'"'.$sel.'>'.htmlspecialchars($c['name']).'</option>';
                } ?>
            </select>
            <input type="file" name="image" accept="image/jpeg,image/png">
            <input type="text" name="image_url" placeholder="Or image URL (https://...)" style="min-width:220px" value="<?=htmlspecialchars($formValues['image'] ?? '')?>">
            <select name="existing_image">
                <option value="">Or choose existing image</option>
                <?php foreach ($imageFiles as $img) echo '<option value="'.htmlspecialchars($img).'">'.htmlspecialchars($img).'</option>'; ?>
            </select>
            <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="featured" value="1" <?=isset($editing) && !empty($editing['featured']) ? 'checked' : ''?>> Featured</label>
            <input type="number" step="0.01" name="price" placeholder="Price (e.g. 49.99)" value="<?=isset($editing['price']) ? htmlspecialchars($editing['price']) : ''?>" style="width:120px">
            <button type="submit"><?=htmlspecialchars($submitLabel)?></button>
            <?php if ($formAction === 'edit'): ?>
                <a href="index.php" style="margin-left:8px;align-self:center">Cancel</a>
            <?php endif; ?>
        </div>
        <div><textarea name="description" placeholder="Description" style="width:100%;margin-top:8px;height:80px"><?=htmlspecialchars($formValues['description'])?></textarea></div>
    </form>

    <h2>Products</h2>
    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <div class="media">
                    <?php if (!empty($p['image'])):
                        $imgSrc = preg_match('#^https?://#i',$p['image']) ? $p['image'] : '/images/'.htmlspecialchars($p['image']);
                    ?>
                        <img src="<?=$imgSrc?>">
                    <?php else: ?>
                        <div style="color:#999">No image</div>
                    <?php endif; ?>
                </div>
                <div class="title"><?=htmlspecialchars($p['name'])?></div>
                <div class="meta"><?php
                    $catName = '';
                    foreach ($cats as $c) if ($c['id']==$p['category_id']) { $catName = $c['name']; break; }
                    echo htmlspecialchars($catName);
                ?></div>
                <?php if (!empty($p['featured'])): ?>
                    <div class="badge">FEATURED</div>
                <?php endif; ?>
                <div class="small"><?=htmlspecialchars(mb_strimwidth($p['description'] ?? '',0,120,'...'))?></div>
                <div class="price">$<?=htmlspecialchars(isset($p['price']) ? number_format($p['price'],2) : '0.00')?></div>
                <div class="actions">
                    <a href="?edit=<?=htmlspecialchars($p['id'])?>">Edit</a>
                    <button data-feature-toggle data-product-id="<?=htmlspecialchars($p['id'])?>" data-featured="<?=!empty($p['featured']) ? '1' : '0'?>" data-csrf="<?=htmlspecialchars(csrf_token())?>"><?=!empty($p['featured']) ? '\u2605 Featured' : '\u2606 Feature'?></button>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?=htmlspecialchars($p['id'])?>">
                        <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
                        <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
<script src="/js/admin-widgets.js"></script>
</html>
