<?php
// файл: admin/edit/booking_update.php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';

if (! isAdmin()) {
    header('Location: /');
    exit;
}

// Обязательно убеждаемся, что это POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/index.php?section=bookings');
    exit;
}

// Читаем из POST
$id          = isset($_POST['id'])   ? (int) $_POST['id']         : 0;
$type        = $_POST['type']   ?? 'примерка на дому';
$user_id     = isset($_POST['user_id'])   ? (int) $_POST['user_id']   : 0;
$stylist_id  = ($_POST['stylist_id'] !== '' ? (int) $_POST['stylist_id'] : null);
$date        = $_POST['date']   ?? null;
$time        = $_POST['time']   ?? null;
$address     = $_POST['address']     ?: null;
$description = $_POST['description'] ?: null;

// Валидация минимум: у нас должны быть id, user_id, date, time
if ($id <= 0 || $user_id <= 0 || !$date || !$time) {
    $_SESSION['error'] = "Неправильные данные.";
    header('Location: /admin/edit/booking.php?id=' . $id . '&type=' . urlencode($type));
    exit;
}

// Готовим и выполняем UPDATE
$sql = "
    UPDATE bookings
       SET `type`      = ?,
           user_id     = ?,
           stylist_id  = ?,
           `date`      = ?,
           `time`      = ?,
           address     = ?,
           description = ?
     WHERE id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'siissssi',
    $type,
    $user_id,
    $stylist_id,
    $date,
    $time,
    $address,
    $description,
    $id
);

if ($stmt->execute()) {
    $_SESSION['success'] = "Бронь #{$id} обновлена.";
} else {
    $_SESSION['error'] = "Ошибка при обновлении: " . $stmt->error;
}

// Редиректим обратно в таб с нужным типом
header('Location: /admin/index.php?section=bookings&type=' . urlencode($type));
exit;