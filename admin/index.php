<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../includes/functions.php'; // Подключаем файл с функциями
    $categories = getCategories(); // Получаем данные из базы
$pageTitle = 'Админ-панель — Горно';

require_once __DIR__ . '/../includes/admin_function.php';
if (!isAdmin()) {
    header('Location: /index.php');
    exit;
}

include_once __DIR__ . '/../templates/header.php'; 

include __DIR__ . '/menu.php';
?>
<main class="admin-panel">
    <h1>Панель администратора</h1>
    <a href="/index.php" class="main-gate">На публичный сайт</a>

    <?php
    
        $section = $_GET['section'] ?? null;
        if ($section) {
            $file = __DIR__ . '/' . basename($section) . '.php';
            if (file_exists($file)) {
                include $file;
            } else {
                echo '<p><strong>Раздел не найден.</strong></p>';
            }
        } else {
            echo '<p>Пожалуйста, выберите раздел в меню сверху.</p>';
        }
    ?>
</main>


<?php include_once __DIR__ . '/../templates/footer.php'; ?>