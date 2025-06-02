<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Получаем категории, подкатегории, цвета и размеры из БД
$categories = $conn->query("SELECT id, name FROM categories")->fetch_all(MYSQLI_ASSOC);
$subcategories = $conn->query("SELECT id, name FROM subcategories")->fetch_all(MYSQLI_ASSOC);
function getEnumValues(mysqli $conn, string $table, string $column): array {
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($result) {
        $row = $result->fetch_assoc();
        if (preg_match("/^enum\((.*)\)$/", $row['Type'], $matches)) {
            $enum = str_getcsv($matches[1], ',', "'");
            return $enum;
        }
    }
    return [];
}
$sizes = $conn->query("SELECT id, name FROM sizes ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$colors = getEnumValues($conn, 'product_attributes', 'color');

?>

<h3>Добавить товар</h3>
<form method="post" action="/admin/add/product_add.php" class="add-product-form">
    <label>Категория:</label>
    <select name="category" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Подкатегория:</label>
    <select name="subcategory" required>
        <?php foreach ($subcategories as $sub): ?>
            <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Пол:</label>
    <select name="gender" required>
        <option value="f">Женщина</option>
        <option value="m">Мужчина</option>
        <option value="g">Девочка</option>
        <option value="b">Мальчик</option>
    </select>

    <label>Название:</label>
    <input type="text" name="name" required>

    <label>Описание:</label>
    <textarea name="description"></textarea>

    <label>Цена:</label>
    <input type="number" step="0.01" name="price" required>

    <label>Цвета:</label>
    <select name="colors[]" multiple>
        <?php foreach ($colors as $color): ?>
            <option value="<?= $color ?>"><?= $color ?></option>
        <?php endforeach; ?>
    </select>


    <label>Размеры:</label>
    <select name="size_ids[]" multiple>
        <?php foreach ($sizes as $size): ?>
            <option value="<?= $size['id'] ?>"><?= $size['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Количество:</label>
    <input type="number" name="quantity" required>

    <label>Главное изображение (URL):</label>
    <input type="text" name="main_image" required>

    <label>Дополнительные изображения (через запятую, URL):</label>
    <input type="text" name="additional_images">

    <button type="submit">Добавить</button>
</form>