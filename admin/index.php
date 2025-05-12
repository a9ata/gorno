<?php
session_start();
include_once __DIR__ . '/../config/config.php';


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
    <p><a href="/index.php">На публичный сайт</a></p>

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
