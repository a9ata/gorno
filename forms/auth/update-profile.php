<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: /index.php");
    exit;
}

// Получаем текущего пользователя
$email = $_SESSION['email'];

// Получаем данные из формы
$name         = trim($_POST['name']);
$phone        = preg_replace('/\D+/', '', $_POST['phone']);
$birthdate    = str_replace('.', '-', trim($_POST['birthdate']));
$new_password = trim($_POST['new_password']);

// Подготовим SQL
if (!empty($new_password)) {
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, birthdate = ?, password = ? WHERE email = ?");
    $stmt->execute([$name, $phone, $birthdate, $hashedPassword, $email]);
} else {
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, birthdate = ? WHERE email = ?");
    $stmt->execute([$name, $phone, $birthdate, $email]);
}

// Обновим сессию
$_SESSION['name']      = $name;
$_SESSION['phone']     = $phone;
$_SESSION['birthdate'] = $birthdate;

// Перенаправим назад в профиль
header("Location: /index.php?page=profile");
exit;