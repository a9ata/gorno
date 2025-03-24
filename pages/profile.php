<?php
if (!isset($_SESSION['name'])) {
    header("Location: /");
    exit;
}
?>

<section class="profile">
    <h2>Профиль пользователя</h2>

    <form id="profileForm" action="/forms/auth/update-profile.php" method="post">
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['name']) ?>" readonly>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly>
        <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($_SESSION['phone']) ?>" readonly>
        <input type="text" name="birthdate" id="birthdate" value="<?= htmlspecialchars($_SESSION['birthdate']) ?>" readonly>

        <button type="button" id="editBtn">Редактировать</button>
        <button type="submit" id="saveBtn" class="hidden">Сохранить</button>
    </form>

    <form action="/forms/auth/logout.php" method="post">
        <button type="submit" class="logout-btn">Выйти</button>
    </form>
</section>