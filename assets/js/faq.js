document.addEventListener('DOMContentLoaded', () => {
    // Раскрытие категорий
    document.querySelectorAll('.category-toggle').forEach(btn => {
      btn.addEventListener('click', () => {
        btn.classList.toggle('active');
        const content = btn.nextElementSibling;
        content.style.display = content.style.display === 'block' ? 'none' : 'block';
      });
    });
  });
  