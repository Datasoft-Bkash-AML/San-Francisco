<?php
require_once __DIR__ . '/../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if ($method === 'GET') {
    $items = [];
    foreach ($_SESSION['wishlist'] as $pid) {
        $stmt = $pdo->prepare('SELECT id, name, image FROM products WHERE id = ?');
        $stmt->execute([$pid]);
        if ($p = $stmt->fetch(PDO::FETCH_ASSOC)) $items[] = $p;
    }
    echo json_encode(['items' => $items, 'count' => count($items)], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    $product_id = isset($input['product_id']) ? (int)$input['product_id'] : null;
    if (!$product_id) { http_response_code(400); echo json_encode(['success' => false, 'error' => 'product_id required']); exit; }
    if (!in_array($product_id, $_SESSION['wishlist'])) $_SESSION['wishlist'][] = $product_id;
    echo json_encode(['success' => true, 'count' => count($_SESSION['wishlist'])]);
    exit;
}

if ($method === 'DELETE') {
    $product_id = isset($input['product_id']) ? (int)$input['product_id'] : (isset($_GET['product_id']) ? (int)$_GET['product_id'] : null);
    if ($product_id) {
        $_SESSION['wishlist'] = array_values(array_filter($_SESSION['wishlist'], function($v) use ($product_id) { return $v != $product_id; }));
    }
    echo json_encode(['success' => true, 'count' => count($_SESSION['wishlist'])]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
