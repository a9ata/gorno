<?php
// admin/delete/booking.php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';

if (! isAdmin()) {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int) ($_POST['id']   ?? 0);
    $type =       ($_POST['type'] ?? '');

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $_SESSION['success'] = "Бронь #{$id} удалена.";
    } else {
        $_SESSION['error'] = "Неверный ID для удаления.";
    }

    header('Location: /admin/index.php?section=bookings&type=' . urlencode($type));
    exit;
}

// Защита от прямого GET-запроса
header('Location: /admin/index.php?section=bookings');
exit;