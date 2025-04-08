<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';


$userId = $_SESSION['id_user'] ?? null;
$productId = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;
$color = $_POST['color'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$userId || !$productId || !$size || !$color) {
    header("Location: /pages/catalog.php?error=1");
    exit;
}

// Проверка на существование такого товара в корзине
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?");
$stmt->bind_param("iiss", $userId, $productId, $size, $color);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // если товар уже есть — увеличиваем количество
    $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?");
    $stmt->bind_param("iiss", $userId, $productId, $size, $color);
    $stmt->execute();
} else {
    // иначе — добавляем новую запись
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size, color, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $userId, $productId, $size, $color, $quantity);
    $stmt->execute();
}

header("Location: /index.php?page=cart");
exit;