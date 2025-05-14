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