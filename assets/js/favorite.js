document.querySelectorAll('.favorite-btn').forEach(button => {
    button.addEventListener('click', function () {
        const productId = this.dataset.id;
        const icon = this.querySelector('img');

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
                icon.src = '/assets/icons/favorite-active.svg';
            } else if (res === 'removed') {
                icon.src = '/assets/icons/favorite-default.svg';
            }
        });
    });
});