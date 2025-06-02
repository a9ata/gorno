<?php
// Подключаем конфигурацию базы данных
include_once __DIR__ . '/../config/db.php';

function debug_log($message) {
    if (is_array($message)) {
        $message = print_r($message, true); // форматирует массив в читаемую строку
    }
    error_log($message . PHP_EOL, 3,  __DIR__ . '/../logs/auth.log');
}


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


function getFilteredProducts($conn, $gender, $filters) {
    $sql = "SELECT p.*, s.name AS subcategory_name, ANY_VALUE(pi.image_url) AS image_url
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            LEFT JOIN product_attributes pa ON pa.product_id = p.id
            LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
            WHERE 1";

    $params = [];

    if ($gender) {
        $sql .= " AND p.gender = ?";
        $params[] = $gender;
    }

    if (!empty($filters['subcategory_ids'])) {
        $placeholders = implode(',', array_fill(0, count($filters['subcategory_ids']), '?'));
        $sql .= " AND p.subcategory_id IN ($placeholders)";
        $params = array_merge($params, $filters['subcategory_ids']);
    }

    if (!empty($filters['price_min'])) {
        $sql .= " AND p.price >= ?";
        $params[] = $filters['price_min'];
    }
    
    if (!empty($filters['price_max'])) {
        $sql .= " AND p.price <= ?";
        $params[] = $filters['price_max'];
    }

    if (!empty($filters['size_ids'])) {
        $placeholders = implode(',', array_fill(0, count($filters['size_ids']), '?'));
        $sql .= " AND pa.size_id IN ($placeholders)";
        $params = array_merge($params, $filters['size_ids']);
    }


    if (!empty($filters['colors'])) {
        $placeholders = implode(',', array_fill(0, count($filters['colors']), '?'));
        $sql .= " AND pa.color IN ($placeholders)";
        $params = array_merge($params, $filters['colors']);
    }

    $sql .= " GROUP BY p.id"; // чтобы избежать дубликатов при нескольких атрибутах
    debug_log("SQL: $sql");
    $stmt = $conn->prepare($sql);

    if ($params) {
        $types = '';
        foreach ($params as $param) {
            $types .= is_numeric($param) ? 'd' : 's';
        }

        debug_log(['params' => $params]);
        $stmt->bind_param($types, ...$params);
    }
    

    $stmt->execute();
    
    $result = $stmt->get_result();
    debug_log(['result' => $result]);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getSizesByType($conn, $type) {
    $stmt = $conn->prepare("SELECT id, name FROM sizes WHERE type = ? ORDER BY name");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAvailableColors($conn) {
    $sql = "SELECT DISTINCT color FROM product_attributes WHERE color IS NOT NULL AND color != '' ORDER BY color";
    $res = $conn->query($sql);
    $colors = [];
    while ($row = $res->fetch_assoc()) {
        $colors[] = $row['color'];
    }
    return $colors;
}


function getProductAttributes($conn, $product_id) {
    $sql = "SELECT pa.color, pa.size_id, s.name AS size_name
            FROM product_attributes pa
            JOIN sizes s ON pa.size_id = s.id
            WHERE pa.product_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getAllCategories($conn) {
    $result = [];
    $sql = "SELECT c.id AS cat_id, c.name AS cat_name, s.id AS sub_id, s.name AS sub_name
        FROM categories c
        JOIN subcategories s ON s.category_id = c.id
        ORDER BY c.name, s.name";


    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $catId = $row['cat_id'];
        if (!isset($result[$catId])) {
            $result[$catId] = [
                'name' => $row['cat_name'],
                'subcategories' => [],
            ];
        }
        $result[$catId]['subcategories'][] = [
            'id' => $row['sub_id'],
            'name' => $row['sub_name'],
        ];
    }
    return $result;
}
?>