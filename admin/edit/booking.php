<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/admin_function.php';

if (! isAdmin()) {
    header('Location: /');
    exit;
}

$users    = [];
$stylists = [];

$res = $conn->query("SELECT id, name, email, role FROM users ORDER BY name");
while ($r = $res->fetch_assoc()) {
    $users[] = $r;
}

$res = $conn->query("SELECT id, name, email FROM users WHERE role = 'stylist' ORDER BY name");
while ($r = $res->fetch_assoc()) {
    $stylists[] = $r;
}

$id   = isset($_GET['id'])   ? (int) $_GET['id']   : 0;
$type = isset($_GET['type']) ? $_GET['type']       : 'примерка на дому';

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (! $booking) {
    header('Location: /admin/index.php?section=bookings&type=' . urlencode($type));
    exit;
}

?>
<h1>Редактировать бронь #<?= $booking['id'] ?></h1>

  <?php if (! empty($_SESSION['error'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <form method="POST" action="/admin/edit/booking_update.php">
    <input type="hidden" name="id"   value="<?= $booking['id'] ?>">
    <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

    <label>Тип:
      <select name="type" required>
        <?php foreach (['примерка на дому','консультация','индивидуальный заказ'] as $t): ?>
        <option value="<?= $t ?>"
          <?= $t === $booking['type'] ? 'selected' : '' ?>>
          <?= mb_convert_case($t, MB_CASE_TITLE, 'UTF-8') ?>
        </option>
        <?php endforeach ?>
      </select>
    </label>

    <label>Пользователь:
      <select name="user_id" required>
        <?php foreach ($users as $u): ?>
        <option value="<?= $u['id'] ?>"
          <?= $u['id'] === (int)$booking['user_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars("ID {$u['id']} – {$u['name']} ({$u['role']}) – {$u['email']}") ?>
        </option>
        <?php endforeach ?>
      </select>
    </label>

    <label>Стилист (для консультации):
      <select name="stylist_id">
        <option value="">— не выбран —</option>
        <?php foreach ($stylists as $s): ?>
        <option value="<?= $s['id'] ?>"
          <?= $s['id'] === (int)$booking['stylist_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars("ID {$s['id']} – {$s['name']} – {$s['email']}") ?>
        </option>
        <?php endforeach ?>
      </select>
    </label>

    <label>Дата:
      <input type="date" name="date"
             value="<?= htmlspecialchars($booking['date']) ?>" required>
    </label>

    <label>Время:
      <input type="time" name="time"
            value="<?= htmlspecialchars($booking['time']) ?>" required>
    </label>

    <label>Адрес:
      <textarea name="address" rows="2"><?= 
        htmlspecialchars($booking['address'] ?? '') 
      ?></textarea>
    </label>

    <label>Описание:
      <textarea name="description" rows="3"><?= htmlspecialchars($booking['description']) ?></textarea>
    </label>

    <button type="submit">Сохранить</button>
    <a href="/admin/index.php?section=bookings&type=<?= urlencode($type) ?>">Отмена</a>
  </form>