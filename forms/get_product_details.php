<?php
session_start();
require_once '../config/db.php';
require_once '../config/config.php'; // если у тебя там ICONS_URL

$id = $_GET['id'] ?? null;
if (!$id) exit;

$product = $conn->query("SELECT p.*, s.name AS subcategory FROM products p
                         LEFT JOIN subcategories s ON p.subcategory_id = s.id
                         WHERE p.id = $id")->fetch_assoc();

$images = $conn->query("SELECT image_url, is_main FROM product_images WHERE product_id = $id")->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("
    SELECT pa.color, pa.size_id, s.name AS size_name
    FROM product_attributes pa
    JOIN sizes s ON pa.size_id = s.id
    WHERE pa.product_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$attributes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Получаем основное изображение
$mainImage = '';
foreach ($images as $img) {
    if ($img['is_main']) {
        $mainImage = $img['image_url'];
        break;
    }
}

// Проверяем, добавлен ли товар в избранное
$isFavorite = false;
$icon = ICONS_URL . 'favorite-default.svg'; // по умолчанию

if (isset($_SESSION['id_user'])) {
    $userId = $_SESSION['id_user'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $isFavorite = true;
        $icon = ICONS_URL . 'favorite-active.svg';
    }
}

echo json_encode([
    'id' => $product['id'],
    'name' => $product['name'],
    'subcategory' => $product['subcategory'],
    'price' => $product['price'],
    'main_image' => $mainImage,
    'images' => $images,
    'attributes' => $attributes,
    'is_favorite' => $isFavorite,
    'icon' => $icon
]);