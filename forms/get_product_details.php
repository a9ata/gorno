<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit;

$product = $conn->query("SELECT p.*, s.name AS subcategory FROM products p
                         LEFT JOIN subcategories s ON p.subcategory_id = s.id
                         WHERE p.id = $id")->fetch_assoc();

$images = $conn->query("SELECT image_url, is_main FROM product_images WHERE product_id = $id")->fetch_all(MYSQLI_ASSOC);
$attributes = $conn->query("SELECT color, size FROM product_attributes WHERE product_id = $id")->fetch_all(MYSQLI_ASSOC);

$mainImage = '';
foreach ($images as $img) {
    if ($img['is_main']) {
        $mainImage = $img['image_url'];
        break;
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
]);