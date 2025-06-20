<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php'; 

$userId = $_SESSION['id_user'] ?? null;

// Если пользователь авторизован — грузим его избранное, иначе оставляем $products = null
if ($userId) {
    $sql = "SELECT 
                p.*, 
                s.name AS subcategory_name, 
                ANY_VALUE(pi.image_url) AS image_url
            FROM favorites f
            JOIN products p ON f.product_id = p.id
            LEFT JOIN subcategories s ON p.subcategory_id = s.id
            LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
            WHERE f.user_id = ?
            GROUP BY p.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $products = null;
}
?>

<section class="favorite-list">
    <h2>Избранное</h2>
    <div class="product-list">
        <!-- 1) Не авторизован -->
        <?php if ($products === null): ?>
            <p>Авторизуйтесь, чтобы видеть избранное.</p>

        <!-- 2) Авторизован, но нет товаров -->
        <?php elseif (empty($products)): ?>
            <p>У вас пока нет избранных товаров.</p>

        <!-- 3) Есть товары -->
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-favorite">
                        <button class="favorite-btn" data-id="<?= htmlspecialchars($product['id']) ?>">
                            <img src="<?= ICONS_URL ?>favorite-active.svg" alt="Удалить из избранного">
                        </button>
                    </div>
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['image_url'] ?? IMAGES_URL . 'products/default.png') ?>"
                             alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="subcategory"><?= htmlspecialchars($product['subcategory_name']) ?></p>
                        <p class="price"><?= htmlspecialchars($product['price']) ?> ₽</p>
                        <button type="button" onclick="openProductModal(<?= htmlspecialchars($product['id']) ?>)">
                            Добавить в корзину
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>