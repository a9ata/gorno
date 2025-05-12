<?php
session_start();
require_once __DIR__ . '/../../config/db.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Неверный метод.");
}

$id = $_POST['id'] ?? null;
$subcategory = $_POST['subcategory'] ?? null;
$gender = $_POST['gender'] ?? null;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? 0;

$colors = explode(',', $_POST['colors'] ?? '');
$sizes = explode(',', $_POST['sizes'] ?? '');
$quantity = $_POST['quantity'] ?? 0;

$mainImage = trim($_POST['main_image'] ?? '');
$additionalImages = array_map('trim', explode(',', $_POST['additional_images'] ?? ''));

if (!$id || !$subcategory || !$gender || !$name || !$price) {
    die("Недостаточно данных.");
}

// Обновление товара
$stmt = $conn->prepare("UPDATE products SET subcategory_id = ?, gender = ?, name = ?, description = ?, price = ? WHERE id = ?");
$stmt->bind_param("isssdi", $subcategory, $gender, $name, $description, $price, $id);
$stmt->execute();

// Очистка изображений и вставка заново
$stmt = $conn->prepare("DELETE FROM product_images WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($mainImage !== '') {
    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, 1)");
    $stmt->bind_param("is", $id, $mainImage);
    $stmt->execute();
}

foreach ($additionalImages as $url) {
    if ($url !== '') {
        $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_url, is_main) VALUES (?, ?, 0)");
        $stmt->bind_param("is", $id, $url);
        $stmt->execute();
    }
}

// Очистка и вставка атрибутов
$stmt = $conn->prepare("DELETE FROM product_attributes WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

foreach ($colors as $color) {
    foreach ($sizes as $size) {
        $color = trim($color);
        $size = trim($size);
        if ($color && $size) {
            $stmt = $conn->prepare("INSERT INTO product_attributes (product_id, color, size, quantity) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $id, $color, $size, $quantity);
            $stmt->execute();
        }
    }
}

header("Location: /admin/index.php?section=products&success=1");
exit;
?>