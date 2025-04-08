document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('loyaltyModal');
    const closeBtn = document.querySelector('.modal-loyalty-close');
    const openBtns = document.querySelectorAll('.open-loyalty-modal');

    openBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
    });

    closeBtn?.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});