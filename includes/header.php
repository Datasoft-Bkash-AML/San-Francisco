<?php
// includes/header.php - Site header and navigation
// Fetch categories for navigation
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Determine current category for active nav
$currentCat = isset($_GET['category']) ? (int)$_GET['category'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>San Francisco Demo</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Use Outfit font to match demo -->
    <link rel="preload" href="https://fonts.gstatic.com/s/outfit/v12/" as="font" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="promo-bar">FREE WORLDWIDE SHIPPING OVER $100</div>
    <header>
        <div class="container header-inner">
            <div class="logo">San Francisco</div>
            <nav>
                <ul class="nav-list">
                    <?php foreach($categories as $cat): ?>
                        <li class="<?php echo $currentCat === $cat['id'] ? 'active' : ''; ?>">
                            <a href="index.php?category=<?php echo urlencode($cat['id']); ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>