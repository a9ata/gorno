<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Преобразуем одиночный параметр subcategory_id к массиву subcategory_ids[]
if (isset($_GET['subcategory_id'])) {
    if (!isset($_GET['subcategory_ids']) || !is_array($_GET['subcategory_ids'])) {
        $_GET['subcategory_ids'] = [];
    }
    $_GET['subcategory_ids'][] = $_GET['subcategory_id'];
    unset($_GET['subcategory_id']);
}

// Получаем фильтры из URL
$gender = $_GET['gender'] ?? null;
$filters = [
    'size_ids' => $_GET['size_ids'] ?? [],
    'colors' => $_GET['colors'] ?? [],
    'subcategory_ids' => $_GET['subcategory_ids'] ?? [],
    'price_min' => $_GET['price_min'] ?? null,
    'price_max' => $_GET['price_max'] ?? null,
];

// Получаем избранные товары, если пользователь авторизован
$favIds = [];
if (isset($_SESSION['id_user'])) {
    $userId = $_SESSION['id_user'];
    $res = $conn->prepare("SELECT product_id FROM favorites WHERE user_id = ?");
    $res->bind_param("i", $userId);
    $res->execute();
    $result = $res->get_result();
    while ($row = $result->fetch_assoc()) {
        $favIds[] = $row['product_id'];
    }
}

// Получаем отфильтрованные товары
$products = getFilteredProducts($conn, $gender, $filters);
?>

<section class="catalog-wrapper">
    <aside class="filter-sidebar">
        <?php include 'includes/filter.php'; ?>
    </aside>
    <div class="container product-list">
        <?php if (empty($products)): ?>
            <p class="no-results">По заданным фильтрам товаров не найдено.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <?php
                    $isFavorite = in_array($product['id'], $favIds);
                    $icon = $isFavorite 
                        ? ICONS_URL . 'favorite-active.svg' 
                        : ICONS_URL . 'favorite-default.svg';
                    $image = $product['image_url'] ?? (IMAGES_URL . 'products/default.png');
                ?>
                <div class="product-card">
                    <div class="product-favorite">
                        <button class="favorite-btn" data-id="<?= $product['id'] ?>">
                            <img src="<?= $icon ?>" alt="В избранное">
                        </button>
                    </div>
                    <div class="product-image">
                        <img src="<?= $image ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="subcategory"><?= htmlspecialchars($product['subcategory_name']) ?></p>
                        <p class="price"><?= htmlspecialchars($product['price']) ?> ₽</p>
                        <button type="button" onclick="openProductModal(<?= $product['id'] ?>)">Добавить в корзину</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>