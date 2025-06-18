<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['id_user'] ?? null;
$selected = $_SESSION['selected_items'] ?? [];

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$cardNumber = $_POST['card_number'] ?? '';
$cvc = $_POST['cvc'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$total = $_POST['total'] ?? 0;
$paymentMethodId = $_POST['payment_method_id'] ?? null;
$deliveryMethodId = $_POST['delivery_method_id'] ?? null;

if (!$userId || !$name || !$email || !$total || !$paymentMethodId || !$deliveryMethodId) {
    header("Location: /index.php?page=payment&error=1");
    exit;
}

// Получаем стоимость доставки из delivery_methods
$stmt = $conn->prepare("SELECT price FROM delivery_methods WHERE id = ?");
$stmt->bind_param("i", $deliveryMethodId);
$stmt->execute();
$res = $stmt->get_result();
$deliveryRow = $res->fetch_assoc();
$deliveryPrice = $deliveryRow['price'] ?? 0.00;

// Общая сумма: товары + доставка
$totalWithDelivery = $total + $deliveryPrice;

// Создание заказа
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method_id, delivery_method_id, status_id) VALUES (?, ?, ?, ?, 1)");
$stmt->bind_param("idii", $userId, $totalWithDelivery, $paymentMethodId, $deliveryMethodId);
$stmt->execute();
$orderId = $conn->insert_id;

// Получаем товары из корзины
if (!empty($selected)) {
    $placeholders = implode(',', array_fill(0, count($selected), '?'));
    $types = str_repeat('i', count($selected) + 1);
    $params = array_merge([$userId], $selected);

    $sql = "SELECT c.*, p.price FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ? AND c.id IN ($placeholders)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    $cartItems = $res->fetch_all(MYSQLI_ASSOC);

    // Записываем в order_items
    foreach ($cartItems as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, color, size_id, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iisiid",
            $orderId,
            $item['product_id'],
            $item['color'],
            $item['size_id'],
            $item['quantity'],
            $item['price']
        );
        $stmt->execute();
    }

    // Удаляем товары из корзины
    $placeholders = implode(',', array_fill(0, count($selected), '?'));
    $types = str_repeat('i', count($selected));
    $stmt = $conn->prepare("DELETE FROM cart WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$selected);
    $stmt->execute();
}

// Сохраняем данные карты (заглушка)
$stmt = $conn->prepare("INSERT INTO card_inputs (user_id, card_number, cvc, expiry) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $userId, $cardNumber, $cvc, $expiry);
$stmt->execute();

// Обновляем loyalty_cards
function calculateDiscount($total) {
    if ($total >= 150000) return 15;
    if ($total >= 100000) return 10;
    if ($total >= 50000)  return 5;
    return 0;
}

$stmt = $conn->prepare("SELECT SUM(total_amount) FROM orders WHERE user_id = ? AND status_id = 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_row();
$totalSpent = $row[0] ?? 0.00;

$discount = calculateDiscount($totalSpent);

// Обновляем или создаём loyalty карту
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

header("Location: ?page=profile&success=payment");
exit;