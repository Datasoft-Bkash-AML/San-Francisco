<?php
// scripts/seed_products.php
// Run: php scripts/seed_products.php
require __DIR__ . '/../config.php';

// Only allow from CLI for safety
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from CLI.\n";
    exit(1);
}

echo "Seeding categories and products...\n";

$categories = [
    'Headphones',
    'Speakers',
    'Accessories'
];

// Ensure products table has a `featured` column (compatible with SQLite/MySQL)
try {
    $hasFeatured = false;
    $cols = $pdo->query("PRAGMA table_info(products)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        if (isset($c['name']) && $c['name'] === 'featured') { $hasFeatured = true; break; }
    }
    if (!$hasFeatured) {
        // SQLite / MySQL: adding a column with default 0
        $pdo->exec("ALTER TABLE products ADD COLUMN featured INTEGER DEFAULT 0");
    }
    // Ensure price column exists
    $hasPrice = false;
    foreach ($cols as $c) {
        if (isset($c['name']) && $c['name'] === 'price') { $hasPrice = true; break; }
    }
    if (!$hasPrice) {
        // price as REAL for SQLite, DECIMAL/DOUBLE for MySQL compatibility
        $pdo->exec("ALTER TABLE products ADD COLUMN price REAL DEFAULT 0");
    }
} catch (Exception $e) {
    // If PRAGMA not available (MySQL), try an INFORMATION_SCHEMA check for MySQL
    try {
        $hasFeatured = false;
        $stmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'products' AND COLUMN_NAME = 'featured'");
        $stmt->execute();
        if ($stmt->fetch()) $hasFeatured = true;
        if (!$hasFeatured) {
            $pdo->exec("ALTER TABLE products ADD COLUMN featured TINYINT(1) DEFAULT 0");
        }
    } catch (Exception $e2) {
        // give up silently; seeder will still work but won't set featured column
    }
}

// Ensure categories exist and map to IDs
$catIds = [];
foreach ($categories as $c) {
    // Prefer the numeric id column but fall back to rowid if id is empty
    $stmt = $pdo->prepare('SELECT id, rowid FROM categories WHERE name = ? ORDER BY rowid DESC LIMIT 1');
    $stmt->execute([$c]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        if (!empty($row['id'])) $catIds[$c] = (int)$row['id'];
        else $catIds[$c] = (int)$row['rowid'];
    } else {
        $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->execute([$c]);
        $last = $pdo->query('SELECT rowid FROM categories ORDER BY rowid DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
        $catIds[$c] = (int)$last['rowid'];
    }
}

// Prepare seed products sets (10-15 each)
// Prefer deterministic, local images from the repo `images/` folder so demos are reliable.
$seed = [];
$localHeadphones = ['product-a.jpg','product-90b4b9b8.jpg','25-330x361.jpg','13-330x361.jpg','55-330x361.jpg'];
$localSpeakers = ['2-936x1024.jpg','66-330x361.jpg','67-330x361.jpg','62-330x361.jpg'];
$localAccessories = ['product-b.jpg','product-c.jpg','56-330x361.jpg','63-330x361.jpg'];

for ($i = 1; $i <= 12; $i++) {
    $img = $localHeadphones[($i - 1) % count($localHeadphones)];
    $seed[] = [
        'category' => 'Headphones',
        'name' => "Studio Headphones H-{$i}",
        'description' => "High-fidelity studio headphones model H-{$i} with detailed sound, long battery life and comfortable ear cushions.",
        'image' => $img,
        'featured' => ($i === 1 || $i === 3) ? 1 : 0,
        'price' => round(49 + ($i * 8) + (($i % 3) * 5), 2)
    ];
}
for ($i = 1; $i <= 12; $i++) {
    $img = $localSpeakers[($i - 1) % count($localSpeakers)];
    $seed[] = [
        'category' => 'Speakers',
        'name' => "Home Speaker S-{$i}",
        'description' => "Compact home speaker S-{$i} delivering powerful bass and clear mids, ideal for living rooms.",
        'image' => $img,
        'featured' => ($i === 1) ? 1 : 0,
        'price' => round(79 + ($i * 10) + (($i % 2) * 7), 2)
    ];
}
for ($i = 1; $i <= 12; $i++) {
    $img = $localAccessories[($i - 1) % count($localAccessories)];
    $seed[] = [
        'category' => 'Accessories',
        'name' => "Accessory A-{$i}",
        'description' => "Accessory A-{$i}: durable, lightweight and built for daily use.",
        'image' => $img,
        'featured' => ($i === 2) ? 1 : 0,
        'price' => round(9 + ($i * 3) + (($i % 4) * 2), 2)
    ];
}

// Insert seed products (avoid duplicates by name)
$inserted = 0;
foreach ($seed as $p) {
    $catId = $catIds[$p['category']];
    $exists = $pdo->prepare('SELECT id FROM products WHERE name = ?');
    $exists->execute([$p['name']]);
    $row = $exists->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // Update including featured if the column exists
        $stmt = $pdo->prepare('UPDATE products SET category_id = ?, description = ?, image = ?, featured = ?, price = ? WHERE id = ?');
        $stmt->execute([$catId, $p['description'], $p['image'], $p['featured'] ?? 0, $p['price'] ?? 0, $row['id']]);
        $inserted++;
    } else {
        // Insert with featured column (works even if DB ignores unknown columns in some engines)
        $stmt = $pdo->prepare('INSERT INTO products (category_id, name, description, image, featured, price) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$catId, $p['name'], $p['description'], $p['image'], $p['featured'] ?? 0, $p['price'] ?? 0]);
        $inserted++;
    }
}

echo "Inserted {$inserted} products.\n";

// List counts
foreach ($categories as $c) {
    $cid = $catIds[$c];
    $cnt = $pdo->prepare('SELECT COUNT(*) as c FROM products WHERE category_id = ?');
    $cnt->execute([$cid]);
    $row = $cnt->fetch(PDO::FETCH_ASSOC);
    echo "Category {$c} (id={$cid}) has {$row['c']} products\n";
}

echo "Done.\n";
