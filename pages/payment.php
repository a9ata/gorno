<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

$userId = $_SESSION['id_user'] ?? null;
$selected = $_SESSION['selected_items'] ?? [];

if (empty($selected)) {
    echo "<p style='color: red;'>Вы не выбрали товары для оплаты.</p>";
    exit;
}

$discountPercentage = 0;
$stmt = $conn->prepare("SELECT discount_percentage FROM loyalty_cards WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $discountPercentage = $row['discount_percentage'];
}

$placeholders = implode(',', array_fill(0, count($selected), '?'));
$types = str_repeat('i', count($selected) + 1);
$params = array_merge([$userId], $selected);

$sql = "SELECT c.*, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ? AND c.id IN ($placeholders)";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();
$cartItems = $res->fetch_all(MYSQLI_ASSOC);

$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>

<section class="payment">
    <h2>Оплата</h2>
    <div class="payment-block">
        <div class="payment-summary">
            <p><strong>Количество товаров:</strong> <?= count($cartItems) ?></p>
            <p><strong>Сумма товаров:</strong> <?= number_format($totalAmount, 0, '.', ' ') ?> ₽</p>
            <p><strong>Доставка:</strong> <span id="deliveryCost">–</span></p>
            <p><strong>Скидка по карте:</strong> <span id="discountText">–</span></p>
            <p><strong>Итоговая сумма:</strong> <span id="finalSum"><?= number_format($totalAmount, 0, '.', ' ') ?> ₽</span></p>
        </div>

        <form action="/includes/process_payment.php" method="POST" id="paymentForm">
            <select name="payment_method_id" required>
                <option disabled selected>Выберите способ оплаты</option>
                <option value="1">Картой онлайн</option>
                <option value="2">Оплата при получении</option>
                <option value="3">СБП</option>
            </select>

            <select name="delivery_method_id" id="deliverySelect" required>
                <option disabled selected>Выберите доставку</option>
                <?php
                $deliveryMethods = [];
                $res = $conn->query("SELECT id, name, price FROM delivery_methods");
                while ($row = $res->fetch_assoc()) {
                    echo '<option value="'.$row['id'].'" data-price="'.$row['price'].'">'
                        .$row['name'].' ('.number_format($row['price'], 0, '.', ' ').' ₽)</option>';
                }
                ?>
            </select>
            <div id="addressField">
                <input type="text" name="delivery_address" id="delivery_address" placeholder="Адрес доставки">
            </div>

            <input type="text" name="name" placeholder="Имя" required>
            <input type="email" name="email" placeholder="Почта" required>
            <input type="text" name="card_number" placeholder="Номер карты" required>
            <input type="text" name="cvc" placeholder="CVC код" required>
            <input type="text" name="expiry" placeholder="мм/гг" required>

            <input type="hidden" name="total" id="totalInput" value="<?= htmlspecialchars($totalAmount) ?>">

            <?php foreach ($selected as $id): ?>
                <input type="hidden" name="selected_items[]" value="<?= htmlspecialchars($id) ?>">
            <?php endforeach; ?>

            <button type="submit">Оплатить</button>
        </form>
    </div>
</section>
<script>
   const deliverySelect = document.getElementById('deliverySelect');
    const deliveryCostSpan = document.getElementById('deliveryCost');
    const discountText = document.getElementById('discountText');
    const finalSumSpan = document.getElementById('finalSum');
    const totalInput = document.getElementById('totalInput');

    const addressField = document.getElementById('addressField');
    const addressInput = document.getElementById('delivery_address');

    const productTotal = <?= $totalAmount ?>;
    const discountPercent = <?= $discountPercentage ?>;

    const SELF_PICKUP_ID = 2; // ID самовывоза

    // Показать скидку
    discountText.textContent = `${discountPercent}%`;

    function updateUI() {
        const selectedOption = deliverySelect.options[deliverySelect.selectedIndex];
        const deliveryPrice = parseFloat(selectedOption.dataset.price || 0);
        const deliveryId = parseInt(selectedOption.value);

        // Показываем или скрываем поле адреса
        if (deliveryId === SELF_PICKUP_ID) {
            addressField.style.display = 'none';
            addressInput.removeAttribute('required');
            addressInput.value = '';
        } else {
            addressField.style.display = 'block';
            addressInput.setAttribute('required', 'required');
        }

        // Пересчёт итоговой суммы
        const discountValue = productTotal * (discountPercent / 100);
        const finalSum = productTotal + deliveryPrice - discountValue;

        deliveryCostSpan.textContent = `${deliveryPrice.toFixed(0)} ₽`;
        finalSumSpan.textContent = `${finalSum.toFixed(0)} ₽`;
        totalInput.value = finalSum.toFixed(2);
    }

    deliverySelect.addEventListener('change', updateUI);

    // Вызов при загрузке, если значение уже выбрано
    window.addEventListener('DOMContentLoaded', () => {
        if (deliverySelect.value) {
            updateUI();
        }
    });
</script>