<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
$pageTitle = 'Оплата — Горно';

$userId = $_SESSION['id_user'] ?? null;
$selected = $_SESSION['selected_items'] ?? [];

if (empty($selected)) {
    echo "<p style='color: red;'>Вы не выбрали товары для оплаты.</p>";
    exit;
}


$placeholders = implode(',', array_fill(0, count($selected), '?'));
$types = str_repeat('i', count($selected) + 1);
$params = array_merge([$userId], $selected);

$sql = "SELECT c.*, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ? AND c.id IN ($placeholders)";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$cartItems = $res->fetch_all(MYSQLI_ASSOC);

$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>

<section class="payment">
    <h2>Оплата</h2>
    <div class="payment-block">
        <div class="payment-summary">
            <p><strong>Количество товаров:</strong> <?= count($cartItems) ?></p>
            <p><strong>К оплате:</strong> <?= number_format($totalAmount, 0, '.', ' ') ?> ₽</p>
        </div>

        <form action="/includes/process_payment.php" method="POST">
            <input type="text" name="name" placeholder="Имя" required>
            <input type="email" name="email" placeholder="Почта" required>
            <input type="text" name="card_number" placeholder="Номер карты" required>
            <input type="text" name="cvc" placeholder="CVC код" required>
            <input type="text" name="expiry" placeholder="мм/гг" required>

            <input type="hidden" name="total" value="<?= $totalAmount ?>">
            
            <?php foreach ($selected as $id): ?>
                <input type="hidden" name="selected_items[]" value="<?= $id ?>">
            <?php endforeach; ?>

            <button type="submit">Оплатить</button>
        </form>
    </div>
</section>
