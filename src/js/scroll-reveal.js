(() => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const items = document.querySelectorAll('.scroll-reveal');

    if (!items.length) {
        return;
    }

    if (reducedMotion || !('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }
                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            });
        },
        {
            threshold: 0.15,
            rootMargin: '0px 0px -8% 0px',
        }
    );

    items.forEach((item) => observer.observe(item));
})();
