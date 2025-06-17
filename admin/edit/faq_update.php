<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

$id             = (int)($_POST['id'] ?? 0);
$section_title  = trim($_POST['section_title'] ?? '');
$question       = trim($_POST['question'] ?? '');
$answer         = trim($_POST['answer'] ?? '');

if ($id && $section_title && $question && $answer) {
    $stmt = $conn->prepare("UPDATE faq SET section_title = ?, question = ?, answer = ? WHERE id = ?");
    $stmt->bind_param("sssi", $section_title, $question, $answer, $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Запись успешно обновлена.";
    } else {
        $_SESSION['error'] = "Ошибка при обновлении записи.";
    }
} else {
    $_SESSION['error'] = "Заполните все поля.";
}

header("Location: /admin/index.php?section=faq");
exit;