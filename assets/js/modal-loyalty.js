function openModal() {
    document.getElementById('loyaltyModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('loyaltyModal').style.display = 'none';
}

window.addEventListener('click', (e) => {
    const modal = document.getElementById('loyaltyModal');
    if (e.target === modal) {
        closeModal();
    }
});
