<?php
session_start();
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../includes/functions.php'; // Подключаем файл с функциями
    $categories = getCategories(); // Получаем данные из базы

include __DIR__ . '/header.php'; 
include __DIR__ . '/modal-product.php'; 
include __DIR__ . '/../forms/loyaltyCard.php'; 
?>

    <main>
        <?php
            if (isset($page)) {
                include_once __DIR__ . "/../pages/{$page}.php";
            } else {
                include_once __DIR__ . '/../pages/home.php'; // главная страница
            }
        ?>
    </main>

<?php
    // Подключаем подвал
    include_once __DIR__ . '/footer.php';
?>