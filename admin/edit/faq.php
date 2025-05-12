<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';
if (!isAdmin()) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int)$_POST['id'];
    $section = trim($_POST['section_title']);
    $question= trim($_POST['question']);
    $answer  = trim($_POST['answer']);

    if ($id && $section && $question && $answer) {
        $sql = "UPDATE faq
                SET section_title = ?, question = ?, answer = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $section, $question, $answer, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Запись #{$id} обновлена.";
        } else {
            $_SESSION['error'] = "Ошибка обновления: " . $stmt->error;
        }
    } else {
        $_SESSION['error'] = "Все поля обязательны.";
    }
}

header('Location: /admin/index.php?section=faq&success=1');
exit;