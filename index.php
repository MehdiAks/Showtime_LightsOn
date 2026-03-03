<?php
// On charge la configuration globale du site (connexion DB, constantes, etc.).
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// On prépare la liste des feuilles de style spécifiques à cette page.
$pageStyles = [
    ROOT_URL . '/src/css/style.css',
];
$pageHasVideo = true;
$pageBackgroundVideo = ROOT_URL . '/src/video/Background_index.mp4';
$pageBackgroundPoster = ROOT_URL . '/src/images/background/background-index-1.webp';

// On inclut l'en-tête HTML (balises <head>, menu, etc.).
require_once 'header.php';

// On ouvre la connexion à la base de données.
sql_connect();

function resolve_article_image_url(?string $path, string $defaultImage): string
{
    if (!$path) {
        return $defaultImage;
    }

    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }

    if (strpos($path, '/src/uploads/') !== false) {
        $relative = substr($path, strpos($path, '/src/uploads/') + strlen('/src/uploads/'));
    } else {
        $relative = ltrim($path, '/');
    }

    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $relative;
    if (file_exists($filePath)) {
        return ROOT_URL . '/src/uploads/' . $relative;
    }

    return $defaultImage;
}

// On prépare la requête SQL pour récupérer 3 articles au hasard.
// - ORDER BY RAND() mélange aléatoirement les lignes.
// - LIMIT 3 garantit qu'on n'affiche jamais plus de 3 articles.

$articleStmt = $DB->prepare(
    'SELECT numArt, libTitrArt, libChapoArt, urlPhotArt, dtCreaArt
    FROM ARTICLE
    ORDER BY RAND()
    LIMIT 3'
);
// On exécute la requête préparée.
$articleStmt->execute();
// On récupère les résultats sous forme de tableau associatif.
$ba_bec_articles = $articleStmt->fetchAll(PDO::FETCH_ASSOC);

$nightHighlights = [
    [
        'badge' => 'Épisode',
        'title' => 'Voix de la nuit',
        'description' => 'Rencontre avec les artistes, DJ et collectifs qui font vibrer la scène locale.',
    ],
    [
        'badge' => 'Chronique',
        'title' => 'Carnet d’adresses',
        'description' => 'Une sélection de lieux, concepts et ambiances à découvrir après le coucher du soleil.',
    ],
];

$homeStats = [
    'segments' => 24,
    'places' => 18,
    'guests' => 41,
];
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
        50% { opacity: 0; }
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

    .home-program-section {
        background: linear-gradient(160deg, rgba(109, 40, 217, 0.92), rgba(31, 31, 43, 0.96));
        color: #ffffff;
        padding: 2rem;
        border-radius: 1.5rem;
    }

    .home-program-section h2 {
        font-size: 1.5rem;
    }

    .home-program-section p {
        font-size: 0.95rem;
    }

    .home-program-section .text-body-secondary {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    .home-program-section .card {
        background: rgba(15, 15, 22, 0.35);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .home-program-section .card .text-body-secondary {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .home-program-card h3 {
        font-size: 1rem;
    }

    .home-program-info {
        margin-bottom: 0;
        line-height: 1.2;
    }

    .home-program-section .btn-outline-light {
        color: #ffffff;
        border-color: #ffffff;
    }
</style>
<section class="home-hero full-bleed">
    <div class="home-hero-content text-center">
        <h2 class="fw-bold mb-0 typewriter-line" data-typewriter data-text="LIGHTS ON"></h2><br>
        <h3 class="fw-bold mb-0 typewriter-line" data-typewriter data-text="Mettre la lumière sur la nuit"></h3>
    </div>
</section>
<div class="container py-5 home-main-surface home-main-surface--hidden">
    <section class="home-section text-center">
        <h1 class="fw-bold mb-3">Lights On — The Night Club Show</h1>
        <p class="lead mb-4">
            Une émission culturelle immersive pour explorer la <strong>vie nocturne</strong> sous toutes ses formes : fête, culture,
            lieux, ambiance et personnages qui font vibrer la nuit.<br>
            Notre promesse éditoriale : créer un contraste fort entre obscurité et lumière, avec un univers visuel moderne,
            dynamique, lumineux et jamais kitsch.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a class="btn btn-primary" href="actualites.php">Découvrir les chroniques</a>
            <a class="btn btn-outline-secondary" href="contact.php">Proposer un lieu</a>
        </div>
    </section>

    <section class="home-section home-program-section">
        <h2 class="fw-bold mb-3 text-center">À l'affiche cette nuit</h2>
        <div class="row g-4">
            <?php foreach ($nightHighlights as $highlight): ?>
                <div class="col-12 col-lg-6">
                    <article class="card h-100 border-0 shadow-sm home-program-card">
                        <div class="card-body">
                            <span class="badge text-bg-secondary mb-2"><?php echo htmlspecialchars($highlight['badge']); ?></span>
                            <h3 class="h5 mb-2"><?php echo htmlspecialchars($highlight['title']); ?></h3>
                            <p class="home-program-info text-body-secondary mb-0"><?php echo htmlspecialchars($highlight['description']); ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="home-section">
        <h2 class="fw-bold mb-4">La nuit en chiffres</h2>
        <p class="text-body-secondary mb-4">
        Des indicateurs visuels pour suivre l'intensité de l'émission.
        </p>
        <div class="row g-4">
        <div class="col-12 col-md-4">
            <article class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <p class="text-uppercase text-body-secondary mb-2">Segments diffusés</p>
                <p
                class="display-6 fw-bold mb-0"
                data-counter
                data-target="<?php echo number_format((int) $homeStats['segments'], 0, ',', ' '); ?>"
                >
                0
                </p>
            </div>
            </article>
        </div>
        <div class="col-12 col-md-4">
            <article class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <p class="text-uppercase text-body-secondary mb-2">Lieux explorés</p>
                <p
                class="display-6 fw-bold mb-0"
                data-counter
                data-target="<?php echo number_format((int) $homeStats['places'], 0, ',', ' '); ?>"
                >
                0
                </p>
            </div>
            </article>
        </div>
        <div class="col-12 col-md-4">
            <article class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <p class="text-uppercase text-body-secondary mb-2">Invités mis en lumière</p>
                <p
                class="display-6 fw-bold mb-0"
                data-counter
                data-target="<?php echo number_format((int) $homeStats['guests'], 0, ',', ' '); ?>"
                >
                0
                </p>
            </div>
            </article>
        </div>
        </div>
    </section>
<script>
    const counters = document.querySelectorAll("[data-counter]");

    function parseNumber(value) {
        const normalized = value.replace(/\s/g, "").replace(",", ".");
        return Number.parseFloat(normalized);
    }

    function formatNumber(value, template) {
        const hasSpace = template.includes(" ");
        return value.toLocaleString("fr-FR", {
            maximumFractionDigits: 0,
            useGrouping: hasSpace,
        });
    }

    function animateCounter(element) {
        const targetText = element.dataset.target || "0";
        const rawTarget = parseNumber(targetText);
        if (!Number.isFinite(rawTarget) || rawTarget < 0) {
            element.textContent = targetText;
            return;
        }

        const target = Math.floor(rawTarget);
        if (target === 0) {
            element.textContent = formatNumber(0, targetText);
            return;
        }

        let current = 0;
        const durationMs = Number.parseInt(element.dataset.duration || "2500", 10);
        const safeDuration = Number.isFinite(durationMs) && durationMs > 0 ? durationMs : 1500;
        const minStepTime = 10;
        const normalStepTime = Math.max(minStepTime, Math.floor(safeDuration / Math.max(1, target)));
        const fastStepTime = Math.max(8, Math.floor(normalStepTime * 0.4));
        const midStepTime = Math.max(12, Math.floor(normalStepTime * 0.7));
        const halfTarget = Math.floor(target * 0.5);
        const threeQuarterTarget = Math.floor(target * 0.75);

        const tick = () => {
            let step = 1;
            let delay = normalStepTime;

            if (current < halfTarget) {
                step = 10;
                delay = fastStepTime;
            } else if (current < threeQuarterTarget) {
                step = 2;
                delay = midStepTime;
            }

            current = Math.min(target, current + step);
            element.textContent = formatNumber(current, targetText);

            if (current < target) {
                setTimeout(tick, delay);
            }
        };

        tick();
    }

    function triggerCounter(element) {
        if (element.dataset.started === "true") {
            return;
        }
        element.dataset.started = "true";
        setTimeout(() => animateCounter(element), 1000);
    }

    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        triggerCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.4 }
        );

        counters.forEach((counter) => observer.observe(counter));
    } else {
        window.addEventListener("load", () => {
            counters.forEach((counter) => triggerCounter(counter));
        });
    }
</script>

    <section aria-label="Dernières actualités" class="home-articles">
        <h2 class="fw-bold mb-4">Dernières chroniques nocturnes</h2>
            <p class="text-body-secondary mb-4">Interviews, portraits, lieux iconiques et récits immersifs de la nuit.</p>
        <!-- Si on a au moins un article récupéré, on les affiche. -->
        <?php if (!empty($ba_bec_articles)): ?>
            <div class="home-articles-container">
                <div class="row g-4">
                    <!-- On parcourt les 3 articles aléatoires récupérés depuis la base. -->
                    <?php foreach ($ba_bec_articles as $ba_bec_article): ?>
                        <?php
                        // 1) On détermine l'image à afficher :
                        //    - si l'article a une image, on utilise celle-ci
                        //    - sinon on utilise l'image par défaut.
                        $defaultImagePath = ROOT_URL . '/src/images/image-defaut.jpeg';
                        $ba_bec_imagePath = resolve_article_image_url($ba_bec_article['urlPhotArt'] ?? null, $defaultImagePath);
                        // 2) On récupère le chapo (texte d'accroche) ou une chaîne vide si absent.
                        $chapo = $ba_bec_article['libChapoArt'] ?? '';
                        // 3) On fixe la longueur max de l'extrait affiché.
                        $maxLength = 160;
                        // 4) On tronque le chapo proprement (multibyte si disponible).
                        $excerptBase = function_exists('mb_substr') ? mb_substr($chapo, 0, $maxLength) : substr($chapo, 0, $maxLength);
                        // 5) On calcule la longueur réelle du chapo.
                        $chapoLength = function_exists('mb_strlen') ? mb_strlen($chapo) : strlen($chapo);
                        // 6) On ajoute "..." seulement si le chapo dépassait la limite.
                        $excerpt = $excerptBase . ($chapoLength > $maxLength ? '...' : '');
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="home-article-card" data-hover-card>
                                <img src="<?php echo $ba_bec_imagePath; ?>"
                                    class="home-article-image mb-3"
                                    alt="<?php echo htmlspecialchars($ba_bec_article['libTitrArt']); ?>">
                                <h3 class="h5 fw-bold mb-2"><?php echo htmlspecialchars($ba_bec_article['libTitrArt']); ?></h3>
                                <p class="fst-italic"><?php echo htmlspecialchars($excerpt); ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <small class="text-body-secondary">
                                        <?php echo htmlspecialchars($ba_bec_article['dtCreaArt']); ?>
                                    </small>
                                    <a href="<?php echo ROOT_URL . '/article.php?numArt=' . (int) $ba_bec_article['numArt']; ?>" class="home-article-link">Lire la suite</a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4">
                    <a class="btn btn-primary" href="actualites.php">Voir toutes les chroniques</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Si aucun article n'est disponible, on affiche un message d'information. -->
            <div class="alert alert-info mb-0" role="status">
                Aucune actualité disponible pour le moment.
            </div>
        <?php endif; ?>
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
<script src="<?php echo ROOT_URL . '/src/js/home-articles-hover.js'; ?>"></script>
<script src="<?php echo ROOT_URL . '/src/js/home-scroll-reveal.js'; ?>"></script>
<?php require_once 'footer.php'; ?>

<p></p>
