<div class="login-modal hidden" id="loginModal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeLoginModal()">&times;</span>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="form-error">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="form-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <!-- Форма входа -->
    <form id="loginForm" class="auth-form" action="forms/auth/login.php" method="POST">
      <input type="email" name="email" placeholder="Почта" required>
      <input type="password" name="password" placeholder="Пароль" required>

      <!-- контейнер SmartCaptcha -->
      <div
        class="smart-captcha"
        data-sitekey="<?= htmlspecialchars(YANDEX_CAPTCHA_CLIENT) ?>"
        data-callback="onCaptchaSuccess"
        data-expired-callback="onCaptchaExpired">
      </div>
      <!-- скрытое поле с токеном -->
      <input type="hidden" name="captcha_token" id="loginCaptchaToken">

      <button type="submit" id="loginSubmit" disabled>Войти</button>
      <p>Ещё нет аккаунта? <a href="#" onclick="switchForm('register')">Зарегистрироваться</a></p>
    </form>

    <!-- Форма регистрации -->
    <form id="registerForm" class="auth-form hidden" action="forms/auth/register.php" method="POST">
      <input type="text" name="name" placeholder="Имя" required>
      <input type="email" name="email" placeholder="Почта" required>
      <input type="tel" name="phone" id="phoneInput" placeholder="Телефон" required>
      <input type="password" name="password" placeholder="Пароль" required>
      <input type="text" name="birthdate" id="birthdateInput" placeholder="гггг.мм.дд" required>

      <!-- контейнер SmartCaptcha -->
      <div
        class="smart-captcha"
        data-sitekey="<?= htmlspecialchars(YANDEX_CAPTCHA_CLIENT) ?>"
        data-callback="onCaptchaSuccess"
        data-expired-callback="onCaptchaExpired">
      </div>
      <!-- скрытое поле с токеном -->
      <input type="hidden" name="captcha_token" id="registerCaptchaToken">

      <button type="submit" id="registerSubmit" disabled>Зарегистрироваться</button>
      <p>Уже есть аккаунт? <a href="#" onclick="switchForm('login')">Войти</a></p>
    </form>
  </div>
</div>
