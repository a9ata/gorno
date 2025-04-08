function openProductModal(productId) {
    fetch(`/forms/get_product_details.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById("productModal");
            const container = document.getElementById("productDetails");

            let colors = [...new Set(data.attributes.map(attr => attr.color))];
            let sizes = [...new Set(data.attributes.map(attr => attr.size))];

            const additionalImages = data.images
                .filter(img => img.is_main == 0)
                .map(img => `<img src="${img.image_url}" alt="Доп фото" class="thumb">`)
                .join('');

            container.innerHTML = `
                <div class="modal-layout">
                    <div>
                        <img src="${data.main_image}" alt="Фото товара" class="main-image" />
                        <p>Дополнительные фото</p>
                        <div class="additional-photos">${additionalImages}</div>
                    </div>
                    <div class="about-product">
                        <form method="post" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="${data.id}">
                            <div>
                                <label>Цвет:</label>
                                ${colors.map(color => `
                                    <label class="radio-wrapper"><input type="radio" name="color" value="${color}" required> ${color}</label>
                                `).join('')}
                            </div>
                            
                            <div>
                                <label>Размер:</label>
                                ${sizes.map(size => `
                                    <label class="radio-wrapper"><input type="radio" name="size" value="${size}" required> ${size}</label>
                                `).join('')}
                            </div>
                            <div class="title">
                                <button class="favorite-btn" data-id="<?= $product['id'] ?>">
                                    <img src="<?= $icon ?>" alt="В избранное">
                                </button>
                                <h2>${data.name}</h2>
                                <p>${data.subcategory}</p>
                                <p><strong>${data.price} ₽</strong></p>
                                <button type="submit">Добавить в корзину</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

            modal.classList.remove("hidden");
        });
}

document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("productModal");
    const modalContent = modal.querySelector('.modal-content');

    
    modal.addEventListener('click', (e) => {
        if (!modalContent.contains(e.target)) {
            closeProductModal();
        }
    });

    
    const closeBtn = modal.querySelector('.close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            closeProductModal();
        });
    }
});

function closeProductModal() {
    const modal = document.getElementById("productModal");
    modal.classList.add("hidden");
}