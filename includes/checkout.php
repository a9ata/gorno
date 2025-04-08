<?php
session_start();
require_once '../config/db.php';

// Получаем JSON-данные из JS
$data = json_decode(file_get_contents("php://input"), true);
$selected = $data['selected_items'] ?? [];
$userId = $_SESSION['id_user'] ?? null;

if (!$userId || empty($selected)) {
    echo json_encode(['success' => false, 'message' => 'Вы не выбрали товары для оплаты.']);
    exit;
}

// Сохраняем выбранные товары в сессии
$_SESSION['selected_items'] = $selected;

// Всё ок — отправляем сигнал JS на редирект
echo json_encode(['success' => true]);
exit;
