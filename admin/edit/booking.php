<?php
$b = $editBooking;
?>
<h3>Редактировать бронь #<?= htmlspecialchars($b['id']) ?></h3>
<?php if (! empty($_SESSION['error'])): ?>
  <p style="color:red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="/admin/edit/booking_update.php">
  <input type="hidden" name="id"   value="<?= htmlspecialchars($b['id']) ?>">
  <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

  <label>Тип:
    <select name="type" required>
      <?php foreach (array_keys($allowed) as $_t): ?>
        <option value="<?= htmlspecialchars($_t) ?>" <?= htmlspecialchars($_t === $b['type'] ? 'selected' : '') ?>>
          <?= htmlspecialchars(mb_convert_case($_t, MB_CASE_TITLE,'UTF-8')) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Пользователь:
    <select name="user_id" required>
      <?php foreach ($users as $u): ?>
        <option value="<?= htmlspecialchars($u['id']) ?>" <?= htmlspecialchars($u['id']==$b['user_id']?'selected':'') ?>>
          <?= htmlspecialchars("ID{$u['id']} – {$u['name']} ({$u['role']})") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Стилист:
    <select name="stylist_id">
      <option value="">— не выбран —</option>
      <?php foreach ($stylists as $s): ?>
        <option value="<?= htmlspecialchars($s['id']) ?>" <?= htmlspecialchars($s['id']==$b['stylist_id']?'selected':'') ?>>
          <?= htmlspecialchars("ID{$s['id']} – {$s['name']}") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>

  <label>Дата:
    <input type="date" name="date" value="<?= htmlspecialchars($b['date']) ?>" required>
  </label>

  <label>Время:
    <input type="time" name="time" value="<?= htmlspecialchars($b['time']) ?>" required>
  </label>

  <label>Адрес:
    <textarea name="address" rows="2"><?= htmlspecialchars($b['address'] ?? '') ?></textarea>
  </label>

  <label>Описание:
    <textarea name="description" rows="3"><?= htmlspecialchars($b['description'] ?? '') ?></textarea>
  </label>

  <button type="submit">Сохранить</button>
  <a href="?section=bookings&type=<?= urlencode($type) ?>" class="btn-secondary">Отмена</a>
</form>