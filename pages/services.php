<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/booking_function.php';

$userName  = $_SESSION['name']  ?? '';
$userEmail = $_SESSION['email'] ?? '';
$userPhone = $_SESSION['phone'] ?? '';
$userBirthdate = $_SESSION['birthdatephone'] ?? '';
?>

<section class="booking">
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; text-align: center;">Заявка успешно отправлена!</p>
    <?php endif; ?>
    <div>
        <h2 id="online-stylists">Услуги онлайн-стилиста</h2>
        <?php include __DIR__ . '/../forms/booking/stylist.php'; ?>

        <h2 id="custom-order">Индивидуальные заказы</h2>
        <?php include __DIR__ . '/../forms/booking/customOrder.php'; ?>
    </div>
    <div>
        <h2 id="home-fitting">Примерка на дому</h2>
        <?php include __DIR__ . '/../forms/booking/fitting.php'; ?>
    </div>    
</section>

<section class="loyaltycard" id="loyalty-program">
<h2>Программа лояльности</h2>
    <div>
        <div>
            <div>
                <h2>Программа лояльности</h2>
                <h1>Горно</h1>
            </div>
            <img src="<?= IMAGES_URL ?>loyalty_card.png" alt="Программа лояльности">
        </div>
        <div>
            <h2>Персональная скидка</h2>
            <div>
                <p>Программа лояльности магазина Горно действует согласно 
                    накопительной системе и подразумевает предоставление 
                    персональной скидки, размер которой зависит от суммы ваших накоплений.
                </p>
                <p>Скидка 5% — сумма покупок от 50 000 до 99 999 ₽
                    Скидка 10% — сумма покупок от 100 000 до 149 999 ₽
                    Скидка 15% — сумма покупок более 150 000 ₽
                </p>
                <p>Для получения персональной скидки необходимо оформить 
                    карту постоянного покупателя, заполнив форму на сайте 
                    или на кассе одного из магазинов. При оформлении 
                    накопительной карты потребуется указать: ФИО, адрес 
                    электронной почты и дату вашего рождения.
                </p>
                <span>Обращаем внимание, что персональная скидка по 
                    карте Горно не распространятся на отдельные 
                    позиции ассортимента и не суммируется со 
                    скидками товаров категории Sale и промокодами.
                </span>
            </div>
            <button onclick="openModal()">Оформить прямо сейчас</button>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../forms/loyaltyCard.php'; ?>
