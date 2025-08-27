


<?php
// new-frontend.php: Rey-style landing page for demo and migration
require __DIR__ . '/config.php';
require __DIR__ . '/includes/header.php';
?>



<!-- Hero/Main Grid Section -->
<section class="rey-hero-main" style="background: #fafafa; padding: 36px 0 0 0;">
  <div class="rey-hero-grid" style="display: grid; grid-template-columns: 2fr 1.2fr 1fr; gap: 28px; max-width: 1400px; margin: 0 auto; align-items: stretch;">
    <!-- Left: Large Feature -->
    <div style="background: #fff; border-radius: 18px; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; min-height: 420px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.06);">
      <img src="images/2-936x1024.jpg" alt="Outstanding performance" style="width: 100%; height: 320px; object-fit: cover;">
      <div style="padding: 32px 32px 28px 32px;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 2.2rem; font-weight: 900; color: #222; margin-bottom: 10px;">Outstanding<br>performance</h2>
        <a href="#" style="font-size: 1.1rem; font-weight: 600; color: #222; text-decoration: none; border-bottom: 2px solid #222; padding-bottom: 2px; letter-spacing: 1px;">EXPLORE &rarr;</a>
      </div>
    </div>
    <!-- Center: Dark Card -->
    <div style="background: #181818; border-radius: 18px; color: #fff; display: flex; flex-direction: column; justify-content: flex-end; min-height: 420px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.10);">
      <img src="images/13-330x361.jpg" alt="Apple Watch" style="width: 100%; height: 220px; object-fit: contain; margin-top: 32px;">
      <div style="padding: 32px 32px 28px 32px;">
        <div style="font-size: 0.95rem; color: #bbb; margin-bottom: 6px;">APPLE WATCH 8 SERIES</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 800; color: #fff; margin-bottom: 10px;">The future of health is<br>on your wrist</h3>
        <a href="#" style="font-size: 1.1rem; font-weight: 600; color: #fff; text-decoration: none; border-bottom: 2px solid #fff; padding-bottom: 2px; letter-spacing: 1px;">DISCOVER &rarr;</a>
      </div>
    </div>
    <!-- Right: Promo/Newsletter -->
    <div style="background: #e6d1c3; border-radius: 18px; display: flex; flex-direction: column; justify-content: flex-end; min-height: 420px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.08);">
      <div style="padding: 32px 32px 28px 32px;">
        <div style="font-size: 0.95rem; color: #a67c52; margin-bottom: 6px; font-weight: 700;">DON'T MISS</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 800; color: #222; margin-bottom: 18px;">Get 5% discount by<br>subscribing to our newsletter</h3>
        <form style="display: flex; gap: 8px;">
          <input type="email" placeholder="Your email address" style="flex:1; padding: 10px 14px; border-radius: 6px; border: none; font-size: 1rem;">
          <button type="submit" style="background: #222; color: #fff; font-weight: 700; border: none; border-radius: 6px; padding: 10px 22px; font-size: 1rem; cursor: pointer;">JOIN</button>
      </div>

