<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId     = $_SESSION['id_user'] ?? null;
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'] ?? null;
    $description = $_POST['description'];
    $date = date('Y-m-d');
    $time = date('H:i:s');

    if (!$userId || !$name || !$email || !$description) {
        die("Пожалуйста, заполните все обязательные поля.");
    }

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, type, date, time, description) VALUES (?, 'индивидуальный заказ', ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $date, $time, $description);


    if ($stmt->execute()) {
        header("Location: /pages/services.php?success=1");
        exit;
    } else {
        echo "Ошибка при отправке индивидуального заказа.";
    }
}