</section>

<!-- Category/Promo Grid -->
<section class="rey-category-promo" style="background: #fafafa; padding: 32px 0 0 0;">
  <div style="max-width: 1300px; margin: 0 auto; display: flex; gap: 18px; flex-wrap: nowrap; overflow-x: auto;">
    <div style="background: #fff; border-radius: 16px; min-width: 180px; height: 260px; display: flex; align-items: flex-end; justify-content: center; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden;">
      <img src="images/13-330x361.jpg" alt="Wearables" style="width: 100%; height: 100%; object-fit: cover; position: absolute; left: 0; top: 0; z-index: 0;">
      <span style="position: relative; z-index: 1; writing-mode: vertical-rl; text-orientation: mixed; font-size: 1.1rem; font-weight: 700; color: #fff; background: rgba(34,34,34,0.7); padding: 8px 4px; border-radius: 8px; margin-bottom: 18px;">Wearables</span>
    </div>
    <div style="background: #fff; border-radius: 16px; min-width: 180px; height: 260px; display: flex; align-items: flex-end; justify-content: center; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden;">
      <img src="images/2-936x1024.jpg" alt="Speakers" style="width: 100%; height: 100%; object-fit: cover; position: absolute; left: 0; top: 0; z-index: 0;">
      <span style="position: relative; z-index: 1; writing-mode: vertical-rl; text-orientation: mixed; font-size: 1.1rem; font-weight: 700; color: #fff; background: rgba(34,34,34,0.7); padding: 8px 4px; border-radius: 8px; margin-bottom: 18px;">Speakers</span>
    </div>
    <div style="background: #fff; border-radius: 16px; min-width: 180px; height: 260px; display: flex; align-items: flex-end; justify-content: center; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden;">
      <img src="images/25-330x361.jpg" alt="Headsets" style="width: 100%; height: 100%; object-fit: cover; position: absolute; left: 0; top: 0; z-index: 0;">
      <span style="position: relative; z-index: 1; writing-mode: vertical-rl; text-orientation: mixed; font-size: 1.1rem; font-weight: 700; color: #fff; background: rgba(34,34,34,0.7); padding: 8px 4px; border-radius: 8px; margin-bottom: 18px;">Headsets</span>
    </div>
    <div style="background: #fff; border-radius: 16px; min-width: 180px; height: 260px; display: flex; align-items: flex-end; justify-content: center; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden;">
      <img src="images/product-b.jpg" alt="Cameras" style="width: 100%; height: 100%; object-fit: cover; position: absolute; left: 0; top: 0; z-index: 0;">
      <span style="position: relative; z-index: 1; writing-mode: vertical-rl; text-orientation: mixed; font-size: 1.1rem; font-weight: 700; color: #fff; background: rgba(34,34,34,0.7); padding: 8px 4px; border-radius: 8px; margin-bottom: 18px;">Cameras</span>
    </div>
    <div style="background: #fff; border-radius: 16px; min-width: 180px; height: 260px; display: flex; align-items: flex-end; justify-content: center; position: relative; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden;">
      <img src="images/product-c.jpg" alt="Accessories" style="width: 100%; height: 100%; object-fit: cover; position: absolute; left: 0; top: 0; z-index: 0;">
      <span style="position: relative; z-index: 1; writing-mode: vertical-rl; text-orientation: mixed; font-size: 1.1rem; font-weight: 700; color: #fff; background: rgba(34,34,34,0.7); padding: 8px 4px; border-radius: 8px; margin-bottom: 18px;">Accessories</span>
    </div>
  </div>
</section>

<!-- Flash Deals Section (static version) -->
<section class="rey-flash-deals" style="background: #fff; padding: 48px 0 24px 0;">
  <div style="max-width: 1300px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 900; color: #222; margin: 0;">Flash Deals</h2>
      <a href="#" style="font-size: 1.1rem; font-weight: 600; color: #222; text-decoration: none; border-bottom: 2px solid #222; padding-bottom: 2px; letter-spacing: 1px;">VIEW ALL</a>
    </div>
    <div style="display: flex; gap: 32px; overflow-x: auto; padding-bottom: 8px;">
      <?php $i = 0; foreach ($products as $product): $prodImg = $babuiImages[$i % count($babuiImages)]; ?>
      <div style="background: #fafafa; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); min-width: 260px; max-width: 260px; flex: 0 0 260px; padding: 0 0 18px 0; display: flex; flex-direction: column; align-items: center; position: relative;">
        <span style="position: absolute; top: 16px; left: 16px; background: #1a8917; color: #fff; font-size: 0.95rem; font-weight: 700; border-radius: 6px; padding: 4px 12px;">Deal</span>
        <img src="images/<?php echo htmlspecialchars($prodImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 160px; object-fit: contain; border-radius: 10px 10px 0 0; margin-bottom: 0;">
        <div style="width: 100%; padding: 18px 18px 0 18px;">
          <div style="font-size: 1.08rem; color: #222; font-weight: 700; margin-bottom: 6px; min-height: 32px;"> <?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?> </div>
          <div style="font-size: 1rem; color: #222; font-weight: 900; margin-bottom: 8px;">$<?php echo number_format($product['price'], 2); ?></div>
        </div>
      </div>
      <?php $i++; endforeach; ?>
    </div>
  </div>
</section>

</section>

<!-- Feature Icons Row -->
<section class="rey-feature-icons" style="background: #fff; padding: 18px 0 8px 0; border-bottom: 1px solid #eee;">
  <div style="display: flex; justify-content: center; gap: 56px; max-width: 1200px; margin: 0 auto; flex-wrap: wrap;">
    <div style="display: flex; flex-direction: column; align-items: center; font-size: 1.1rem; color: #222;">
      <span style="font-size: 2rem; margin-bottom: 4px;">&#127942;</span>
      <span>Exclusive Products</span>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center; font-size: 1.1rem; color: #222;">
      <span style="font-size: 2rem; margin-bottom: 4px;">&#128230;</span>
      <span>Premium Packaging</span>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center; font-size: 1.1rem; color: #222;">
      <span style="font-size: 2rem; margin-bottom: 4px;">&#128230;</span>
      <span>Check Package on Delivery</span>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center; font-size: 1.1rem; color: #222;">
      <span style="font-size: 2rem; margin-bottom: 4px;">&#128179;</span>
      <span>30 days money back</span>
    </div>
    <div style="display: flex; flex-direction: column; align-items: center; font-size: 1.1rem; color: #222;">
      <span style="font-size: 2rem; margin-bottom: 4px;">&#128666;</span>
      <span>Free Shipping</span>
    </div>
  </div>
</section>

<!-- New Arrivals Carousel (static version) -->
<section class="rey-new-arrivals" style="background: #fafafa; padding: 48px 0 24px 0;">
  <div style="max-width: 1300px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 900; color: #222; margin: 0;">New Arrivals</h2>
      <a href="#" style="font-size: 1.1rem; font-weight: 600; color: #222; text-decoration: none; border-bottom: 2px solid #222; padding-bottom: 2px; letter-spacing: 1px;">VIEW ALL</a>
    </div>
    <div style="display: flex; gap: 32px; overflow-x: auto; padding-bottom: 8px;">
      <?php
      $carouselProducts = $products;
      $carouselImages = $babuiImages;
      $brands = ['X-FORM', 'QUANTECH', 'SQUAREX', 'QUANTECH'];
      $i = 0;
      foreach ($carouselProducts as $product):
        $prodImg = $carouselImages[$i % count($carouselImages)];
        $brand = $brands[$i % count($brands)];
      ?>
      <div style="background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); min-width: 260px; max-width: 260px; flex: 0 0 260px; padding: 0 0 18px 0; display: flex; flex-direction: column; align-items: center;">
        <img src="images/<?php echo htmlspecialchars($prodImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: 180px; object-fit: contain; border-radius: 10px 10px 0 0; margin-bottom: 0;">
        <div style="width: 100%; padding: 18px 18px 0 18px;">
          <div style="font-size: 0.9rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;"><?php echo $brand; ?></div>
          <div style="font-size: 1.08rem; color: #222; font-weight: 700; margin-bottom: 6px; min-height: 32px;"> <?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?> </div>
          <div style="font-size: 1rem; color: #222; font-weight: 900; margin-bottom: 8px;">$<?php echo number_format($product['price'], 2); ?></div>
          <div style="display: flex; gap: 6px; margin-bottom: 0;">
            <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: #bdbdbd;"></span>
            <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: #e6d1c3;"></span>
            <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: #222;"></span>
          </div>
        </div>
      </div>
      <?php $i++; endforeach; ?>
    </div>
  </div>
</section>


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

