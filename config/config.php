<?php
    $env = parse_ini_file(__DIR__ . '/../.env', INI_SCANNER_RAW) ?: [];

    define('YANDEX_CAPTCHA_CLIENT', $env['YANDEX_CAPTCHA_CLIENT'] ?? '');
    define('YANDEX_CAPTCHA_SECRET', $env['YANDEX_CAPTCHA_SECRET'] ?? '');

    // Базовый URL сайта
    const BASE_URL = 'http://gorno/';
    const ASSETS_URL = '/../assets/'; // Путь к папке с ассетами
    const IMAGES_URL = ASSETS_URL . 'images/'; // Путь к изображениям
    const ICONS_URL = IMAGES_URL . 'icon/'; // Путь к иконкам
    const CSS_URL = ASSETS_URL . 'css/'; // Путь к CSS
    const JS_URL = ASSETS_URL . 'js/'; // Путь к JS

    // Настройки базы данных
    const DB_HOST = 'localhost'; // Хост базы данных
    const DB_USER = 'root'; // Пользователь базы данных
    const DB_PASSWORD = ''; // Пароль базы данных
    const DB_NAME = 'gorno'; // Имя базы данных
?>

