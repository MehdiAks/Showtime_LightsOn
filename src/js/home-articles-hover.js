document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('[data-hover-card]');
    cards.forEach((card) => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('is-lifted');
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('is-lifted');
        });
    });
});
