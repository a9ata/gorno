<?php
// admin/add/booking_add.php
session_start();
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/index.php?section=bookings');
    exit;
}

// Собираем данные
$type        = $_POST['type'];
$user_id     = (int)$_POST['user_id'];
$stylist_id  = $_POST['stylist_id'] !== '' ? (int)$_POST['stylist_id'] : null;
$date        = $_POST['date'];
$time        = $_POST['time'];
$address     = $_POST['address'] ?: null;
$description = $_POST['description'] ?: null;

// Вставляем
$stmt = $conn->prepare("
    INSERT INTO bookings
      (`type`, user_id, stylist_id, `date`, `time`, address, description)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param('siissss',
    $type,
    $user_id,
    $stylist_id,
    $date,
    $time,
    $address,
    $description
);
$stmt->execute();

// Редирект обратно в таб нужного типа
header('Location: /admin/index.php?section=bookings&type=' . urlencode($type));
exit;