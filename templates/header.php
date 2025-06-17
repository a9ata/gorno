<?php
$genders = [
    'Женщины' => 'f',
    'Мужчины'  => 'm',
    'Девочки'  => 'g',
    'Мальчики' => 'b',
];
$allCats = getAllCategories($conn);
?>
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
            <?php foreach ($genders as $label => $genderKey): ?>
            <li>
                <!-- ссылка на весь сегмент -->
                <a href="/catalog&gender=<?= htmlspecialchars($genderKey) ?>">
                <?= htmlspecialchars($label) ?>
                </a>
                <ul class="dropdown">
                <?php foreach ($allCats as $catId => $cat): ?>
                    <li>
                    <p>
                        <!-- ссылка на конкретную категорию -->
                        <a href="/catalog&gender=<?= htmlspecialchars($genderKey) ?>&category_id=<?= htmlspecialchars($catId) ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    </p>
                    <?php if (!empty($cat['subcategories'])): ?>
                        <ul>
                        <?php foreach ($cat['subcategories'] as $sub): ?>
                            <li>
                            <!-- ссылка на подкатегорию -->
                            <a href="/catalog&gender=<?= htmlspecialchars($genderKey) ?>&subcategory_id=<?= htmlspecialchars($sub['id']) ?>">
                                <?= htmlspecialchars($sub['name']) ?>
                            </a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    </li>
                <?php endforeach; ?>
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
                <a href="/profile" class="icon-link">
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
