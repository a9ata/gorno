document.querySelectorAll('.favorite-btn').forEach(button => {
    button.addEventListener('click', function () {
        const productId = this.dataset.id;

        fetch('/forms/favorite_toggle.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}`
        })
        .then(res => res.text())
        .then(res => {
            if (res === 'added') {
                this.querySelector('img').src = '<?= ICON_URL ?>favorite-active.svg';
            } else if (res === 'removed') {
                this.querySelector('img').src = '<?= ICON_URL ?>favorite-default.svg';
            }
        });
    });
});