<h3>Редактировать пользователя #<?= htmlspecialchars($edit['id']) ?></h3>
<form method="POST" action="/admin/edit/user_update.php">

  <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit['id']) ?>">

  <label>Имя:
    <input type="text" name="name"
           value="<?= htmlspecialchars($edit['name']) ?>"
           required>
  </label>

  <label>Email:
    <input type="email" name="email"
           value="<?= htmlspecialchars($edit['email']) ?>"
           required>
  </label>

  <label>Телефон:
    <input type="text" name="phone"
           value="<?= htmlspecialchars($edit['phone'] ?? '') ?>">
  </label>

  <label>Дата рождения:
    <input type="date" name="birthdate"
           value="<?= htmlspecialchars($edit['birthdate'] ?? '') ?>">
  </label>

  <label>Пароль (если не менять — оставить пустым):
    <input type="password" name="password">
  </label>

  <label>Роль:
    <select name="role">
      <option value="user"    <?= htmlspecialchars($edit['role']==='user'    ? 'selected':'') ?>>Пользователь</option>
      <option value="admin"   <?= htmlspecialchars($edit['role']==='admin'   ? 'selected':'') ?>>Админ</option>
      <option value="stylist" <?= htmlspecialchars($edit['role']==='stylist' ? 'selected':'') ?>>Стилист</option>
    </select>
  </label>

  <button type="submit">Сохранить</button>
  <a href="?section=users" class="btn-secondary">Отмена</a>
</form>