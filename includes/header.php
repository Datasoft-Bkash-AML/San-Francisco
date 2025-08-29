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
    <!-- New frontend scaffold CSS -->
    <link rel="stylesheet" href="css/new-frontend.css">
    <!-- Minimal replica CSS (branch: work/new-frontend-minimal) -->
    <link rel="stylesheet" href="css/new-frontend-minimal.css">
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
            <!-- New frontend scaffold JS -->
            <script src="js/new-frontend.js" defer></script>
            <!-- Minimal replica JS (branch: work/new-frontend-minimal) -->
            <script src="js/new-frontend-minimal.js" defer></script>
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
        <script src="js/api-widgets.js"></script>
    <!-- Load main and Babui-dynamic JS files after core -->
    <script src="js/script.js"></script>
    <script src="js/frontend-script.js"></script>
    <script src="js/add-to-cart.min.js"></script>
    <!-- Add more JS as needed for full functionality -->
</head>
<body>
    <div class="promo-bar" style="background: #222; color: #fff; text-align: center; padding: 8px 0; font-size: 1rem; letter-spacing: 1px; font-weight: 600;">FREE WORLDWIDE SHIPPING OVER $100</div>
    <header style="background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
        <div class="container header-inner" style="display: flex; align-items: center; justify-content: space-between; padding: 24px 40px 18px 40px; max-width: 1400px; margin: 0 auto;">
            <div class="logo" style="font-family: 'Outfit', sans-serif; font-weight: 900; font-size: 2.6rem; color: #222; letter-spacing: 0.5px; text-transform: lowercase;">rey</div>
            <nav>
                <ul class="nav-list" style="display: flex; gap: 36px; list-style: none; margin: 0; padding: 0; align-items: center;">
                    <li style="font-size: 1.15rem; font-weight: 600;"><a href="#" style="color: #222; text-decoration: none; padding: 6px 12px; border-radius: 6px; transition: background 0.2s;">Home</a></li>
                    <li style="font-size: 1.15rem; font-weight: 600; position: relative;">
                        <a href="#" style="color: #222; text-decoration: none; padding: 6px 12px; border-radius: 6px; transition: background 0.2s;">Shop <span style="font-size: 0.8em;">&#9662;</span></a>
                        <!-- Dropdown placeholder -->
                    </li>
                    <li style="font-size: 1.15rem; font-weight: 600;"><a href="#" style="color: #222; text-decoration: none; padding: 6px 12px; border-radius: 6px; transition: background 0.2s;">New Arrivals</a></li>
                    <li style="font-size: 1.15rem; font-weight: 600;"><a href="#" style="color: #222; text-decoration: none; padding: 6px 12px; border-radius: 6px; transition: background 0.2s;">Brands</a></li>
                    <li style="font-size: 1.15rem; font-weight: 600; position: relative;">
                        <a href="#" style="color: #222; text-decoration: none; padding: 6px 12px; border-radius: 6px; transition: background 0.2s;">Pages <span style="font-size: 0.8em;">&#9662;</span></a>
                        <!-- Dropdown placeholder -->
                    </li>
                </ul>
            </nav>
            <div class="header-icons" style="display: flex; gap: 24px; align-items: center; font-size: 1.4rem; color: #222;">
                <span title="Search" style="cursor:pointer;" data-open="search">&#128269;</span>
                <span title="Account" style="cursor:pointer;" data-open="account">&#128100;</span>
                <span title="Wishlist" style="cursor:pointer; position:relative" data-open="wishlist">&#10084;
                    <span id="header-wishlist-count" style="position:absolute; top:-8px; right:-10px; background:#b76e79; color:#fff; font-size:12px; padding:2px 6px; border-radius:12px; display:none">0</span>
                </span>
                <span title="Cart" style="cursor:pointer; position:relative" data-open="cart">&#128722;
                    <span id="header-cart-count" style="position:absolute; top:-8px; right:-10px; background:#222; color:#fff; font-size:12px; padding:2px 6px; border-radius:12px; display:none">0</span>
                </span>
                <span title="Lightning" style="cursor:pointer;">&#9889;</span>
            </div>
        </div>
    </header>
    <!-- Off-canvas panels for cart and wishlist -->
    <div id="offcanvas-panels">
        <div id="panel-cart" style="position:fixed;right:18px;top:80px;width:360px;max-width:90%;background:#fff;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,0.12);display:none;z-index:1200;padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><strong>Your cart</strong><button id="panel-cart-close" style="background:none;border:none;font-size:16px;cursor:pointer">✕</button></div>
            <div id="panel-cart-body">Loading…</div>
        </div>
        <div id="panel-wishlist" style="position:fixed;right:18px;top:80px;width:360px;max-width:90%;background:#fff;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,0.12);display:none;z-index:1200;padding:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><strong>Your wishlist</strong><button id="panel-wishlist-close" style="background:none;border:none;font-size:16px;cursor:pointer">✕</button></div>
            <div id="panel-wishlist-body">Loading…</div>
        </div>
    </div>