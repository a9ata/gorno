<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId    = $_SESSION['id_user'] ?? null;
    $name      = $_POST['name'];
    $email     = $_POST['email'];
    $time      = $_POST['time'];
    $date      = $_POST['date'];
    $stylistId = $_POST['stylist_id'];

    if (!$userId || !$name || !$email || !$time || !$date || !$stylistId) {
        die("Заполните все поля.");
    }

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, stylist_id, type, date, time) VALUES (?, ?, 'консультация', ?, ?)");
    $stmt->bind_param("iiss", $userId, $stylistId, $date, $time);

    if ($stmt->execute()) {
        header("Location: /pages/services.php?success=1");
        exit;
    } else {
        echo "Ошибка при бронировании.";
    }
}