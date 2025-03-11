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
                                    <a href="#"><?= $category ?></a>
                                    <?php if (!empty($subcategories)): ?>
                                        <ul class="sub-dropdown">
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
            <a href="/?page=shoppingCart" class="icon-link">
                <img src="<?= ICONS_URL ?>shopping-cart.svg" alt="Корзина" />
            </a>
            <a href="/?forms/auth/login.php=login" class="icon-link">
                <img src="<?= ICONS_URL ?>login.svg" alt="Логин" />
            </a>
        </div>
    </nav>
</header>
