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
    <input type="hidden" name="id" value="<?= $productId ?>">

    <label>Категория:<br>
      <select name="category" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"
            <?= $cat['id'] == $categoryId ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label><br><br>

    <label>Подкатегория:<br>
      <select name="subcategory" required>
        <?php foreach ($subcategories as $sub): ?>
          <option value="<?= $sub['id'] ?>"
            <?= $sub['id'] == $subcategoryId ? 'selected' : '' ?>>
            <?= htmlspecialchars($sub['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label><br><br>

    <label>Пол:<br>
      <select name="gender" required>
        <option value="f" <?= $gender==='f' ? 'selected' : '' ?>>Женщина</option>
        <option value="m" <?= $gender==='m' ? 'selected' : '' ?>>Мужчина</option>
        <option value="g" <?= $gender==='g' ? 'selected' : '' ?>>Девочка</option>
        <option value="b" <?= $gender==='b' ? 'selected' : '' ?>>Мальчик</option>
      </select>
    </label><br><br>

    <label>Название:<br>
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
    </label><br><br>

    <label>Описание:<br>
      <textarea name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
    </label><br><br>

    <label>Цена (₽):<br>
      <input type="number" step="0.01" name="price" value="<?= $price ?>" required>
    </label><br><br>

    <label>Цвета (через запятую):<br>
      <input type="text" name="colors" value="<?= htmlspecialchars($colorsList) ?>">
    </label><br><br>

    <label>Размеры:<br>
      <select name="sizes[]" multiple>
        <?php foreach ($sizesListAll as $s): ?>
          <option value="<?= $s['id'] ?>" <?= in_array($s['name'], $sizes) ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label><br><br>


    <label>Количество:<br>
      <input type="number" name="quantity" value="<?= $quantity ?>" required>
    </label><br><br>

    <label>Главное изображение (URL):<br>
      <input type="text" name="main_image" value="<?= htmlspecialchars($mainImage) ?>">
    </label><br><br>

    <label>Доп. изображения (через запятую):<br>
      <input type="text" name="additional_images" value="<?= htmlspecialchars($additionalImages) ?>">
    </label><br><br>

    <button type="submit">Сохранить</button>
    <a href="/admin/index.php?section=products" style="margin-left:10px;">Отмена</a>
</form>