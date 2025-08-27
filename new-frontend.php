


<?php
// new-frontend.php: Rey-style landing page for demo and migration
require __DIR__ . '/config.php';
require __DIR__ . '/includes/header.php';
?>



<!-- Hero/Main Grid Section -->
<section class="rey-hero-main" style="background: #fafafa; padding: 36px 0 0 0;">
  <div class="rey-hero-grid" style="display: grid; grid-template-columns: 2fr 1.2fr 1fr; gap: 28px; max-width: 1400px; margin: 0 auto; align-items: stretch;">
    <!-- Left: Large Feature (dark background, bold white text) -->
    <div style="background: #232323; border-radius: 22px; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; min-height: 480px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.10);">
      <img src="images/2-936x1024.jpg" alt="Innovative, wireless home speaker" style="width: 100%; height: 340px; object-fit: cover; filter: brightness(0.85);">
      <div style="padding: 40px 40px 36px 40px; position: absolute; left: 0; bottom: 0; width: 100%;">
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 2.6rem; font-weight: 900; color: #fff; margin-bottom: 18px; line-height: 1.1;">Innovative, wireless home<br>speaker</h2>
        <a href="#" style="font-size: 1.1rem; font-weight: 700; color: #fff; text-decoration: none; border-bottom: 2px solid #fff; padding-bottom: 2px; letter-spacing: 1px;">Discover &rarr;</a>
      </div>
    </div>
    <!-- Center: Apple Watch Card (black background, white text, product image) -->
    <div style="background: #181818; border-radius: 22px; color: #fff; display: flex; flex-direction: column; justify-content: flex-end; min-height: 480px; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.12);">
      <img src="images/13-330x361.jpg" alt="Apple Watch" style="width: 70%; height: 220px; object-fit: contain; margin: 40px auto 0 auto; display: block;">
      <div style="padding: 40px 40px 36px 40px;">
        <div style="font-size: 1rem; color: #bbb; margin-bottom: 8px; letter-spacing: 1px;">APPLE WATCH 8 SERIES</div>
        <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 900; color: #fff; margin-bottom: 18px; line-height: 1.15;">The future of health is<br>on your wrist</h3>
        <a href="#" style="font-size: 1.1rem; font-weight: 700; color: #fff; text-decoration: none; border-bottom: 2px solid #fff; padding-bottom: 2px; letter-spacing: 1px;">Discover &rarr;</a>
      </div>
    </div>
    <!-- Right: Newsletter and Promo Card -->
    <div style="display: flex; flex-direction: column; gap: 18px; min-height: 480px;">
      <!-- Newsletter Card -->
      <div style="background: #e6d1c3; border-radius: 22px; flex: 1 1 0; display: flex; flex-direction: column; justify-content: flex-end; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.08);">
        <div style="padding: 40px 40px 36px 40px;">
          <div style="font-size: 1rem; color: #a67c52; margin-bottom: 8px; font-weight: 700; letter-spacing: 1px;">DON'T MISS</div>
          <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.35rem; font-weight: 900; color: #222; margin-bottom: 22px; line-height: 1.2;">Get 5% discount by<br>subscribing to our newsletter</h3>
          <form style="display: flex; gap: 8px;">
            <input type="email" placeholder="Your email address" style="flex:1; padding: 12px 16px; border-radius: 8px; border: none; font-size: 1rem;">
            <button type="submit" style="background: #222; color: #fff; font-weight: 900; border: none; border-radius: 8px; padding: 12px 28px; font-size: 1rem; cursor: pointer;">JOIN</button>
          </form>
        </div>
      </div>
      <!-- Promo Card -->
      <div style="background: #fff; border-radius: 22px; flex: 1 1 0; display: flex; flex-direction: column; justify-content: flex-end; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.08); min-height: 180px;">
        <img src="images/25-330x361.jpg" alt="With active noise cancelling" style="width: 100%; height: 120px; object-fit: cover; border-radius: 22px 22px 0 0;">
        <div style="padding: 24px 32px 20px 32px;">
          <div style="font-size: 0.95rem; color: #888; margin-bottom: 6px; font-weight: 700; letter-spacing: 1px;">EB 3RD GEN</div>
          <h4 style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 900; color: #222; margin-bottom: 0; line-height: 1.2;">With active noise<br>cancelling</h4>
        </div>
      </div>
    </div>
  </div>
</section>

