<?php
require_once __DIR__ . '/../../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['is_admin'])) { http_response_code(403); echo json_encode(['success'=>false,'error'=>'unauthorized']); exit; }
if (empty($_POST['csrf']) || !csrf_check($_POST['csrf'])) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'csrf']); exit; }

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success'=>false,'error'=>'no file']);
    exit;
}

$allowed = ['image/jpeg' => '.jpg', 'image/png' => '.png'];
$up = $_FILES['image'];
if (!isset($allowed[$up['type']])) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'invalid type']); exit; }

$ext = $allowed[$up['type']];
$fname = 'product-' . bin2hex(random_bytes(6)) . $ext;
$dst = __DIR__ . '/../../images/' . $fname;
if (move_uploaded_file($up['tmp_name'], $dst)) {
    echo json_encode(['success'=>true,'filename'=>$fname]);
} else {
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>'move failed']);
}
