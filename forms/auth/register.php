<?php
session_start();
require_once '../../config/db.php'; // подключение к базе

// Проверка на POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name      = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $phone = preg_replace('/\D+/', '', $_POST['phone']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $birthdate = trim($_POST['birthdate']);
    $birthdate = str_replace('.', '-', $birthdate);

    // Проверка на существующего пользователя
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "Пользователь с таким email уже зарегистрирован.";
        header("Location: /index.php");
        exit;
    }
    
    // Добавляем пользователя
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, birthdate, role, created_at)
        VALUES (?, ?, ?, ?, ?, 'user', NOW())");
    $stmt->execute([$name, $email, $phone, $password, $birthdate]);


    $_SESSION['user'] = $email;
    header("Location: /index.php");
    exit;
}
?>