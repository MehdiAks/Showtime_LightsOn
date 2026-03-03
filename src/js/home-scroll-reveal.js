const homeMainSurface = document.querySelector('.home-main-surface');

if (homeMainSurface) {
    const revealSurface = () => {
        homeMainSurface.classList.remove('home-main-surface--hidden');
        window.removeEventListener('scroll', handleScroll);
    };

    const handleScroll = () => {
        if (window.scrollY > 0) {
            revealSurface();
        }
    };

    if (window.scrollY > 0) {
        revealSurface();
    } else {
        window.addEventListener('scroll', handleScroll, { passive: true });
    }
}
