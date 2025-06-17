<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isAdmin()) {
    header("Location: /index.php");
    exit;
}

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

$edit = null;
if (! empty($_GET['edit_id'])) {
    $eid = (int) $_GET['edit_id'];

    $stmt = $conn->prepare("
        SELECT p.*, s.category_id, s.name AS subcategory_name, s.id AS subcategory_id
          FROM products p
          JOIN subcategories s ON p.subcategory_id = s.id
         WHERE p.id = ?
    ");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();

    if ($edit) {
        // изображения
        $stmt = $conn->prepare("SELECT * FROM product_images WHERE product_id = ?");
        $stmt->bind_param("i", $eid);
        $stmt->execute();
        $imgs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $edit['images'] = $imgs;
        // атрибуты
        $stmt = $conn->prepare("
            SELECT pa.color, pa.size_id, s.name AS size_name, pa.quantity
            FROM product_attributes pa
            JOIN sizes s ON pa.size_id = s.id
            WHERE pa.product_id = ?
        ");
        $stmt->bind_param("i", $eid);
        $stmt->execute();
        $attrs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $edit['colors']   = array_unique(array_column($attrs, 'color'));
        $edit['sizes']    = array_unique(array_column($attrs, 'size_name'));
        $edit['quantity'] = $attrs[0]['quantity'] ?? 0;
            }
}
?>

<h2>Товары</h2>

<section>
    <div>
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
                    <th colspan="2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['subcategory']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?> ₽</td>
                    <td><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 50, '...')) ?></td>
                    <td>
                        <a href="?section=products&edit_id=<?= htmlspecialchars($row['id']) ?>">Ред.</a>
                    </td>
                    <td>
                        <form action="/admin/delete/product.php" method="POST"
                            onsubmit="return confirm('Удалить этот товар?');">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit">Удалить</button>
                        </form>
                    </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="form">
        <?php if ($edit): ?>
            <?php require __DIR__ . '/../admin/edit/product.php'; ?>
        <?php else: ?>
            <?php require __DIR__ . '/../admin/add/product.php'; ?>
        <?php endif; ?>
    </div>
</section>