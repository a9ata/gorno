<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['email'];
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Неверный email или пароль.";
        header("Location: index.php");
        exit;
    }
}
?>