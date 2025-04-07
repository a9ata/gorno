<?php if ($_SESSION['role'] === 'admin'): ?>
    <nav class="admin-nav">
        <ul>
            <li><a href="/admin/users.php">Пользователи</a></li>
            <li><a href="/admin/products.php">Товары</a></li>
            <li><a href="/admin/orders.php">Заказы</a></li>
            <li><a href="/admin/faq.php">FAQ</a></li>
        </ul>
    </nav>
<?php endif; ?>