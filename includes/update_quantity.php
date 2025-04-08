<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['id_user'] ?? null;
$cartId = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$userId || !$cartId || !in_array($action, ['increase', 'decrease'])) {
    die("Неверные данные");
}

if ($action === 'increase') {
    $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
} else {
    // Уменьшаем, но не даём уйти в 0
    $stmt = $conn->prepare("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
}

$stmt->execute();

header("Location: /index.php?page=cart");
exit;
