<?php
session_start();

// Удаляем все сессионные данные
session_unset();

// Уничтожаем сессию
session_destroy();

// Редирект на главную страницу или на страницу входа
header("Location: /index.php");
exit;
?>