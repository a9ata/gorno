<?php 
    require_once 'includes/functions.php'; 
?>
<form method="GET" action="index.php">
    <input type="hidden" name="page" value="catalog">
    <input type="hidden" name="gender" value="<?= htmlspecialchars($_GET['gender'] ?? '') ?>">

    <h2>Фильтр</h2>
    <ul class="category-filter">
        <?php foreach (getAllCategories($conn) as $cat): ?>
            <li>
                <h4><?= $cat['name'] ?></h4>
                <ul>
                    <?php foreach ($cat['subcategories'] as $sub): ?>
                        <li>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="subcategory_ids[]" value="<?= $sub['id'] ?>">
                                <?= $sub['name'] ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>Цена</h4>
    <ul class="price-filter">
        <li><label>от <input type="number" name="price_min" min="0" step="0.01"></label></li>
        <li><label>до <input type="number" name="price_max" min="0" step="0.01"></label></li>
    </ul>

    <h4>Размеры одежды</h4>
    <ul class="size-filter">
        <?php foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size): ?>
            <li>
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="sizes[]" value="<?= $size ?>"> <?= $size ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>Размеры обуви</h4>
    <ul class="shoe-size-filter">
        <?php for ($i = 16; $i <= 45; $i++): ?>
            <li>
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="sizes[]" value="<?= $i ?>"> <?= $i ?>
                </label>
            </li>
        <?php endfor; ?>
    </ul>

    <h4>Цвета</h4>
    <ul class="color-filter">
        <?php foreach (['Белый', 'Черный', 'Синий', 'Красный', 'Зеленый', 'Голубой'] as $color): ?>
            <li>
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="colors[]" value="<?= $color ?>"> <?= $color ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>

    <button type="submit">Применить</button>
</form>