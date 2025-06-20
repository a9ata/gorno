<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$orderId = $_GET['id'] ?? null;
$userId = $_SESSION['id_user'] ?? null;

if (!$orderId || !$userId) {
    echo "<p>Ошибка доступа. Вернитесь назад.</p>";
    exit;
}

// Проверяем, что заказ принадлежит этому пользователю
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_assoc();

if (!$order) {
    echo "<p>Заказ не найден или вам не принадлежит.</p>";
    exit;
}

// Получаем список товаров из заказа
$stmt = $conn->prepare("
    SELECT oi.product_id, p.name, oi.color, s.name AS size, oi.quantity, oi.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN sizes s ON oi.size_id = s.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$res = $stmt->get_result();
$items = $res->fetch_all(MYSQLI_ASSOC);

// Получаем статус заказа
$stmt = $conn->prepare("
    SELECT s.name AS status 
    FROM order_statuses s 
    JOIN orders o ON o.status_id = s.id 
    WHERE o.id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$res = $stmt->get_result();
$status = $res->fetch_assoc()['status'] ?? 'Неизвестен';
?>

<section class="order-details">
    <h2>Детали заказа №<?= $orderId ?></h2>
    <div>
        <a href="/orders">Назад к заказам</a>
        <p><strong>Дата:</strong> <?= date('d.m.Y', strtotime($order['created_at'])) ?></p>
        <p><strong>Статус:</strong> <?= htmlspecialchars($status) ?></p>
        <p><strong>Общая сумма:</strong> <?= number_format($order['total_amount'], 0, '.', ' ') ?> ₽</p>
        <p><strong>Адрес доставки:</strong>
            <?= $order['delivery_address'] ? htmlspecialchars($order['delivery_address']) : 'Самовывоз' ?>
        </p>
    </div>
    
    <h3>Состав заказа:</h3>
    <table>
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цвет</th>
                <th>Размер</th>
                <th>Кол-во</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['color']) ?></td>
                    <td><?= htmlspecialchars($item['size']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 0, '.', ' ') ?> ₽</td>
                    <td><?= number_format($item['price'] * $item['quantity'], 0, '.', ' ') ?> ₽</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>