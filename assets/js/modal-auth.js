// === Функции открытия/закрытия модалки и переключения форм ===
function openLoginModal() {
    document.getElementById("loginModal").classList.remove("hidden");
  }
  
  function closeLoginModal() {
    document.getElementById("loginModal").classList.add("hidden");
  }
  
  function switchForm(target) {
    document.getElementById("loginForm")
            .classList.toggle("hidden", target === 'register');
    document.getElementById("registerForm")
            .classList.toggle("hidden", target === 'login');
  }
  
  // === Коллбэки для SmartCaptcha ===
  function onCaptchaSuccess(token) {
    // логин-форма
    const lc = document.getElementById('loginCaptchaToken');
    if (lc) {
      lc.value = token;
      document.getElementById('loginSubmit').disabled = false;
    }
    // регистра-форма
    const rc = document.getElementById('registerCaptchaToken');
    if (rc) {
      rc.value = token;
      document.getElementById('registerSubmit').disabled = false;
    }
  }
  
  function onCaptchaExpired() {
    ['loginCaptchaToken','registerCaptchaToken'].forEach(id => {
      const inp = document.getElementById(id);
      if (inp) inp.value = '';
    });
    document.getElementById('loginSubmit')?.setAttribute('disabled', true);
    document.getElementById('registerSubmit')?.setAttribute('disabled', true);
  }
  
  // === Вся остальная инициализация после загрузки DOM ===
  document.addEventListener("DOMContentLoaded", function () {
    // маски
    Inputmask("+7 (999) 999-99-99")
      .mask(document.getElementById("phoneInput"));
    Inputmask("9999.99.99")
      .mask(document.getElementById("birthdateInput"));
  
    // сабмит-валидация логина
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      loginForm.addEventListener('submit', function(e) {
        if (!document.getElementById('loginCaptchaToken').value) {
          e.preventDefault();
          alert('Пожалуйста, подтвердите, что вы не робот.');
        }
      });
    }
  
    // сабмит-валидация регистрации
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
      registerForm.addEventListener('submit', function(e) {
        if (!document.getElementById('registerCaptchaToken').value) {
          e.preventDefault();
          alert('Пожалуйста, подтвердите, что вы не робот.');
        }
      });
    }
});  