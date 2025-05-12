<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';
if (!isAdmin()) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM faq WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Запись #{$id} удалена.";
    } else {
        $_SESSION['error'] = "Ошибка удаления: " . $stmt->error;
    }
}

header('Location: /admin/index.php?section=faq&success=1');
exit;