document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".trash-btn").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const cartId = this.dataset.id;

            if (!confirm("Вы точно хотите удалить товар из корзины?")) return;

            fetch("/includes/remove_from_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `cart_id=${cartId}`
            })
            .then(res => res.text())
            .then(res => {
                if (res.trim() === "success") {
                    // Удалить строку из DOM
                    this.closest("tr").remove();
                } else {
                    alert("Ошибка при удалении товара.");
                }
            })
            .catch(() => alert("Ошибка сети."));
        });
    });
});
