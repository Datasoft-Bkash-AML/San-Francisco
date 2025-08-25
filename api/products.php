<?php
require_once __DIR__ . '/../config.php';

// Allow basic cross-origin for local testing and set JSON content type
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=60');

$products = $pdo->query('SELECT id, name, description, image, category_id FROM products ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
// Build absolute URLs for images (relative to site root)
$base = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')) . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
foreach ($products as &$p) {
    $img = $p['image'] ?: '';
    $p['image_url'] = $base . '/images/' . ltrim($img, '/');
}
echo json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
