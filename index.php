<?php
require __DIR__ . '/config.php';
require __DIR__ . '/includes/header.php';

// Fetch products for landing page
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$offset = 0;
$limit = 10;

// Build query with optional category filter
if ($categoryId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :categoryId LIMIT :offset, :limit");
    $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
} else {
    $stmt = $pdo->prepare("SELECT * FROM products LIMIT :offset, :limit");
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (!empty($products)): ?>
    <?php $hero = $products[0]; ?>
    <?php
        // Prefer svg/png/jpg variant based on available files
        $imgBase = pathinfo($hero['image'], PATHINFO_FILENAME);
        $candidates = ["$imgBase.jpg", "$imgBase.png", "$imgBase.svg", $hero['image']];
        $heroImg = $hero['image'];
        foreach ($candidates as $cand) {
            if (file_exists(__DIR__ . '/images/' . $cand)) { $heroImg = $cand; break; }
        }
    ?>
    <section class="hero" style="background-image: url('images/<?php echo htmlspecialchars($heroImg, ENT_QUOTES, 'UTF-8'); ?>');">
        <div class="hero-content">
            <h2><?php echo htmlspecialchars($hero['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><?php echo htmlspecialchars($hero['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="#products" class="btn">Explore Products</a>
        </div>
    </section>
<?php endif; ?>

<div id="products" class="container">
    <div class="products">
        <?php foreach ($products as $product): ?>
            <?php
                $imgBase = pathinfo($product['image'], PATHINFO_FILENAME);
                $candidates = ["$imgBase.jpg", "$imgBase.png", "$imgBase.svg", $product['image']];
                $prodImg = $product['image'];
                foreach ($candidates as $cand) {
                    if (file_exists(__DIR__ . '/images/' . $cand)) { $prodImg = $cand; break; }
                }
            ?>
            <div class="product" data-product-id="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <img src="images/<?php echo htmlspecialchars($prodImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // expose initial products to JS for progressive hydration
    window.__initialProducts = <?php echo json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>