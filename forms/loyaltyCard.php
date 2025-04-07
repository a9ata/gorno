<div class="modal-loyalty-overlay" id="loyaltyModal">
    <div class="modal-loyalty-content">
        <button class="modal-loyalty-close">&times;</button>
        <h2>Оформление карты лояльности</h2>

        <form method="POST" action="/forms/handle_loyalty.php" class="form-loyalty">
            <input type="text" name="name" placeholder="Имя" required>
            <input type="email" name="email" placeholder="Почта" required>
            <input type="date" name="birthdate" required>
            <button type="submit">Отправить</button>
        </form>
    </div>
</div>