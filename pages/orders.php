<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['id_user'] ?? null;
if (!$userId) {
    echo "<p>Вы не авторизованы.</p>";
    exit;
}

$stmt = $conn->prepare("
    SELECT o.id, o.total_amount, o.created_at, s.name AS status
    FROM orders o
    LEFT JOIN order_statuses s ON o.status_id = s.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$orders = $res->fetch_all(MYSQLI_ASSOC);
?>

<section class="my-orders">
    <h2>Мои заказы</h2>

    <?php if (empty($orders)): ?>
        <p>У вас пока нет заказов.</p>
    <?php else: ?>
        <ul class="orders-list">
            <?php foreach ($orders as $order): ?>
                <li class="order-item">
                    <p><strong>Заказ №<?= $order['id'] ?></strong> от <?= date('d.m.Y', strtotime($order['created_at'])) ?></p>
                    <p>Сумма: <?= number_format($order['total_amount'], 0, '.', ' ') ?> ₽</p>
                    <p>Статус: <strong><?= htmlspecialchars($order['status']) ?></strong></p>
                    <a href="/order_details?id=<?= $order['id'] ?>">Посмотреть детали</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>