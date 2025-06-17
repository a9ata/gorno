<?php
$stylists = getStylists($conn);
?>

<form method="POST" action="/forms/booking/handle_stylist.php" class="form-stylist">
    <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($userName) ?>" required>
    <input type="email" name="email" placeholder="Почта" value="<?= htmlspecialchars($userEmail) ?>" required>
    <input type="time" name="time" required>
    <input type="date" name="date" required>

    <div class="stylist-select">
        <?php foreach ($stylists as $stylist): ?>
            <label>
                <input type="radio" name="stylist_id" value="<?= htmlspecialchars($stylist['id']) ?>" required>
                <?= htmlspecialchars($stylist['name']) ?>
            </label>
        <?php endforeach; ?>
    </div>

    <button type="submit">Отправить</button>
</form>