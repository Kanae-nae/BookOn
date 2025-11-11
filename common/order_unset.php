<?php session_start(); ?>

<?php
// $_SESSION['order']を削除する処理
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

if ($action === 'order_unset') {
    if(isset($_SESSION['order'])){
        unset($_SESSION['order']);
    }
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'invalid action']);