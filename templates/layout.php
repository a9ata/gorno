<?php
session_start();
include_once __DIR__ . '/../config/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_URL ?>styles.css">
    <title>Горно</title>
</head>
<body>
    <?php
        // Подключаем шапку
        include_once __DIR__ . '/header.php';
    ?>
    <?php include_once 'templates/modal-product.php'; ?>
    
    <?php
        require_once __DIR__ . '/../includes/admin_function.php';
        if (isAdmin()) {
            include 'templates/admin-menu.php';
        }
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

    <script src="https://unpkg.com/inputmask/dist/inputmask.min.js"></script>
    <script src="<?= JS_URL ?>modal-auth.js"></script>
    <script src="<?= JS_URL ?>auth-validation.js"></script>
    <script src="<?= JS_URL ?>profile-edit.js"></script>
    <script src="<?= JS_URL ?>faq.js"></script>
    <script src="<?= JS_URL ?>modal-loyalty.js"></script>
    <script src="<?= JS_URL ?>favorite.js"></script>
    <script src="<?= JS_URL ?>modal-product.js"></script>
    <script src="<?= JS_URL ?>check-cart.js"></script>
    <script src="<?= JS_URL ?>quantity.js"></script>
    <script src="<?= JS_URL ?>cart-actions.js"></script>
</body>
</html>
