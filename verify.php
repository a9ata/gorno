<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/modules/User.php';

debug_log("\n==== Подтверждение email ====");

// Проверяем наличие токена
if (!isset($_GET['token'])) {
    debug_log("Токен не передан в запросе.");
    $_SESSION['error'] = 'Токен не передан.';
    header("Location: /index.php");
    exit;
}

$token = $_GET['token'];
debug_log("Получен токен: $token");

// Проверяем наличие токена в БД
$stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    debug_log("Пользователь найден по токену. ID: {$user['id']}");

    // Обновляем флаг is_verified
    $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
    $update->bind_param("i", $user['id']);
    $update->execute();

    if ($update->affected_rows > 0) {
        debug_log("Статус подтверждения обновлён успешно.");
    } else {
        debug_log("Обновление прошло, но ничего не изменено.");
    }

    $_SESSION['success'] = "Аккаунт успешно подтверждён. Теперь вы можете войти.";
} else {
    debug_log("Токен не найден в базе данных.");
    $_SESSION['error'] = "Неверный или устаревший токен подтверждения.";
}

debug_log("Редирект на главную страницу.");
header("Location: /index.php");
exit;
