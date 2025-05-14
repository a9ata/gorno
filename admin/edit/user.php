<?php
session_start();
require_once '../../config/db.php';
require_once '../../includes/admin_function.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "Некорректный ID";
    exit;
}

// Получаем данные пользователя
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Пользователь не найден.";
    exit;
}

// Обработка обновления
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $email, $phone, $hashed, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $role, $id);
    }

    if ($stmt->execute()) {
        header("Location: /admin/users.php?success=1");
        exit;
    } else {
        $error = "Ошибка при обновлении данных.";
    }
}
?>

<h2>Редактирование пользователя</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Имя: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></label><br>
    <label>Телефон: <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"></label><br>
    <label>Пароль (оставьте пустым, если не менять): <input type="password" name="password"></label><br>
    <label>Роль:
        <select name="role">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Пользователь</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Админ</option>
            <option value="stylist" <?= $user['role'] === 'stylist' ? 'selected' : '' ?>>Стилист</option>
        </select>
    </label><br>
    <button type="submit">Сохранить</button>
    <a href="/admin/index.php?section=users">Назад</a>
</form>