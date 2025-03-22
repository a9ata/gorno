<?php
    include_once 'includes/functions.php'; // Подключаем файл с функциями
    $categories = getCategories(); // Получаем данные из базы
?>

<header class="header">
    <nav class="header-container">
        <div class="header-logo">
            <a href="/index.php">
                <h1>Горно</h1>
            </a>
        </div>
        <ul class="header-menu">
            <?php foreach (['Женщины', 'Мужчины', 'Девочки', 'Мальчики'] as $section): ?>
                <li>
                    <a href="#"><?= $section ?></a>
                    <ul class="dropdown">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category => $subcategories): ?>
                                <li>
                                    <p><?= $category ?></p>
                                    <?php if (!empty($subcategories)): ?>
                                        <ul>
                                            <?php foreach ($subcategories as $subcategory): ?>
                                                <li><a href="#"><?= $subcategory ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="user-actions">
            <a href="/?page=favorite" class="icon-link">
                <img src="<?= ICONS_URL ?>favorite-default.svg" alt="Избранные" />
            </a>
            <a href="/?page=cart" class="icon-link">
                <img src="<?= ICONS_URL ?>shopping-cart.svg" alt="Корзина" />
            </a>
            <?php if (isset($_SESSION['user'])): ?>
                <p>Привет, <?= htmlspecialchars($_SESSION['user']) ?>!</p>
                <a href="logout.php" class="icon-link">Выйти</a>
            <?php else: ?>
                <a href="javascript:void(0);" class="icon-link" onclick="openLoginModal()">
                    <img src="<?= ICONS_URL ?>login.svg" alt="Login">
                </a>
                <?php include 'templates/modal-auth.php'; ?>
            <?php endif; ?>
        </div>
    </nav>
</header>
