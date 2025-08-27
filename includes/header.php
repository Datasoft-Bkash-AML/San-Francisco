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
    <!-- Main site CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Babui-dynamic frontend CSS -->
    <link rel="stylesheet" href="css/frontend.min.css">
    <link rel="stylesheet" href="css/apple-webkit.min.css">
    <link rel="stylesheet" href="css/e-swiper.min.css">
    <link rel="stylesheet" href="css/widget-heading.min.css">
    <link rel="stylesheet" href="css/widget-icon-box.min.css">
    <link rel="stylesheet" href="css/widget-image-gallery.min.css">
    <link rel="stylesheet" href="css/widget-image.min.css">
    <link rel="stylesheet" href="css/widget-social-icons.min.css">
    <link rel="stylesheet" href="css/widget-text-editor.min.css">
    <!-- Add more as needed for full UI fidelity -->
    <!-- Use Outfit font to match demo -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>
<body>
        <!-- Load jQuery first -->
        <script src="js/jquery.min.js"></script>
        <!-- Define reyParams global variable required by Rey theme scripts -->
        <script>
                            var reyParams = {
                                ajaxUrl: '', // Set to your AJAX endpoint if needed
                                siteUrl: window.location.origin,
                                theme: 'San-Francisco',
                                lang: 'en',
                                js_params: {
                                    enableCart: true,
                                    enableWishlist: true,
                                    enableQuickView: true,
                                    currency: 'USD',
                                    locale: 'en-US',
                                    // Add more Rey-specific config as needed
                                },
                                // Add more config as needed for your frontend
                            };
        </script>
        <!-- Load Babui-dynamic core JS likely to define 'rey' -->
        <script src="js/c-rey-template.js"></script>
        <script src="js/c-general.js"></script>
        <script src="js/c-helpers.js"></script>
    <!-- Load main and Babui-dynamic JS files after core -->
    <script src="js/script.js"></script>
    <script src="js/frontend-script.js"></script>
    <script src="js/add-to-cart.min.js"></script>
    <!-- Add more JS as needed for full functionality -->
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