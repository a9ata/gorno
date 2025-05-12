<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Неверный метод запроса');
}

// 1) Простая валидация бренных полей
$subcategoryId = $_POST['subcategory'] ?? null;
$gender        = $_POST['gender'] ?? '';
$name          = trim($_POST['name'] ?? '');
$description   = trim($_POST['description'] ?? '');
$price         = floatval($_POST['price'] ?? 0);
$quantity      = intval($_POST['quantity'] ?? 0);
$mainImage     = trim($_POST['main_image'] ?? '');

if (!$subcategoryId || !$gender || !$name || $price <= 0 || !$mainImage) {
    die('Пожалуйста, заполните все обязательные поля');
}

// 2) Цвета и размеры
$rawColors = $_POST['colors'] ?? '';
$rawSizes  = $_POST['size']  ?? '';
$colors    = is_array($rawColors) ? $rawColors : explode(',', $rawColors);
$sizes     = is_array($rawSizes)  ? $rawSizes  : explode(',', $rawSizes);
$colors    = array_filter(array_map('trim', $colors), fn($v) => $v !== '');
$sizes     = array_filter(array_map('trim', $sizes),  fn($v) => $v !== '');

// 3) Добавляем продукт
$stmt = $conn->prepare("
    INSERT INTO products 
      (subcategory_id, gender, name, description, price)
    VALUES 
      (?, ?, ?, ?, ?)
");
$stmt->bind_param("isssd", $subcategoryId, $gender, $name, $description, $price);
$stmt->execute();
$productId = $stmt->insert_id;
$stmt->close();

// 4) Добавляем изображения (main + дополн.)
$stmt = $conn->prepare("
    INSERT INTO product_images 
      (product_id, image_url, is_main) VALUES (?, ?, ?)
");
$stmt->bind_param("isi", $productId, $uri, $isMain);
// главное
$uri    = $mainImage; 
$isMain = 1;
$stmt->execute();
// доп. (если есть)
foreach (preg_split('/\s*,\s*/', $_POST['additional_images'] ?? '') as $uri) {
    if ($uri = trim($uri)) {
        $isMain = 0;
        $stmt->execute();
    }
}
$stmt->close();

// 5) Добавляем атрибуты, только если и цвета и размеры есть
if ($colors && $sizes) {
    $stmt = $conn->prepare("
        INSERT INTO product_attributes
          (product_id, color, size, quantity)
        VALUES
          (?, ?, ?, ?)
    ");
    foreach ($colors as $color) {
        foreach ($sizes as $size) {
            $stmt->bind_param("issi", $productId, $color, $size, $quantity);
            $stmt->execute();
        }
    }
    $stmt->close();
} else {
    error_log("Skipped attributes for product {$productId}: colors=".
              json_encode($colors)." sizes=".json_encode($sizes));
}

// 6) Редирект в админку
header("Location: /admin/index.php?section=products&success=1");
exit;