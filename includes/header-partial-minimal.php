<?php
// Minimal header partial for the demo - includes logo, main nav and header icons
?>
<header class="minimal-site-header" style="background: transparent;">
  <div class="minimal-header-inner" style="max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:18px 20px;">
    <a class="minimal-logo" href="/" style="display:flex;align-items:center;text-decoration:none;color:inherit;font-weight:700;">
      <img src="images/logo-black-mobile.svg" alt="Site" style="height:36px;display:block;">
    </a>

    <nav class="minimal-main-nav" style="display:flex;gap:22px;align-items:center;">
      <a href="#" style="text-decoration:none;color:inherit;font-weight:600;">Home</a>
      <a href="#" style="text-decoration:none;color:inherit;font-weight:600;">Shop</a>
      <a href="#" style="text-decoration:none;color:inherit;font-weight:600;">New Arrivals</a>
      <a href="#" style="text-decoration:none;color:inherit;font-weight:600;">Brands</a>
    </nav>

    <div class="minimal-header-icons" style="display:flex;gap:12px;align-items:center;">
      <button class="min-search-btn" aria-label="Search" style="background:transparent;border:0;cursor:pointer;font-weight:700;">Search</button>
      <a href="/wishlist.php" class="min-wishlist" aria-label="Wishlist" style="text-decoration:none;color:inherit;">♥</a>
      <a href="/cart.php" class="min-cart" aria-label="Cart" style="text-decoration:none;color:inherit;">🛒<span class="min-cart-count" style="font-weight:700;margin-left:6px;">0</span></a>
      <button class="min-mobile-toggle" aria-label="Open menu" style="display:none;border:0;background:transparent;cursor:pointer;font-weight:700;">☰</button>
    </div>
  </div>
</header>
