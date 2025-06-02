<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';


$userId = $_SESSION['id_user'] ?? null;
$productId = $_POST['product_id'] ?? null;
$sizeId = $_POST['size_id'] ?? null;
$color = $_POST['color'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$userId || !$productId || !$sizeId || !$color) {
    header("Location: /pages/catalog.php?error=1");
    exit;
}

// Проверка на существование такого товара в корзине
$stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ? AND size_id = ? AND color = ?");
$stmt->bind_param("iiis", $userId, $productId, $sizeId, $color);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // если товар уже есть — увеличиваем количество
    $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ? AND size_id = ? AND color = ?");
    $stmt->bind_param("iiis", $userId, $productId, $sizeId, $color);
    $stmt->execute();
} else {
    // иначе — добавляем новую запись
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size_id, color, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisi", $userId, $productId, $sizeId, $color, $quantity);
    $stmt->execute();
}

header("Location: /index.php?page=cart");
exit;