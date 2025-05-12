<?php
session_start();
require_once '../../modules/User.php';
include_once __DIR__ . '/../config/config.php';


// Создаем функцию для логирования
function debug_log($message) {
    error_log($message . PHP_EOL, 3, '../../logs/auth.log');

}


debug_log("=== Новый запрос авторизации ===");

// 1) Проверка токена
$captchaToken = trim($_POST['captcha_token'] ?? '');
debug_log("Получен captcha_token: “{$captchaToken}”");
if (!$captchaToken) {
    debug_log(" → Нет токена, редирект на форму.");
    $_SESSION['error'] = 'Пожалуйста, подтвердите, что вы не робот.';
    header("Location: /index.php");
    exit;
}

// 2) Верификация капчи
$serverKey = YANDEX_CAPTCHA_SECRET;
$verifyUrl = 'https://smartcaptcha.yandexcloud.net/validate';
    debug_log("Отправляем верификацию капчи на {$verifyUrl}");
    
$ch = curl_init($verifyUrl);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    CURLOPT_POSTFIELDS     => http_build_query([
        'secret' => $serverKey,
        'token'  => $captchaToken,
    ]),
]);
$response = curl_exec($ch);
if ($response === false) {
    debug_log("cURL error: " . curl_error($ch));
}
curl_close($ch);

debug_log(" → Ответ сервера капчи (raw): {$response}");
$result = json_decode($response, true);
debug_log(" → Parsed JSON: " . print_r($result, true));

// Проверяем именно статус 'ok'
if (empty($result['status']) || $result['status'] !== 'ok') {
    debug_log(" → Капча НЕ пройдена: status={$result['status']}");
    $_SESSION['error'] = 'Не удалось пройти проверку капчи.';
    header("Location: /index.php");
    exit;
}
debug_log(" → Капча ОК");

// 3) логика логина
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    debug_log("Попытка логина: {$email} / {$password}");

    if (!$email || !$password) {
        debug_log(" → Не переданы email или пароль");
        $_SESSION['error'] = "Введите email и пароль.";
        header("Location: /index.php");
        exit;
    }

    $user = new User();
    $authSuccess = $user->login($email, $password);
    debug_log(" → user->login вернул: " . ($authSuccess ? 'true' : 'false'));

    if ($authSuccess) {
        debug_log(" → Успешный логин, редирект в профиль.");
        header("Location: /index.php?page=profile");
    } else {
        debug_log(" → Неверный email или пароль, редирект на форму.");
        $_SESSION['error'] = "Неверный email или пароль.";
        header("Location: /index.php");
    }
    exit;
}