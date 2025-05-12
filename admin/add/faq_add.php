<?php
// /admin/add/faq_add.php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';

if (!isAdmin()) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = trim($_POST['section_title'] ?? '');
    $question = trim($_POST['question'] ?? '');
    $answer   = trim($_POST['answer'] ?? '');

    if (!$section || !$question || !$answer) {
        $_SESSION['error'] = 'Пожалуйста, заполните все поля.';
        header('Location: faq.php');
        exit;
    }

    $sql = "INSERT INTO faq (section_title, question, answer) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $section, $question, $answer);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Новая запись добавлена.';
    } else {
        $_SESSION['error'] = 'Ошибка при добавлении: ' . $stmt->error;
    }
}

header('Location: /admin/index.php?section=faq&success=1');
exit;