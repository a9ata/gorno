<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Неверный метод запроса');
}

// Получаем данные
$subcategoryId = $_POST['subcategory'] ?? null;
$gender = $_POST['gender'] ?? '';
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? 0;
$colors = explode(',', $_POST['colors'] ?? '');
$sizes = explode(',', $_POST['sizes'] ?? '');
$quantity = $_POST['quantity'] ?? 0;
$mainImage = $_POST['main_image'] ?? '';
$additionalImages = explode(',', $_POST['additional_images'] ?? '');

// Валидация простая
if (!$subcategoryId || !$gender || !$name || !$price || !$mainImage) {
    die('Пожалуйста, заполните все обязательные поля');
}

// Вставка в products
$stmt = $conn->prepare("INSERT INTO products (subcategory_id, gender, name, description, price) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isssd", $subcategoryId, $gender, $name, $description, $price);
$stmt->execute();
$productId = $stmt->insert_id;

// Вставка главного изображения
$stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, 1)");
$stmt->bind_param("is", $productId, $mainImage);
$stmt->execute();

// Вставка дополнительных изображений
if (!empty($additionalImages)) {
    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, 0)");
    foreach ($additionalImages as $img) {
        $img = trim($img);
        if ($img) {
            $stmt->bind_param("is", $productId, $img);
            $stmt->execute();
        }
    }
}

// Вставка атрибутов: каждый цвет + размер = отдельная строка
$stmt = $conn->prepare("INSERT INTO product_attributes (product_id, size, color, quantity) VALUES (?, ?, ?, ?)");
foreach ($colors as $color) {
    foreach ($sizes as $size) {
        $stmt->bind_param("issi", $productId, trim($size), trim($color), $quantity);
        $stmt->execute();
    }
}

// Редирект
header("Location: /index.php?page=admin-products&success=1");
exit;