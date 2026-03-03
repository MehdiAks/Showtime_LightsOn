<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/style.css',
];
$pageHasVideo = true;
$pageBackgroundVideo = ROOT_URL . '/src/video/Background_index.mp4';
$pageBackgroundPoster = ROOT_URL . '/src/images/background/background-index-1.webp';

require_once 'header.php';
?>

<style>
    .typewriter-line {
        position: relative;
        display: inline-block;
        white-space: nowrap;
        max-width: 100%;
    }

    .typewriter-line::after {
        content: "";
        display: inline-block;
        width: 2px;
        height: 1em;
        background: currentColor;
        margin-left: 6px;
        vertical-align: -0.1em;
        animation: caret-blink 0.9s steps(1) infinite;
    }

    .typewriter-line.is-done::after {
        opacity: 0;
        animation: none;
    }

    @keyframes caret-blink {
        50% {
            opacity: 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .typewriter-line::after {
            animation: none;
        }
    }

    @media (max-width: 576px) {
        .typewriter-line {
            white-space: normal;
            overflow-wrap: anywhere;
        }
    }
</style>

<section class="home-hero full-bleed">
    <div class="home-hero-content text-center">
        <h1 class="fw-bold mb-2 typewriter-line" data-typewriter data-text="LIGHTS ON"></h1>
        <p class="lead mb-4 typewriter-line" data-typewriter data-text="Mettre la lumière sur la nuit."></p>
        <a class="btn btn-primary" href="episodes.php">▶ Voir le dernier épisode</a>
    </div>
</section>

<div class="container py-5 home-main-surface home-main-surface--hidden">
    <section class="home-section text-center">
        <h2 class="fw-bold mb-3">Concept</h2>
        <p class="lead mb-0">
            Une émission culturelle immersive pour explorer la <strong>vie nocturne</strong> sous toutes ses formes : fête, culture,
            lieux, ambiance et personnages qui font vibrer la nuit.
        </p>
    </section>

    <section class="home-section text-center">
        <h2 class="fw-bold mb-3">Épisode à la une</h2>
        <h3 class="h4 mb-3">Épisode 01</h3>
        <p class="mb-4">
            Plongée au cœur d'une nuit urbaine entre rencontres, musique et lieux emblématiques qui façonnent l'identité de LIGHTS ON.
        </p>
        <a class="btn btn-outline-primary" href="article.php?numArt=1">Voir le détail de l'épisode 01</a>
    </section>

    <section class="home-section text-center">
        <h2 class="fw-bold mb-3">Prochaine diffusion</h2>
        <p class="mb-0">Nouveau numéro à venir très bientôt. Restez connectés pour découvrir la prochaine immersion nocturne.</p>
    </section>

    <section class="home-section text-center">
        <h2 class="fw-bold mb-3">Réseaux</h2>
        <p class="mb-4">Suivez LIGHTS ON sur nos réseaux pour ne rien manquer des épisodes, coulisses et annonces exclusives.</p>
        <a class="btn btn-outline-secondary" href="contact.php">Accéder à nos réseaux</a>
    </section>
</div>

<script>
    (function () {
        const lines = Array.from(document.querySelectorAll("[data-typewriter]"));
        if (!lines.length) {
            return;
        }

        const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        const speedMs = 70;
        const lineDelayMs = 350;

        const typeLine = (element) =>
            new Promise((resolve) => {
                const text = element.dataset.text || "";
                if (prefersReducedMotion) {
                    element.textContent = text;
                    element.classList.add("is-done");
                    resolve();
                    return;
                }

                let index = 0;
                element.textContent = "";
                element.classList.remove("is-done");

                const tick = () => {
                    element.textContent = text.slice(0, index);
                    if (index >= text.length) {
                        element.classList.add("is-done");
                        setTimeout(resolve, lineDelayMs);
                        return;
                    }
                    index += 1;
                    setTimeout(tick, speedMs);
                };

                tick();
            });

        (async () => {
            for (const line of lines) {
                await typeLine(line);
            }
        })();
    })();
</script>
<script src="<?php echo ROOT_URL . '/src/js/home-scroll-reveal.js'; ?>"></script>

<?php require_once 'footer.php'; ?>
