<form method="POST" action="/forms/booking/handle_custom_order.php" class="form-custom-order">
    <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($userName) ?>" required>
    <input type="email" name="email" placeholder="Почта" value="<?= htmlspecialchars($userEmail) ?>" required>
    <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($userPhone) ?>">

    <textarea name="description" placeholder="Введите описание заказа" required></textarea>

    <button type="submit">Отправить</button>
</form>