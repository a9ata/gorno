<form method="GET" action="index.php">
    <input type="hidden" name="page" value="catalog">
    <input type="hidden" name="gender" value="<?= htmlspecialchars($_GET['gender'] ?? '') ?>">

    <h2>Фильтр</h2>
    <ul class="category-filter">
        <?php foreach (getAllCategories($conn) as $cat): ?>
            <li>
                <h4><?= htmlspecialchars($cat['name']) ?></h4>
                <ul>
                    <?php foreach ($cat['subcategories'] as $sub): ?>
                        <li>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" name="subcategory_ids[]" value="<?= htmlspecialchars($sub['id']) ?>"
                                <?= in_array($sub['id'], $_GET['subcategory_ids'] ?? []) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($sub['name']) ?>
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
        <?php foreach (getSizesByType($conn, 'clothing') as $size): ?>
            <li>
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="size_ids[]" value="<?= htmlspecialchars($size['id']) ?>"
                        <?= in_array($size['id'], $_GET['size_ids'] ?? []) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($size['name']) ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>Размеры обуви</h4>
    <ul class="size-filter grid-sizes">
        <?php foreach (getSizesByType($conn, 'shoes') as $size): ?>
            <li>
                <label>
                    <input type="checkbox" name="size_ids[]" value="<?= htmlspecialchars($size['id']) ?>"
                        <?= in_array($size['id'], $_GET['size_ids'] ?? []) ? 'checked' : '' ?>>
                    <span><?= htmlspecialchars($size['name']) ?></span>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>


    <h4>Цвета</h4>
    <ul class="color-filter">
        <?php foreach (getAvailableColors($conn) as $color): ?>
            <li>
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="colors[]" value="<?= htmlspecialchars($color) ?>" 
                        <?= in_array($color, $_GET['colors'] ?? []) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($color) ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>

    <button type="submit">Применить</button>
</form>