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

        if ($action === 'add') {
            $stmt = $pdo->prepare('INSERT INTO products (name, description, category_id) VALUES (?, ?, ?)');
            $stmt->execute([$name, $description, $category_id]);
            $productId = $pdo->lastInsertId();
        } else {
            $productId = intval($_POST['id']);
            $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, category_id = ? WHERE id = ?');
            $stmt->execute([$name, $description, $category_id, $productId]);
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

        // If no upload, allow selecting an existing image name from the select box
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
    <style>
        .admin{padding:24px}
        .admin table{width:100%;border-collapse:collapse}
        .admin th,.admin td{padding:8px;border-bottom:1px solid #eee}
        .form-row{display:flex;gap:8px;align-items:center}
        .form-row input, .form-row select{padding:6px}
    </style>
</head>
<body>
<div class="container admin">
    <h1>Admin Panel</h1>
    <form method="post" style="float:right">
        <input type="hidden" name="action" value="logout">
        <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
        <button type="submit">Logout</button>
    </form>

    <h2>Add / Edit Product</h2>
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
            <select name="existing_image">
                <option value="">Or choose existing image</option>
                <?php foreach ($imageFiles as $img) echo '<option value="'.htmlspecialchars($img).'">'.htmlspecialchars($img).'</option>'; ?>
            </select>
            <button type="submit"><?=htmlspecialchars($submitLabel)?></button>
            <?php if ($formAction === 'edit'): ?>
                <a href="index.php" style="margin-left:8px;align-self:center">Cancel</a>
            <?php endif; ?>
        </div>
        <div><textarea name="description" placeholder="Description" style="width:100%;margin-top:8px;height:80px"><?=htmlspecialchars($formValues['description'])?></textarea></div>
    </form>

    <h2>Products</h2>
    <table>
    <thead><tr><th>ID</th><th>Name</th><th>Image</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?=htmlspecialchars($p['id'])?></td>
                <td><?=htmlspecialchars($p['name'])?></td>
                <td><?php if (!empty($p['image'])): ?><img src="/images/<?=htmlspecialchars($p['image'])?>" style="height:48px"> <?php else: ?>—<?php endif; ?></td>
                <td>
                    <a href="?edit=<?=htmlspecialchars($p['id'])?>">Edit</a>
                    <form method="post" style="display:inline;margin-left:8px">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?=htmlspecialchars($p['id'])?>">
                        <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
                        <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
