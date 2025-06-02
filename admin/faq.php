<?php
// admin/faq.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/admin_function.php';
if (!isAdmin()) {
    header('Location: /index.php');
    exit;
}

// 1) Получаем все записи
$sql = "SELECT id, section_title, question, answer
        FROM faq
        ORDER BY section_title, id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$faqs = $stmt->get_result();

// 2) Узнаём, редактируем ли конкретную запись
$editFaq = null;
if (!empty($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    $q = $conn->prepare("SELECT id, section_title, question, answer FROM faq WHERE id = ?");
    $q->bind_param('i', $eid);
    $q->execute();
    $editFaq = $q->get_result()->fetch_assoc();
}

// (по желанию) флаш-сообщения
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error']   ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<h2>FAQ</h2>
<section>
    <?php if ($success): ?>
        <p class="notice notice-success"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="notice notice-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <div>
         <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Раздел</th>
                <th>Вопрос</th>
                <th>Ответ</th>
                <th colspan="2">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $faqs->fetch_assoc()): ?>
                <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['section_title']) ?></td>
                <td><?= htmlspecialchars(mb_strimwidth($row['question'],0,50,'…')) ?></td>
                <td><?= htmlspecialchars(mb_strimwidth($row['answer'],0,50,'…')) ?></td>
                <td>
                    <a href="?section=faq&edit_id=<?= $row['id'] ?>">Редакт.</a>
                </td>
                <td>
                    <form action="delete/faq.php" method="POST"
                        onsubmit="return confirm('Удалить запись #<?= $row['id'] ?>?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit">Удалить</button>
                    </form>
                </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="form">
        <?php if ($editFaq): ?>
            <h3>Редактировать запись #<?= $editFaq['id'] ?></h3>
            <form action="/admin/edit/faq.php" method="POST">
                <input type="hidden" name="id" value="<?= $editFaq['id'] ?>">
                <label>Раздел:
                    <input type="text"
                        name="section_title"
                        value="<?= htmlspecialchars($editFaq['section_title']) ?>"
                        required>
                </label>
                <label>Вопрос:
                    <textarea name="question" rows="2" required><?= htmlspecialchars($editFaq['question']) ?></textarea>
                </label>
                <label>Ответ:
                    <textarea name="answer" rows="4" required><?= htmlspecialchars($editFaq['answer']) ?></textarea>
                </label>
                <button type="submit">Обновить</button>
                <a href="?section=faq" class="btn-secondary">Отмена</a>
            </form>
        <?php else: ?>
            <?php require __DIR__ . '/../admin/add/faq.php'; ?>
        <?php endif; ?>
    </div>
</section>