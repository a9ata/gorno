<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

// допустимые типы и колонки
$allowed = [
    'примерка на дому'     => ['id','user_id','date','time','address','description','created_at'],
    'консультация'         => ['id','user_id','stylist_id','date','time','created_at'],
    'индивидуальный заказ' => ['id','user_id','date','time','description','created_at'],
];

$users    = [];
$stylists = [];
$r = $conn->query("SELECT id, name, email, role FROM users ORDER BY name");
while ($row = $r->fetch_assoc()) {
  $users[] = $row;
}
$r = $conn->query("SELECT id, name, email FROM users WHERE role='stylist' ORDER BY name");
while ($row = $r->fetch_assoc()) {
  $stylists[] = $row;
}

// --- Режим «Редактирование»? Подгружаем бронь по edit_id ---
$editBooking = null;
if (!empty($_GET['edit_id'])) {
  $eid = (int)$_GET['edit_id'];
  $q = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
  $q->bind_param('i', $eid);
  $q->execute();
  $editBooking = $q->get_result()->fetch_assoc();
  // Если вдруг не нашлось — сбрасываем
  if (! $editBooking) {
    $editBooking = null;
  }
}

// определяем текущий тип (из GET)
$type = mb_strtolower($_GET['type'] ?? 'примерка на дому');
if (! isset($allowed[$type])) {
    header('Location: index.php?section=bookings&type=' . urlencode('примерка на дому'));
    exit;
}

$cols    = $allowed[$type];
$sqlCols = implode(',', $cols);

// готовим и выполняем запрос
$stmt = $conn->prepare("SELECT $sqlCols FROM bookings WHERE `type` = ?");
$stmt->bind_param('s', $type);
$stmt->execute();
$result = $stmt->get_result();
?>


<nav class="tabs">
  <ul>
    <?php foreach ($allowed as $_type => $_cols): ?>
      <li>
        <a href="index.php?section=bookings&type=<?= urlencode($_type) ?>"
           class="<?= htmlspecialchars($_type === $type ? 'active' : '') ?>">
          <?= htmlspecialchars(mb_convert_case($_type, MB_CASE_TITLE, 'UTF-8')) ?>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
</nav>
<h2>Бронирования: «<?= htmlspecialchars(mb_convert_case($type, MB_CASE_TITLE, 'UTF-8')) ?>»</h2>

  <section>
    <div>
        <table>
            <thead>
                <tr>
                    <?php foreach ($cols as $c): ?>
                        <th><?= htmlspecialchars($c) ?></th>
                    <?php endforeach ?>
                <th colspan="2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($cols as $c): ?>
                    <td><?= nl2br(htmlspecialchars($row[$c])) ?></td>
                    <?php endforeach ?>
                    <td>
                        <a href="?section=bookings&edit_id=<?= htmlspecialchars($row['id']) ?>&type=<?= htmlspecialchars(urlencode($type)) ?>">Редакт.</a>
                    </td>
                    <td>
                        <form action="/delete/booking.php" method="POST" style="display:inline">
                            <input type="hidden" name="id"   value="<?= htmlspecialchars($row['id']) ?>">
                            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                            <button type="submit" onclick="return confirm('Удалить #<?= htmlspecialchars($row['id']) ?>?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <div class="form">
        <?php if ($editBooking): ?>
            <?php require __DIR__ . '/../admin/edit/booking.php'; ?>
        <?php else: ?>
            <?php require __DIR__ . '/../admin/add/booking.php'; ?>
        <?php endif; ?>
    </div>
  </section>