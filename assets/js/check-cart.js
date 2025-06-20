document.addEventListener("DOMContentLoaded", function () {
    document.querySelector('.pay-btn')?.addEventListener('click', function (e) {
        e.preventDefault(); // Предотвращаем обычный submit

        const checkboxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
        if (checkboxes.length === 0) {
            const error = document.getElementById('selectionError');
            error.textContent = 'Пожалуйста, выберите товары для оплаты.';
            error.style.display = 'block';
            return;
        }

        const selectedIds = Array.from(checkboxes).map(cb => cb.value);

        fetch("/includes/checkout.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ selected_items: selectedIds })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                window.location.href = "/payment";
            } else {
                const error = document.getElementById('selectionError');
                error.textContent = res.message || "Ошибка.";
                error.style.display = 'block';
            }
        });
    });
});
