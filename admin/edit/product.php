<?php
// Получаем списки категорий и подкатегорий
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name")
                  ->fetch_all(MYSQLI_ASSOC);
$subcategories = $conn->query("SELECT id, name FROM subcategories ORDER BY name")
                     ->fetch_all(MYSQLI_ASSOC);

// Извлекаем данные из $edit
$productId     = $edit['id'];
$name          = $edit['name'];
$description   = $edit['description'];
$price         = $edit['price'];
$gender        = $edit['gender'];
$categoryId    = $edit['category_id'];
$subcategoryId = $edit['subcategory_id'];
$images        = $edit['images'] ?? [];
$colors        = $edit['colors']   ?? [];
$sizesListAll = $conn->query("SELECT id, name FROM sizes ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$quantity      = $edit['quantity'] ?? 0;

// Формируем строки для изображений и атрибутов
$mainImage = '';
$additional = [];
foreach ($images as $img) {
    if ($img['is_main']) {
        $mainImage = $img['image_url'];
    } else {
        $additional[] = $img['image_url'];
    }
}
$additionalImages = implode(',', $additional);
$colorsList       = implode(',', $colors);
$sizesList        = implode(',', $sizes);
?>
<h3>Редактировать товар #<?= $productId ?>: <?= htmlspecialchars($name) ?></h3>
<form method="post" action="/admin/edit/product_update.php" class="edit-product-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($productId) ?>">

    <label>Категория:
      <select name="category" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['id']) ?>"
            <?= htmlspecialchars($cat['id'] == $categoryId ? 'selected' : '') ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Подкатегория:
      <select name="subcategory" required>
        <?php foreach ($subcategories as $sub): ?>
          <option value="<?= htmlspecialchars($sub['id']) ?>""
            <?= htmlspecialchars($sub['id'] == $subcategoryId ? 'selected' : '') ?>>
            <?= htmlspecialchars($sub['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>Пол:
      <select name="gender" required>
        <option value="f" <?= htmlspecialchars($gender==='f' ? 'selected' : '') ?>>Женщина</option>
        <option value="m" <?= htmlspecialchars($gender==='m' ? 'selected' : '') ?>>Мужчина</option>
        <option value="g" <?= htmlspecialchars($gender==='g' ? 'selected' : '') ?>>Девочка</option>
        <option value="b" <?= htmlspecialchars($gender==='b' ? 'selected' : '') ?>>Мальчик</option>
      </select>
    </label>

    <label>Название:
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
    </label>

    <label>Описание:
      <textarea name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
    </label>

    <label>Цена (₽):
      <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>" required>
    </label>

    <label>Цвета (через запятую):
      <input type="text" name="colors" value="<?= htmlspecialchars($colorsList) ?>">
    </label>

    <label>Размеры:
      <select name="sizes[]" multiple>
        <?php foreach ($sizesListAll as $s): ?>
          <option value="<?= htmlspecialchars($s['id']) ?>" <?= htmlspecialchars(in_array($s['name'], $sizes) ? 'selected' : '') ?>>
            <?= htmlspecialchars($s['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>


    <label>Количество:
      <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>" required>
    </label>

    <label>Главное изображение (URL):
      <input type="text" name="main_image" value="<?= htmlspecialchars($mainImage) ?>">
    </label>

    <label>Доп. изображения (через запятую):
      <input type="text" name="additional_images" value="<?= htmlspecialchars($additionalImages) ?>">
    </label>

    <button type="submit">Сохранить</button>
    <a href="?section=products" class="btn-secondary">Отмена</a>
</form>