<?php
    session_start();
    $page = isset($_GET['page']) ? $_GET['page'] : null;

    include_once __DIR__ .'/templates/layout.php';
?>