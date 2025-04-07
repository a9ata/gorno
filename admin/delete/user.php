<?php
session_start();
require_once '../../config/db.php';
require_once '../../includes/admin_function.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

// Получаем ID из запроса
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo "Некорректный ID.";
    exit;
}

// Удаляем пользователя
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: /admin/users.php?deleted=1");
    exit;
} else {
    echo "Ошибка при удалении пользователя.";
    exit;
}