<?php
require_once __DIR__ . '/../../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');

// Must be admin
if (empty($_SESSION['is_admin'])) { http_response_code(403); echo json_encode(['success'=>false,'error'=>'unauthorized']); exit; }

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
if (empty($input['csrf']) || !csrf_check($input['csrf'])) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'csrf']); exit; }
if (empty($input['id'])) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'id required']); exit; }
$id = (int)$input['id'];
$featured = !empty($input['featured']) ? 1 : 0;

$stmt = $pdo->prepare('UPDATE products SET featured = ? WHERE id = ?');
$stmt->execute([$featured, $id]);
echo json_encode(['success'=>true,'id'=>$id,'featured'=>$featured]);
