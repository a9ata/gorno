<?php
session_start();
require_once '../config/db.php';

$userId = $_SESSION['id_user'] ?? null;
$productId = $_POST['product_id'] ?? null;

if (!$userId || !$productId) {
    http_response_code(400);
    exit('error');
}

// Проверим наличие в избранном
$stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $conn->query("DELETE FROM favorites WHERE user_id = $userId AND product_id = $productId");
    echo 'removed';
} else {
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    echo 'added';
}