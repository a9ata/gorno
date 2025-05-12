<?php
session_start();
require_once '../config/db.php';
require_once '../includes/admin_function.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

// Обработка добавления пользователя
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                <th>Действия</th>
            </tr>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['phone']) ?></td>
                <td><?= htmlspecialchars($user['birthdate']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                <a href="/admin/edit/user.php?id=<?= $user['id'] ?>">Редактировать</a>
                <a href="/admin/delete/user.php?id=<?= $user['id'] ?>" onclick="return confirm('Удалить пользователя?')">Удалить</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    
    <div>
        <h3>Добавить пользователя</h3>

        <?php if ($success): ?>
            <p style="color: green"><?= $success ?></p>
        <?php elseif ($error): ?>
            <p style="color: red"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Имя: <input type="text" name="name" required></label><br>
            <label>Email: <input type="email" name="email" required></label><br>
            <label>Телефон: <input type="text" name="phone"></label><br>
            <label>Пароль: <input type="password" name="password" required></label><br>
            <label>Роль:
                <select name="role">
                    <option value="user">Пользователь</option>
                    <option value="admin">Админ</option>
                    <option value="stylist">Стилист</option>
                </select>
            </label><br>
            <button type="submit">Добавить</button>
        </form>
    </div>
</section>