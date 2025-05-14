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

<h2>Бронирования: «<?= htmlspecialchars(mb_convert_case($type, MB_CASE_TITLE, 'UTF-8')) ?>»</h2>

  <!-- переключатели типов -->
  <div class="tabs">
    <?php foreach ($allowed as $_type => $_cols): ?>
        <a href="index.php?section=bookings&type=<?= urlencode($_type) ?>"
            class="<?= $_type === $type ? 'active' : '' ?>">
            <?= htmlspecialchars(mb_convert_case($_type, MB_CASE_TITLE,'UTF-8')) ?>
        </a>
    <?php endforeach ?>
  </div>

  <section>
    <div>
        <table>
            <thead>
                <tr>
                <?php foreach ($cols as $c): ?>
                    <th><?= htmlspecialchars($c) ?></th>
                <?php endforeach ?>
                <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($cols as $c): ?>
                    <td><?= nl2br(htmlspecialchars($row[$c])) ?></td>
                    <?php endforeach ?>
                    <td>
                        <a href="/admin/edit/booking.php?id=<?= $row['id'] ?>&type=<?= urlencode($type) ?>">Редакт.</a>
                        |
                        <form action="/admin/delete/booking.php" method="POST" style="display:inline">
                            <input type="hidden" name="id"   value="<?= $row['id'] ?>">
                            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                            <button type="submit" onclick="return confirm('Удалить #<?= $row['id'] ?>?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>

    <div class="add-form">
        <?php require_once __DIR__ . '/../admin/add/booking.php'; ?>
    </div>
  </section>