<h3>Добавить пользователя</h3>

<?php if ($success): ?>
    <p style="color: green"><?= htmlspecialchars($success) ?></p>
<?php elseif ($error): ?>
    <p style="color: red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Имя: <input type="text" name="name" required></label>
    <label>Email: <input type="email" name="email" required></label>
    <label>Телефон: <input type="text" name="phone"></label>
    <label>Пароль: <input type="password" name="password" required></label>
    <label>Роль:
        <select name="role">
            <option value="user">Пользователь</option>
            <option value="admin">Админ</option>
            <option value="stylist">Стилист</option>
        </select>
    </label>
    <button type="submit">Добавить</button>
</form>