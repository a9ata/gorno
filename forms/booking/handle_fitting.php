<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId     = $_SESSION['id_user'] ?? null;
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'] ?? null;
    $address    = $_POST['address'];
    $time       = $_POST['time'];
    $date       = $_POST['date'];
    $description = $_POST['description'];

    if (!$userId || !$name || !$email || !$address || !$time || !$date || !$description) {
        die("Пожалуйста, заполните все обязательные поля.");
    }

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, type, address, time, date, description) 
                            VALUES (?, 'примерка на дому', ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $address, $time, $date, $description);

    if ($stmt->execute()) {
        header("Location: /pages/services.php?success=1");
        exit;
    } else {
        echo "Ошибка при отправке заявки на примерку.";
    }
}