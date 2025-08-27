<?php
require_once __DIR__ . '/../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Initialize cart in session
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart'] = [];

$method = $_SERVER['REQUEST_METHOD'];
// POST: add or update; GET: fetch; DELETE: remove via body or query
if ($method === 'GET') {
    // build cart details
    $items = [];
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare('SELECT id, name, image, price FROM products WHERE id = ?');
        $stmt->execute([$pid]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$p) continue;
        $price = isset($p['price']) ? (float)$p['price'] : 0.0;
        $subtotal = $price * (int)$qty;
        $items[] = ['product_id' => (int)$p['id'], 'qty' => (int)$qty, 'name' => $p['name'], 'image' => $p['image'], 'price' => $price, 'subtotal' => round($subtotal,2)];
    }
    // compute total
    $total = 0.0;
    foreach ($items as $it) $total += ($it['subtotal'] ?? 0);
    echo json_encode(['items' => $items, 'count' => array_sum($_SESSION['cart']), 'total' => round($total,2)], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if ($method === 'POST') {
    $product_id = isset($input['product_id']) ? (int)$input['product_id'] : null;
    $qty = isset($input['qty']) ? max(0, (int)$input['qty']) : 1;
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'product_id required']);
        exit;
    }
    // check product exists
    $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }
    if ($qty <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        // increment if exists
        if (isset($_SESSION['cart'][$product_id])) $_SESSION['cart'][$product_id] += $qty;
        else $_SESSION['cart'][$product_id] = $qty;
    }
    // return updated cart count and totals
    $count = array_sum($_SESSION['cart']);
    // compute total quickly
    $total = 0.0;
    foreach ($_SESSION['cart'] as $pid => $q) {
        $stmt = $pdo->prepare('SELECT price FROM products WHERE id = ?');
        $stmt->execute([$pid]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        $price = isset($p['price']) ? (float)$p['price'] : 0.0;
        $total += $price * $q;
    }
    echo json_encode(['success' => true, 'count' => $count, 'total' => round($total,2)]);
    exit;
}

if ($method === 'DELETE') {
    // remove product
    $product_id = isset($input['product_id']) ? (int)$input['product_id'] : (isset($_GET['product_id']) ? (int)$_GET['product_id'] : null);
    if ($product_id && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
