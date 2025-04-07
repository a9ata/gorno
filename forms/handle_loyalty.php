<?php
session_start();
require_once '../config/db.php';

$userId = $_SESSION['id_user'] ?? null;

if (!$userId) {
    die("Вы должны быть авторизованы, чтобы оформить карту лояльности.");
}

// Проверяем, есть ли уже карта
$check = $conn->prepare("SELECT id FROM loyalty_cards WHERE user_id = ?");
$check->bind_param("i", $userId);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    header("Location: /pages/services.php?loyalty=exists");
    exit;
}

// Вставляем новую карту
$stmt = $conn->prepare("INSERT INTO loyalty_cards (user_id, total_spent, discount_percentage) VALUES (?, 0.00, 0.00)");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    header("Location: /pages/services.php?loyalty=success");
} else {
    header("Location: /pages/services.php?loyalty=error");
}
exit;