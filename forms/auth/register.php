<?php
session_start();
require_once '../../modules/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $phone     = preg_replace('/\D+/', '', $_POST['phone']);
    $password  = trim($_POST['password']);
    $birthdate = str_replace('.', '-', trim($_POST['birthdate']));

    if (!$name || !$email || !$phone || !$password || !$birthdate) {
        $_SESSION['error'] = "Пожалуйста, заполните все поля.";
        header("Location: /index.php");
        exit;
    }

    $user = new User();
    $registered = $user->register($name, $email, $phone, $password, $birthdate);

    if ($registered) {
        // После регистрации сразу логиним
        $user->login($email, $password);
        header("Location: /index.php?page=profile");
    } else {
        $_SESSION['error'] = "Пользователь с таким email уже существует.";
        header("Location: /index.php");
    }
    exit;
}
