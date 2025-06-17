<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';

if (!isAdmin()) {
    header('Location: /index.php');
    exit;
}
?>

<h3>Добавить запись в FAQ</h3>

<form action="/admin/add/faq_add.php" method="POST">
    <label>
        Раздел (section_title):
        <input type="text" name="section_title" required>
    </label>

    <label>
        Вопрос:
        <textarea name="question" rows="3" required></textarea>
    </label>

    <label>
        Ответ:
        <textarea name="answer" rows="5" required></textarea>
    </label>

    <button type="submit">Сохранить</button>
</form>