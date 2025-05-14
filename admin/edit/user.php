<h3>Редактировать пользователя #<?= $edit['id'] ?></h3>
<form method="POST" action="/admin/edit/user_update.php">
  <!-- признак редактирования -->
  <input type="hidden" name="edit_id" value="<?= $edit['id'] ?>">

  <label>Имя:<br>
    <input type="text" name="name"
           value="<?= htmlspecialchars($edit['name']) ?>"
           required>
  </label><br><br>

  <label>Email:<br>
    <input type="email" name="email"
           value="<?= htmlspecialchars($edit['email']) ?>"
           required>
  </label><br><br>

  <label>Телефон:<br>
    <input type="text" name="phone"
           value="<?= htmlspecialchars($edit['phone'] ?? '') ?>">
  </label><br><br>

  <label>Дата рождения:<br>
    <input type="date" name="birthdate"
           value="<?= htmlspecialchars($edit['birthdate'] ?? '') ?>">
  </label><br><br>

  <label>Пароль (если не менять — оставить пустым):<br>
    <input type="password" name="password">
  </label><br><br>

  <label>Роль:<br>
    <select name="role">
      <option value="user"    <?= $edit['role']==='user'    ? 'selected':'' ?>>Пользователь</option>
      <option value="admin"   <?= $edit['role']==='admin'   ? 'selected':'' ?>>Админ</option>
      <option value="stylist" <?= $edit['role']==='stylist' ? 'selected':'' ?>>Стилист</option>
    </select>
  </label><br><br>

  <button type="submit">Сохранить</button>
  <a href="?section=users">Отмена</a>
</form>