
<?php
// new-frontend.php: Rey-style landing page for demo and migration
require __DIR__ . '/config.php';
require __DIR__ . '/includes/header.php';
?>



<!-- Hero Section -->
<section class="elementor-section elementor-section-boxed hero" style="background-image: url('images/Hussain-rehar1536x1198.jpg');">
  <div class="elementor-container">
    <div class="hero-content elementor-widget">
      <h1>San Francisco</h1>
      <p>Discover the best of San Francisco fashion, inspired by Rey theme.</p>
      <a href="#products" class="btn elementor-button">Explore Products</a>
    </div>
  </div>
</section>

<!-- Product Grid -->
<div id="products" class="elementor-section elementor-section-boxed">
  <div class="elementor-container">
    <h2 class="elementor-heading-title">Featured Products</h2>
    <div class="products elementor-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 32px; padding: 32px 0;">
      <?php
      $babuiImages = [
        '12-330x361.jpg', '13-330x361.jpg', '2-936x1024.jpg', '25-330x361.jpg',
        '55-330x361.jpg', '56-330x361.jpg', '62-330x361.jpg', '63-330x361.jpg',
        '66-330x361.jpg', '67-330x361.jpg', 'Hussain-rehar1536x1198.jpg'
      ];
      $stmt = $pdo->query("SELECT * FROM products LIMIT 8");
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($products as $product):
        $prodImg = $babuiImages[array_rand($babuiImages)];
      ?>
        <div class="product elementor-widget" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); padding: 24px; text-align: center;">
          <img src="images/<?php echo htmlspecialchars($prodImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
          <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; color: #222;"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
          <p style="font-size: 0.95rem; color: #555; margin-bottom: 0;"><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

