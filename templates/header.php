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
            <a href="/favorite" class="icon-link">
                <img src="<?= ICONS_URL ?>favorite-default.svg" alt="Избранные" />
            </a>
            <a href="/cart" class="icon-link">
                <img src="<?= ICONS_URL ?>shopping-cart.svg" alt="Корзина" />
            </a>
            <a href="javascript:void(0);" class="icon-link" onclick="openLoginModal()">
                <img src="<?= ICONS_URL ?>login.svg" alt="Вход">
            </a>
            <?php include 'templates/modal-auth.php'; ?>                                    
            <?php if (isset($_SESSION['name'])): ?>
                <a href="/profile" class="user-name">
                    <?= htmlspecialchars($_SESSION['name']) ?>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>
