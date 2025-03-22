function openLoginModal() {
    document.getElementById("loginModal").classList.remove("hidden");
}

function closeLoginModal() {
    document.getElementById("loginModal").classList.add("hidden");
}

function switchForm(target) {
    document.getElementById("loginForm").classList.toggle("hidden", target === 'register');
    document.getElementById("registerForm").classList.toggle("hidden", target === 'login');
}


document.addEventListener("DOMContentLoaded", function () {
    Inputmask("+7 (999) 999-99-99").mask(document.getElementById("phoneInput"));
    Inputmask("9999.99.99").mask(document.getElementById("birthdateInput"));
});
