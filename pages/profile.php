<?php
session_start();
include_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/booking_function.php';
$pageTitle = 'Профиль — Горно';


$userId = $_SESSION['id_user'] ?? null;

$userName  = $_SESSION['name']  ?? '';
$userEmail = $_SESSION['email'] ?? '';
$userBirthdate = $_SESSION['birthdatephone'] ?? '';

$bookings = [];
$stmt = $conn->prepare("SELECT type, date, time, stylist_id, description 
                        FROM bookings WHERE user_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}


$loyalty = null;
$stmt = $conn->prepare("SELECT total_spent, discount_percentage FROM loyalty_cards WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$loyalty = $res->fetch_assoc();


$isActive = $loyalty !== null;

$totalSpent = 0;
$stmt = $conn->prepare("SELECT SUM(total_amount) AS total FROM orders WHERE user_id = ? AND status = 'paid'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$totalSpent = $row['total'] ?? 0.00;

// Вычисляем скидку
function calculateDiscount($totalSpent) {
    if ($totalSpent >= 150000) return 15;
    if ($totalSpent >= 100000) return 10;
    if ($totalSpent >= 50000)  return 5;
    return 0;
}
$discount = calculateDiscount($totalSpent);

?>
<section class="profile">
    <div class="profile-info">
        <h2>Профиль</h2>

        <form id="profileForm" action="/forms/auth/update-profile.php" method="post">
            
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['name']) ?>" readonly>
            
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
        
            <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($_SESSION['phone']) ?>" readonly>
        
            <input type="text" name="birthdate" id="birthdate" value="<?= htmlspecialchars($_SESSION['birthdate']) ?>" readonly>
            
            <input type="password" name="new_password" id="new_password" placeholder="Оставьте пустым, если не нужно менять" readonly>

            <div class="profile-buttons">
                <button type="button" id="editBtn">Редактировать</button>
                <button type="submit" id="saveBtn" class="hidden">Сохранить</button>
            </div>
        </form>

        <form action="/forms/auth/logout.php" method="post">
            <button type="submit" class="logout-btn">Выйти</button>
        </form>
    </div>
    <div class="booking">
        <div class="user-bookings">
            <?php if (empty($bookings)): ?>
                <p>Вы ещё не оформляли заявки.</p>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-mini-card">
                        <h3><?= htmlspecialchars(ucfirst($booking['type'])) ?></h3>

                        <?php if ($booking['type'] === 'консультация'): ?>
                            <label>Дата:
                                <input type="text" readonly value="<?= htmlspecialchars($booking['date']) ?>">
                            </label>
                            <label>Время:
                                <input type="text" readonly value="<?= htmlspecialchars($booking['time']) ?>">
                            </label>
                            <label>Стилист:
                                <input type="text" readonly value="<?= getStylistName($booking['stylist_id'], $conn) ?>">
                            </label>

                        <?php elseif ($booking['type'] === 'примерка на дому'): ?>
                            <label>Дата:
                                <input type="text" readonly value="<?= htmlspecialchars($booking['date']) ?>">
                            </label>
                            <label>Время:
                                <input type="text" readonly value="<?= htmlspecialchars($booking['time']) ?>">
                            </label>

                        <?php elseif ($booking['type'] === 'индивидуальный заказ'): ?>
                            <label>Описание:
                                <input type="text" readonly value="<?= htmlspecialchars($booking['description']) ?>">
                            </label>
                            <label>Статус:
                                <input type="text" readonly value="Заявка обрабатывается">
                            </label>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <div class="loyalty-card-box">
            <div class="loyalty-card">
                <div class="loyalty-active">
                    <h2>ГОРНО</h2>
                    <div>
                        <?php if (!$isActive): ?>
                            <p style="color: red;">Неактивна</p>
                            <button class="open-loyalty-modal" style="color: blue;">Активировать</button>
                        <?php else: ?>
                            <p class="loyalty-status" style="color: green;">Активна</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="loyalty-info">
                    <div>Скидка</div>
                    <div>Сумма покупок</div>
                </div>
                <div class="loyalty-values">
                    <strong><?= $discount ?>%</strong>
                    <strong><?= number_format($totalSpent, 0, '.', ' ') ?> ₽</strong>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('editBtn').addEventListener('click', function () {
    const fields = ['name', 'phone', 'birthdate', 'new_password'];
    fields.forEach(id => {
        const el = document.getElementById(id);
        el.removeAttribute('readonly');
    });

    document.getElementById('saveBtn').classList.remove('hidden');
    this.classList.add('hidden');
});
</script>
