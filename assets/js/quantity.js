document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', () => {
            const cartId = button.dataset.id;
            const action = button.dataset.action;

            fetch('/includes/update_quantity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${cartId}&action=${action}`
            }).then(() => {
                // Обновим страницу после обновления
                window.location.reload();
            });
        });
    });
});