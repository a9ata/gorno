<form method="POST" action="/forms/booking/handle_fitting.php" class="form-fitting">
    <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($userName) ?>" required>
    <input type="email" name="email" placeholder="Почта" value="<?= htmlspecialchars($userEmail) ?>" required>
    <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($userPhone) ?>">
    <input type="text" name="address" placeholder="Адрес" required>

    <input type="time" name="time" required>
    <input type="date" name="date" required>

    <textarea name="description" placeholder="Введите название товара(-ов), необходимый размер, цвет." required></textarea>

    <button type="submit">Отправить</button>
</form>