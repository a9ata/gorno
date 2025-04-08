<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Получаем список товаров с основной информацией
$sql = "SELECT 
    p.id, 
    p.name, 
    p.gender, 
    p.price, 
    p.description, 
    s.name AS subcategory, 
    c.name AS category
FROM products p
JOIN subcategories s ON p.subcategory_id = s.id
JOIN categories c ON s.category_id = c.id
ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>

<h2>Товары</h2>

<a href="/admin/add/product.php" class="btn">+ Добавить товар</a>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Подкатегория</th>
            <th>Пол</th>
            <th>Цена</th>
            <th>Описание</th>
            <th>Действия</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['subcategory']) ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['price'] ?> ₽</td>
                <td><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 50, '...')) ?></td>
                <td>
                    <a href="/admin/edit/product.php?id=<?= $row['id'] ?>">Ред.</a>
                </td>
                <td>
                    <form action="/admin/delete/product.php" method="POST" onsubmit="return confirm('Удалить этот товар?');">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>