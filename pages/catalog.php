<?php
    require_once 'config/db.php';
    require_once 'includes/functions.php'; 

    // Получаем фильтры из URL
    $gender = $_GET['gender'] ?? null;
    $filters = [
        'sizes' => $_GET['sizes'] ?? [],
        'colors' => $_GET['colors'] ?? [],
        'subcategory_ids' => $_GET['subcategory_ids'] ?? [],
        'price_min' => $_GET['price_min'] ?? null,
        'price_max' => $_GET['price_max'] ?? null,
    ];
    

    // Получаем отфильтрованные товары
    $products = getFilteredProducts($conn, $gender, $filters);
?>

<div class="catalog-wrapper">
    <aside class="filter-sidebar">
        <?php include 'includes/filter.php'; ?>
    </aside>
    <section class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <div class="product-favorite">
                <button class="favorite-btn" data-id="<?= $product['id'] ?>">
                    <img src="<?= ICONS_URL ?>favorite-default.svg" alt="">
                </button>
            </div>
            <div class="product-image">
                <?php
                    $image = $product['image_url'] ?? (IMAGES_URL . 'products/default.png');
                ?>
                <img src="<?= $image ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p class="subcategory"><?= htmlspecialchars($product['subcategory_name']) ?></p>
                <p class="price"><?= htmlspecialchars($product['price']) ?> ₽</p>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit">Добавить в корзину</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    </section>
</div>
