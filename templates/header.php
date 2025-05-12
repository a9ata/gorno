<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_URL ?>styles.css">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <link rel="stylesheet" href="<?= CSS_URL ?>admin.css">
    <?php endif; ?>
    <link rel="icon" href="<?= ICONS_URL ?>favicon.svg" type="image/svg+xml">
    <title><?= htmlspecialchars($pageTitle) ?></title>
</head>
<body>
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
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/admin/index.php" class="icon-link" title="Админ-панель">
                    <img src="<?= ICONS_URL ?>admin-panel.svg" alt="Админ-панель">
                </a>
            <?php endif; ?>


            <a href="/favorite" class="icon-link">
                <img src="<?= ICONS_URL ?>favorite-default.svg" alt="Избранные" />
            </a>
            <a href="/cart" class="icon-link">
                <img src="<?= ICONS_URL ?>shopping-cart.svg" alt="Корзина" />
            </a>
            <?php include __DIR__ . '/../templates/modal-auth.php'; ?>
            <?php if (isset($_SESSION['name'])): ?>
                <a href="/index.php?page=profile" class="icon-link">
                    <img src="<?= ICONS_URL ?>account.svg" alt="Профиль">
                </a>
            <?php else: ?>
                <a href="javascript:void(0);" class="icon-link" onclick="openLoginModal()">
                    <img src="<?= ICONS_URL ?>login.svg" alt="Вход">
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>
