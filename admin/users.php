<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/admin_function.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

// Обработка добавления пользователя
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['edit_id'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $birthdate= trim($_POST['birthdate']);
    $role     = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $password, $role);

    if ($stmt->execute()) {
        $success = "Пользователь добавлен!";
    } else {
        $error = "Ошибка при добавлении пользователя.";
    }
}

$edit = null;
if (!empty($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    $q = $conn->prepare(
      "SELECT id,name,email,phone,birthdate,role FROM users WHERE id=?"
    );
    $q->bind_param('i', $eid);
    $q->execute();
    $edit = $q->get_result()->fetch_assoc();
}

// Получаем список пользователей
$result = $conn->query("SELECT id, name, email, phone, birthdate, role FROM users ORDER BY id DESC");
?>
    
<h2>Пользователи</h2>

<section>
    <div>
        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Дата рождения</th>
                <th>Роль</th>
                <th colspan="2">Действия</th>
            </tr>
            <?php while($u=$result->fetch_assoc()): ?>
                <tr>
                    <td><?=$u['id']?></td>
                    <td><?=htmlspecialchars($u['name'])?></td>
                    <td><?=htmlspecialchars($u['email'])?></td>
                    <td><?=htmlspecialchars($u['phone']   ?? '')?></td>
                    <td><?=htmlspecialchars($u['birthdate']??'')?></td>
                    <td><?=htmlspecialchars($u['role'])?></td>
                    <td><a href="?section=users&edit_id=<?=$u['id']?>">Редакт.</a></td>
                    <td>
                    <a href="/admin/delete/user.php?id=<?=$u['id']?>"
                        onclick="return confirm('Удалить?')">Удалить</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    
    <div>
        <?php require_once __DIR__ . '/../admin/add/user.php'; ?>
    </div>

    <?php if ($edit): ?>
        <div class="form">
            <h3>Редактировать запись #<?= $edit['id'] ?></h3>
            <?php require_once __DIR__ . '/../admin/edit/user.php'; ?>
        </div>
    <?php endif; ?>
</section>