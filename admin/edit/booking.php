<?php
// admin/edit/booking.php — partial, подключается из bookings.php
// в этот момент есть $editBooking, $users, $stylists, $type

$b = $editBooking;
?>
<h3>Редактировать бронь #<?= $b['id'] ?></h3>
<?php if (! empty($_SESSION['error'])): ?>
  <p style="color:red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="/admin/edit/booking_update.php">
  <input type="hidden" name="id"   value="<?= $b['id'] ?>">
  <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

  <label>Тип:
    <select name="type" required>
      <?php foreach (array_keys($allowed) as $_t): ?>
        <option value="<?= $_t ?>" <?= $_t === $b['type'] ? 'selected' : '' ?>>
          <?= htmlspecialchars(mb_convert_case($_t, MB_CASE_TITLE,'UTF-8')) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br><br>

  <label>Пользователь:
    <select name="user_id" required>
      <?php foreach ($users as $u): ?>
        <option value="<?= $u['id'] ?>" <?= $u['id']==$b['user_id']?'selected':''?>>
          <?= htmlspecialchars("ID{$u['id']} – {$u['name']} ({$u['role']})") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br><br>

  <label>Стилист:
    <select name="stylist_id">
      <option value="">— не выбран —</option>
      <?php foreach ($stylists as $s): ?>
        <option value="<?= $s['id'] ?>" <?= $s['id']==$b['stylist_id']?'selected':''?>>
          <?= htmlspecialchars("ID{$s['id']} – {$s['name']}") ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label><br><br>

  <label>Дата:
    <input type="date" name="date" value="<?= htmlspecialchars($b['date']) ?>" required>
  </label><br><br>

  <label>Время:
    <input type="time" name="time" value="<?= htmlspecialchars($b['time']) ?>" required>
  </label><br><br>

  <label>Адрес:<br>
    <textarea name="address" rows="2"><?= htmlspecialchars($b['address'] ?? '') ?></textarea>
  </label><br><br>

  <label>Описание:<br>
    <textarea name="description" rows="3"><?= htmlspecialchars($b['description'] ?? '') ?></textarea>
  </label><br><br>

  <button type="submit">Сохранить</button>
  <a href="/admin/index.php?section=bookings&type=<?= urlencode($type) ?>">Отмена</a>
</form>