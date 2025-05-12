<?php
session_start();
require_once '../../modules/User.php';
include_once __DIR__ . '/../config/config.php';


// 1) Проверяем, передан ли токен
$captchaToken = trim($_POST['captcha_token'] ?? '');
if (!$captchaToken) {
    $_SESSION['error'] = 'Пожалуйста, подтвердите, что вы не робот.';
    header("Location: /index.php");
    exit;
}

// 2) Отправляем POST-запрос на верификацию
$serverKey = YANDEX_CAPTCHA_SECRET;
$verifyUrl = 'https://smartcaptcha.yandexcloud.net/validate';

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

curl_close($ch);
$result = json_decode($response, true);

// 3) Если капча не пройдена — редиректим обратно с ошибкой
if (empty($result['status'])) {
    $_SESSION['error'] = 'Не удалось пройти проверку капчи.';
    header("Location: /index.php");
    exit;
}

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
