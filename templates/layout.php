<?php
include_once 'config/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_URL ?>styles.css">
    <title>Горно</title>
</head>
<body>
    <?php
        // Подключаем шапку
        include_once 'templates/header.php';
    ?>

    <main>
        <?php
            if (isset($page)) {
                include_once "pages/{$page}.php";
            } else {
                include_once 'pages/home.php'; // главная страница
            }
        ?>
    </main>

    <?php
        // Подключаем подвал
        include_once 'templates/footer.php';
    ?>
    <script src="https://unpkg.com/inputmask/dist/inputmask.min.js"></script>
    <script src="<?= JS_URL ?>modal-auth.js"></script>
    <script src="<?= JS_URL ?>auth-validation.js"></script>
    <script src="<?= JS_URL ?>profile-edit.js"></script>
    <script src="<?= JS_URL ?>faq.js"></script>
</body>
</html>


