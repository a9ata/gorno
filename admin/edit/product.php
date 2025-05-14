<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

$productId = $_GET['id'] ?? null;
if (!$productId) {
    die("Товар не найден.");
}

// Получаем данные товара
$sql = "
    SELECT 
        p.*, 
        s.category_id, 
        s.name AS subcategory_name,
        s.id AS subcategory_id
    FROM products p
    JOIN subcategories s ON p.subcategory_id = s.id
    WHERE p.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Получаем изображения
$stmt = $conn->prepare("SELECT * FROM product_images WHERE product_id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Получаем атрибуты
$stmt = $conn->prepare("SELECT * FROM product_attributes WHERE product_id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$attributes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$colors = array_unique(array_column($attributes, 'color'));
$sizes = array_unique(array_column($attributes, 'size'));
$quantity = $attributes[0]['quantity'] ?? 0;

// Получаем подкатегории и категории
$subcategories = $conn->query("SELECT id, name FROM subcategories")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT id, name FROM categories")->fetch_all(MYSQLI_ASSOC);

?>

<h2>Редактировать товар: <?= htmlspecialchars($product['name']) ?></h2>
<form method="post" action="/admin/edit/product_update.php" class="edit-product-form">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <label>Категория:</label>
    <select name="category">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $product['category_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Подкатегория:</label>
    <select name="subcategory">
        <?php foreach ($subcategories as $sub): ?>
            <option value="<?= $sub['id'] ?>" <?= ($sub['id'] == $product['subcategory_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($sub['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Пол:</label>
    <select name="gender">
        <option value="f" <?= $product['gender'] === 'f' ? 'selected' : '' ?>>Женщина</option>
        <option value="m" <?= $product['gender'] === 'm' ? 'selected' : '' ?>>Мужчина</option>
        <option value="g" <?= $product['gender'] === 'g' ? 'selected' : '' ?>>Девочка</option>
        <option value="b" <?= $product['gender'] === 'b' ? 'selected' : '' ?>>Мальчик</option>
    </select>

    <label>Название:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <label>Описание:</label>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Цвета:</label>
    <input type="text" name="colors" value="<?= implode(',', $colors) ?>">

    <label>Размеры:</label>
    <input type="text" name="sizes" value="<?= implode(',', $sizes) ?>">

    <label>Количество:</label>
    <input type="number" name="quantity" value="<?= $quantity ?>">

    <label>Главное изображение:</label>
    <input type="text" name="main_image" value="<?= htmlspecialchars($images[0]['image_url'] ?? '') ?>">

    <label>Доп. изображения (через запятую):</label>
    <input type="text" name="additional_images" value="<?php
        $addImgs = array_filter($images, fn($img) => !$img['is_main']);
        echo implode(',', array_column($addImgs, 'image_url'));
    ?>">

    <button type="submit">Сохранить</button>
    <a href="/admin/index.php?section=users">Назад</a>
</form>
