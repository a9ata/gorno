<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['id_user'] ?? null;
$selected = $_SESSION['selected_items'] ?? [];
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$total = $_POST['total'] ?? 0;

if (!$userId || !$name || !$email || !$total) {
    header("Location: /index.php?page=payment&error=1");
    exit;
}

// создаём заказ
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'paid')");
$stmt->bind_param("id", $userId, $total);
$stmt->execute();

// очищаем купленные позиции из корзины (если нужно — только оплаченные)
$cartItems = $_POST['selected_items'] ?? [];
if (!empty($cartItems)) {
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));
    $types = str_repeat('i', count($cartItems));
    $stmt = $conn->prepare("DELETE FROM cart WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$cartItems);
    $stmt->execute();
}

$placeholders = implode(',', array_fill(0, count($selected), '?'));
$types = str_repeat('i', count($selected));
$stmt = $conn->prepare("DELETE FROM cart WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$selected);
$stmt->execute();


// Обновляем loyalty_card для пользователя
function calculateDiscount($total) {
    if ($total >= 150000) return 15;
    if ($total >= 100000) return 10;
    if ($total >= 50000)  return 5;
    return 0;
}

// получаем сумму всех оплаченных заказов
$stmt = $conn->prepare("SELECT SUM(total_amount) FROM orders WHERE user_id = ? AND status = 'paid'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_row();
$totalSpent = $row[0] ?? 0.00;

$discount = calculateDiscount($totalSpent);

// проверяем, есть ли уже карта
$stmt = $conn->prepare("SELECT id FROM loyalty_cards WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE loyalty_cards SET total_spent = ?, discount_percentage = ? WHERE user_id = ?");
    $stmt->bind_param("dii", $totalSpent, $discount, $userId);
} else {
    $stmt = $conn->prepare("INSERT INTO loyalty_cards (user_id, total_spent, discount_percentage) VALUES (?, ?, ?)");
    $stmt->bind_param("idd", $userId, $totalSpent, $discount);
}
$stmt->execute();
unset($_SESSION['selected_items']);


// редирект на профиль или успех
header("Location: /index.php?page=profile&success=payment");
exit;
