<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
$pageTitle = 'Корзина — Горно';

$userId = $_SESSION['id_user'] ?? null;

$sql = "SELECT 
    c.*, 
    p.name, p.description, p.price, 
    pi.image_url,
    s.name AS size_name
FROM cart c
JOIN products p ON c.product_id = p.id
LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
JOIN sizes s ON c.size_id = s.id
WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$totalSum = 0;
?>

<section class="cart">
    <h2>Корзина</h2>

    <table>
        <thead>
            <tr>
                <th>Выбор</th>
                <th>Товар</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Размер</th>
                <th>Сумма</th>
                <th>Удалить</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): 
                $sum = $item['price'] * $item['quantity'];
                $totalSum += $sum;
            ?>
                <tr>
                    <td><input type="checkbox" class="select-item" name="selected_items[]" value="<?= $item['id'] ?>"></td>
                    <td><img src="<?= $item['image_url'] ?? IMAGES_URL . 'products/default.png' ?>" width="248px" /></td>
                    <td><?= htmlspecialchars($item['name']) ?><br><small><?= htmlspecialchars($item['description']) ?></small></td>
                    <td><?= $item['price'] ?> ₽</td>
                    <td>
                        <form method="post" action="/includes/update_quantity.php">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button name="action" value="decrease">-</button>
                            <?= $item['quantity'] ?>
                            <button name="action" value="increase">+</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($item['size_name']) ?></td>
                    <td><?= number_format($sum, 0, '.', ' ') ?> ₽</td>
                    <td>
                        <button class="trash-btn" data-id="<?= $item['id'] ?>">
                            <img src="<?= ICONS_URL ?>trash.svg" alt="Удалить">
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-sum">
        <p><strong>Общая стоимость:</strong> <?= number_format($totalSum, 0, '.', ' ') ?> ₽</p>

        <form id="paymentForm" method="post" action="/includes/checkout.php">
            <input type="hidden" name="selected_items" id="selectedItemsInput">
            <p id="selectionError" style="color: red; display: none;"></p>
            <button type="submit" class="pay-btn">Оплатить</button>
        </form>
    </div>
</section>