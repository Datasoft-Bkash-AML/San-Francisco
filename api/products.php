<?php
require_once __DIR__ . '/../config.php';

// Allow basic cross-origin for local testing and set JSON content type
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=60');

$base = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')) . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

// Parameters: q, category_id, featured, page, per_page
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$category_id = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? intval($_GET['category_id']) : null;
$featured = isset($_GET['featured']) && $_GET['featured'] !== '' ? intval($_GET['featured']) : null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$per_page = isset($_GET['per_page']) ? max(1, min(100, (int)$_GET['per_page'])) : 24;

$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
    $like = "%{$q}%";
    $params[] = $like;
    $params[] = $like;
}
if ($category_id !== null) {
    $where[] = 'p.category_id = ?';
    $params[] = $category_id;
}
if ($featured !== null) {
    $where[] = 'p.featured = ?';
    $params[] = $featured ? 1 : 0;
}

$whereSql = '';
if (count($where)) $whereSql = 'WHERE ' . implode(' AND ', $where);

$offset = ($page - 1) * $per_page;

$sql = "SELECT p.id, p.name, p.description, p.image, p.category_id, p.featured, p.price FROM products p {$whereSql} ORDER BY p.rowid DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build image URLs
foreach ($products as &$p) {
    $img = $p['image'] ?? '';
    if (preg_match('#^https?://#i', $img)) {
        $p['image_url'] = $img;
    } else {
        $p['image_url'] = $base . '/images/' . ltrim($img, '/');
    }
    $p['price'] = isset($p['price']) ? (float)$p['price'] : null;
}

// Count total for paging
$countSql = "SELECT COUNT(*) as c FROM products p {$whereSql}";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute(array_slice($params, 0, count($params) - 2));
$total = (int)$countStmt->fetchColumn();

echo json_encode([
    'items' => $products,
    'total' => $total,
    'page' => $page,
    'per_page' => $per_page
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
