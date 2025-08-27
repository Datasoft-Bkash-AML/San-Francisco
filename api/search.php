<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$limit = isset($_GET['limit']) ? max(1, min(20, (int)$_GET['limit'])) : 6;

if ($q === '') {
    echo json_encode(['suggestions' => []]);
    exit;
}

$like = "%{$q}%";
$stmt = $pdo->prepare('SELECT id, name, description, image, category_id FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY rowid DESC LIMIT ?');
$stmt->execute([$like, $like, $limit]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$base = (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')) . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
foreach ($rows as &$r) {
    $img = $r['image'] ?? '';
    $r['image_url'] = preg_match('#^https?://#i', $img) ? $img : $base . '/images/' . ltrim($img, '/');
    $r['snippet'] = mb_substr(strip_tags($r['description'] ?? ''), 0, 120);
}

echo json_encode(['suggestions' => $rows], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
