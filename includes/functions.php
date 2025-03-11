<?php
// Подключаем конфигурацию базы данных
include_once 'config/db.php';

function getCategories() {
    global $conn; // Используем подключение из config/db.php

    // SQL-запрос для получения категорий и подкатегорий
    $sql = "SELECT c.name AS category_name, sc.name AS subcategory_name 
            FROM categories c
            LEFT JOIN subcategories sc ON c.id = sc.category_id
            ORDER BY c.name, sc.name";
    $result = $conn->query($sql);

    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[$row['category_name']][] = $row['subcategory_name'];
        }
    }
    return $categories;
}
?>
