<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['birthdate'] = $user['birthdate'];
        header("Location: /");
        exit;
    } else {
        $_SESSION['error'] = "Неверный email или пароль.";
        header("Location: /index");
        exit;
    }
}
?>