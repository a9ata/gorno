<div class="login-modal hidden" id="loginModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeLoginModal()">&times;</span>

    <!-- Форма входа -->
    <form id="loginForm" class="auth-form" action="forms/auth/login.php" method="POST">
      <input type="email" name="email" placeholder="Почта" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <button type="submit">Войти</button>
      <p>Ещё нет аккаунта? <a href="#" onclick="switchForm('register')">Зарегистрироваться</a></p>
    </form>

    <!-- Форма регистрации -->
    <form id="registerForm" class="auth-form hidden" action="forms/auth/register.php" method="POST">
      <input type="text" name="name" placeholder="Имя" required>
      <input type="email" name="email" placeholder="Почта" required>
      <input type="tel" name="phone" id="phoneInput" placeholder="Телефон" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <input type="text" name="birthdate" id="birthdateInput" placeholder="гггг.мм.дд" required>
      <button type="submit">Зарегистрироваться</button>
      <p>Уже есть аккаунт? <a href="#" onclick="switchForm('login')">Войти</a></p>
    </form>
  </div>
</div>
