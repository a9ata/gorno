<?php
include_once 'config.php';

// Создаем соединение с базой данных
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Проверяем подключение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

?>

