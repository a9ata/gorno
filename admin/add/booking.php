<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Получаем пользователей
$users = [];
if ($res = $conn->query("SELECT id, name, email, role FROM users ORDER BY name")) {
    while ($r = $res->fetch_assoc()) {
        $users[] = $r;
    }
}

// Получаем стилистов
$stylists = [];
if ($res = $conn->query("SELECT id, name, email FROM users WHERE role='stylist' ORDER BY name")) {
    while ($r = $res->fetch_assoc()) {
        $stylists[] = $r;
    }
}
?>
<h3>Добавить бронь</h3>
<form method="post" action="/admin/add/booking_add.php">
  <label>Тип:
    <select name="type" required>
      <option value="примерка на дому">Примерка на дому</option>
      <option value="консультация">Консультация</option>
      <option value="индивидуальный заказ">Индивидуальный заказ</option>
    </select>
  </label>

  <label>Пользователь:
    <select name="user_id" required>
      <option value="">— выберите —</option>
      <?php foreach ($users as $u): ?>
        <option value="<?= htmlspecialchars($u['id']) ?>">
          <?= htmlspecialchars("ID {$u['id']} – {$u['name']} ({$u['role']}) – {$u['email']}") ?>
        </option>
      <?php endforeach ?>
    </select>
  </label>

  <label>Стилист (для консультации):
    <select name="stylist_id">
      <option value="">— не выбран —</option>
      <?php foreach ($stylists as $s): ?>
        <option value="<?= htmlspecialchars($s['id']) ?>">
          <?= htmlspecialchars("ID {$s['id']} – {$s['name']} – {$s['email']}") ?>
        </option>
      <?php endforeach ?>
    </select>
  </label>

  <label>Дата:
    <input type="date" name="date" required>
  </label>

  <label>Время:
    <input type="time" name="time" required>
  </label>

  <label>Адрес:
    <textarea name="address" rows="2" cols="30"></textarea>
  </label>

  <label>Описание:
    <textarea name="description" rows="3" cols="30"></textarea>
  </label>

  <button type="submit">Сохранить</button>
</form>