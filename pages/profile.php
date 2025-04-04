<?php
session_start();
?>

<section class="profile">
    <h2>Профиль пользователя</h2>

    <form id="profileForm" action="/forms/auth/update-profile.php" method="post">
        <label>
            Имя:
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['name']) ?>" readonly>
        </label>

        <label>
            Email:
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
        </label>

        <label>
            Телефон:
            <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($_SESSION['phone']) ?>" readonly>
        </label>

        <label>
            Дата рождения:
            <input type="text" name="birthdate" id="birthdate" value="<?= htmlspecialchars($_SESSION['birthdate']) ?>" readonly>
        </label>

        <label>
            Новый пароль:
            <input type="password" name="new_password" id="new_password" placeholder="Оставьте пустым, если не нужно менять" readonly>
        </label>

        <div class="profile-buttons">
            <button type="button" id="editBtn">Редактировать</button>
            <button type="submit" id="saveBtn" class="hidden">Сохранить</button>
        </div>
    </form>

    <form action="/forms/auth/logout.php" method="post">
        <button type="submit" class="logout-btn">Выйти</button>
    </form>
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
