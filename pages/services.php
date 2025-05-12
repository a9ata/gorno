<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/booking_function.php';
$pageTitle = 'Услуги — Горно';

$userName  = $_SESSION['name']  ?? '';
$userEmail = $_SESSION['email'] ?? '';
$userPhone = $_SESSION['phone'] ?? '';
$userBirthdate = $_SESSION['birthdatephone'] ?? '';
?>

<section class="bookings">
    <div>
        <h1>Услуги</h1>
    </div>
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; text-align: center;">Заявка успешно отправлена!</p>
    <?php endif; ?>
    <div class="forms-bookings">
        <div>
            <h2 id="online-stylists">Услуги онлайн-стилиста</h2>
            <?php include __DIR__ . '/../forms/booking/stylist.php'; ?>
        </div>
        <div>
            <h2 id="custom-order">Индивидуальные заказы</h2>
            <?php include __DIR__ . '/../forms/booking/customOrder.php'; ?>
        </div>
        <div>
            <h2 id="home-fitting">Примерка на дому</h2>
            <?php include __DIR__ . '/../forms/booking/fitting.php'; ?>
        </div>
    </div>    
</section>

<section class="loyaltycard" id="loyalty-program">
    <h2 class="title">Программа лояльности</h2>
    <div class="content">
        <div class="visual">
            <div class="logo-box">
                <h2 class="subtitle">Программа лояльности</h2>
                <h1 class="brand">Горно</h1>
            </div>
            <img src="<?= IMAGES_URL ?>loyalty_card.png" alt="Программа лояльности" class="loyaltycard-image">
        </div>
        <div class="details">
            <h2>Персональная скидка</h2>
            <div class="text">
                <p>Программа лояльности магазина Горно действует согласно 
                накопительной системе и подразумевает предоставление 
                персональной скидки, размер которой зависит от суммы ваших накоплений.
                </p>
                <p>Скидка 5% — сумма покупок от 50 000 до 99 999 ₽<br>
                Скидка 10% — сумма покупок от 100 000 до 149 999 ₽<br>
                Скидка 15% — сумма покупок более 150 000 ₽
                </p>
                <p>Для получения персональной скидки необходимо оформить 
                карту постоянного покупателя, заполнив форму на сайте 
                или на кассе одного из магазинов. При оформлении 
                накопительной карты потребуется указать: ФИО, адрес 
                электронной почты и дату вашего рождения.
                </p>
                <span>Обращаем внимание, что персональная скидка по 
                карте Горно не распространяется на отдельные 
                позиции ассортимента и не суммируется со 
                скидками товаров категории Sale и промокодами.
                </span>
            </div>
            <button class="open-loyalty-modal">Оформить прямо сейчас</button>
        </div>
    </div>
</section>