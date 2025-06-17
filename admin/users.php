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
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Дата рождения</th>
                    <th>Роль</th>
                    <th colspan="2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while($u=$result->fetch_assoc()): ?>
                    <tr>
                        <td><?=htmlspecialchars($u['id']) ?></td>
                        <td><?=htmlspecialchars($u['name'])?></td>
                        <td><?=htmlspecialchars($u['email'])?></td>
                        <td><?=htmlspecialchars($u['phone']   ?? '')?></td>
                        <td><?=htmlspecialchars($u['birthdate']??'')?></td>
                        <td><?=htmlspecialchars($u['role'])?></td>
                        <td><a href="?section=users&edit_id=<?= htmlspecialchars($u['id']) ?>">Редакт.</a></td>
                        <td>
                            <form action="/delete/user.php" method="POST" style="display:inline">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                                <button type="submit" onclick="return confirm('Удалить #<?= htmlspecialchars($u['id']) ?>?')">
                                Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="form">
        <?php if ($edit): ?>
            <?php require __DIR__ . '/../admin/edit/user.php'; ?>
        <?php else: ?>
            <?php require __DIR__ . '/../admin/add/user.php'; ?>
        <?php endif; ?>
    </div>
</section>