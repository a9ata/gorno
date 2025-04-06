<?php
session_start();
require_once '../../modules/User.php';

// Создаем функцию для логирования
function debug_log($message) {
    error_log($message . PHP_EOL, 3, '../../logs/auth.log');

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!$email || !$password) {
        $_SESSION['error'] = "Введите email и пароль.";
        header("Location: /index.php");
        exit;
    }
    debug_log("{$email} {$password}");
    $user = new User();
    $authSuccess = $user->login($email, $password);

    if ($authSuccess) {
        header("Location: /index.php?page=profile");
    } else {
        $_SESSION['error'] = "Неверный email или пароль.";
        header("Location: /index.php");
    }
    exit;
}
