    </main>
    <footer class="site-footer border-top py-5" id="Contact">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-lg-5">
                    <a class="btn-contact-footer" href="<?php echo ROOT_URL . '/contact.php'; ?>">Contactez-nous :</a>
                    <p class="mb-1">
                        <a href="mailto:secretariat@bec-bordeaux?subject=Demande%20de%20contact%20depuis%20le%20site%20BEC%20Bordeaux">
                            secretariat@bec-bordeaux
                        </a>
                    </p>
                    
                    <p class="mb-3"><a href="tel:+33671942380">Tel : 06 71 94 23 80</a><br>
                    <a href="tel:+33556918350">Tel : 05 56 91 83 50</a> </p>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <a href="https://www.instagram.com/becbasket/?hl=fr" class="social-icon">
                            <img src="<?php echo ROOT_URL . '/src/images/logo/logo-reseaux-sociaux/instagram.png'; ?>" alt="Instagram">
                        </a>
                        <a href="https://www.facebook.com/becofficiel/?locale=fr_FR" class="social-icon">
                            <img src="<?php echo ROOT_URL . '/src/images/logo/logo-reseaux-sociaux/facebook.png'; ?>" alt="Facebook">
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ratio ratio-4x3 rounded-4 overflow-hidden shadow-sm">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387.73473039224695!2d-0.5620506434721906!3d44.827972032339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd55264b1f8d16e7%3A0x60bae14b3c5cbd38!2s8%20Cr%20Barbey%2C%2033800%20Bordeaux!5e0!3m2!1sfr!2sfr!4v1770375688862!5m2!1sfr!2sfr""
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="small fst-italic mb-0">
                        <a href="<?php echo ROOT_URL . '/infoleg/cgu.php'; ?>">Conditions d’utilisation</a>
                        -
                        <a href="<?php echo ROOT_URL . '/infoleg/mentionleg.php'; ?>">Mentions légales</a>
                        -
                        <a href="<?php echo ROOT_URL . '/infoleg/rgpd.php'; ?>">RGPD</a>
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-end">
                    <a class="footer-credit-link" href="<?php echo ROOT_URL . '/about.php'; ?>">Designed by Les égarés</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.querySelectorAll('.header-submenu').forEach((submenu) => {
            const toggle = submenu.querySelector('.submenu-toggle');
            const container = submenu.closest('.header-nav, .header-burger-panel') || document;

            const closeSubmenu = () => {
                submenu.classList.remove('is-open');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            };

            if (!toggle) {
                return;
            }

            toggle.addEventListener('click', (event) => {
                event.preventDefault();
                const isOpen = submenu.classList.contains('is-open');

                container.querySelectorAll('.header-submenu.is-open').forEach((openSubmenu) => {
                    if (openSubmenu !== submenu) {
                        const openToggle = openSubmenu.querySelector('.submenu-toggle');
                        openSubmenu.classList.remove('is-open');
                        if (openToggle) {
                            openToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                if (isOpen) {
                    closeSubmenu();
                } else {
                    submenu.classList.add('is-open');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });

            document.addEventListener('click', (event) => {
                if (!submenu.contains(event.target)) {
                    closeSubmenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeSubmenu();
                }
            });
        });
    </script>
    <script>
        const buttonHoverTargets = document.querySelectorAll(
            'button:not(.btn-check), input[type="button"], input[type="submit"], input[type="reset"], .btn, .btn-contact-footer, .btn-more, .btn_envoyer, .bouton'
        );

        buttonHoverTargets.forEach((button) => {
            if (
                button.disabled ||
                button.classList.contains('disabled') ||
                button.getAttribute('aria-disabled') === 'true'
            ) {
                return;
            }

            const lift = () => {
                if (
                    button.disabled ||
                    button.classList.contains('disabled') ||
                    button.getAttribute('aria-disabled') === 'true'
                ) {
                    return;
                }
                button.classList.add('is-lifted');
            };

            const reset = () => {
                button.classList.remove('is-lifted');
            };

            button.addEventListener('pointerenter', lift);
            button.addEventListener('pointerleave', reset);
            button.addEventListener('focus', lift);
            button.addEventListener('blur', reset);
        });
    </script>
    <script>
        (function () {
            if (window.matchMedia('(pointer: coarse)').matches) {
                return;
            }

            const glow = document.createElement('div');
            glow.className = 'cursor-glow';
            document.body.appendChild(glow);

            let currentX = 0;
            let currentY = 0;
            let targetX = 0;
            let targetY = 0;
            const offset = { x: 0, y: 0 };
            let isVisible = false;

            const update = () => {
                currentX += (targetX - currentX) * 0.12;
                currentY += (targetY - currentY) * 0.12;
                glow.style.transform = `translate3d(${currentX}px, ${currentY}px, 0) translate3d(-50%, -50%, 0)`;
                requestAnimationFrame(update);
            };

            const handleMove = (event) => {
                targetX = event.clientX + offset.x;
                targetY = event.clientY + offset.y;
                if (!isVisible) {
                    glow.style.opacity = '1';
                    isVisible = true;
                }
            };

            document.addEventListener('pointermove', handleMove);
            document.addEventListener('pointerleave', () => {
                glow.style.opacity = '0';
                isVisible = false;
            });

            update();
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
