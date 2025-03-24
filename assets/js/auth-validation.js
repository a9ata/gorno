document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
  
    if (loginForm) {
      loginForm.addEventListener("submit", function (e) {
        const email = loginForm.querySelector('input[name="email"]').value.trim();
        const password = loginForm.querySelector('input[name="password"]').value;
  
        if (!validateEmail(email)) {
          alert("Введите корректный email.");
          e.preventDefault();
        } else if (password.length < 8) {
          alert("Пароль должен содержать минимум 8 символов.");
          e.preventDefault();
        }
      });
    }
  
    if (registerForm) {
      registerForm.addEventListener("submit", function (e) {
        const name = registerForm.querySelector('input[name="name"]').value.trim();
        const email = registerForm.querySelector('input[name="email"]').value.trim();
        const phone = registerForm.querySelector('input[name="phone"]').value.trim();
        const password = registerForm.querySelector('input[name="password"]').value;
        const birthdate = registerForm.querySelector('input[name="birthdate"]').value.trim();
  
        if (!name || !email || !phone || !password || !birthdate) {
          alert("Пожалуйста, заполните все поля.");
          e.preventDefault();
          return;
        }
  
        if (!validateEmail(email)) {
          alert("Введите корректный email.");
          e.preventDefault();
        } else if (password.length < 8) {
          alert("Пароль должен содержать минимум 8 символов.");
          e.preventDefault();
        }
      });
    }
  
    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }
  });
  