<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';


$userId = $_SESSION['id_user'] ?? null;
$cartId = $_POST['cart_id'] ?? null;

if (!$userId || !$cartId) {
    http_response_code(400);
    echo 'Ошибка запроса';
    exit;
}

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cartId, $userId);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'error';
}