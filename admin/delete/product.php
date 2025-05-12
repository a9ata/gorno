<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_POST['product_id'])) {
    die("Некорректный запрос");
}

$productId = intval($_POST['product_id']);

// Удаляем связанные изображения
$stmt = $conn->prepare("DELETE FROM product_images WHERE product_id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();

// Удаляем связанные атрибуты
$stmt = $conn->prepare("DELETE FROM product_attributes WHERE product_id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();

// Удаляем сам товар
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();

header("Location: /admin/index.php?section=products&success=1");
exit;
