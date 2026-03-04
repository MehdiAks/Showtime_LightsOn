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

<section class="home-hero full-bleed">
    <div class="home-hero-overlay"></div>
    <div class="container home-hero-content">
        <span class="home-live-pill">● EN REPLAY</span>
        <p class="home-channel mb-2">LIGHTS ON · Culture nocturne</p>
        <h1 class="home-main-title">L'émission qui fait parler la nuit</h1>
        <p class="home-main-subtitle">Reportages, invités, performances et coulisses des lieux qui font vibrer la ville après le coucher du soleil.</p>
        <div class="home-hero-actions">
            <a class="btn btn-primary" href="episodes.php">Regarder le dernier épisode</a>
            <a class="btn btn-outline-light" href="about.php">Découvrir l'émission</a>
        </div>
    </div>
</section>

<div class="container py-5 home-main-surface">
    <section class="home-highlight-strip">
        <article class="home-highlight-card">
            <p class="home-highlight-label">Ce soir</p>
            <h2>Spéciale clubs underground</h2>
            <p>Yann et l'équipe plongent dans les lieux alternatifs qui redéfinissent la fête.</p>
            <a href="episodes/episode-03.php">Voir le programme</a>
        </article>
        <article class="home-highlight-card">
            <p class="home-highlight-label">Interview</p>
            <h2>Un DJ, une ville, une nuit</h2>
            <p>Conversation inédite avec une figure montante de la scène électro française.</p>
            <a href="episodes/episode-02.php">Lire l'interview</a>
        </article>
        <article class="home-highlight-card">
            <p class="home-highlight-label">Immersion</p>
            <h2>Dans les coulisses d'un after mythique</h2>
            <p>Une équipe, des caméras, et une nuit entière pour capter ce qui ne se voit jamais.</p>
            <a href="article.php?numArt=1">Regarder l'immersion</a>
        </article>
    </section>

    <section class="home-section">
        <div class="home-section-head">
            <h2>À la une</h2>
            <a href="episodes.php">Tout voir</a>
        </div>

        <div class="row g-4 home-feature-grid">
            <article class="col-lg-6">
                <a class="home-feature-card home-feature-card--main" href="episodes/episode-01.php">
                    <img src="<?php echo ROOT_URL . '/src/uploads/article/article-1.jpg'; ?>" alt="Épisode 01">
                    <div class="home-feature-content">
                        <p class="home-feature-tag">Épisode 01</p>
                        <h3>Première immersion dans les nuits urbaines</h3>
                        <p>Le pilote de LIGHTS ON suit la trajectoire d'une nuit, de l'ouverture à l'aube.</p>
                    </div>
                </a>
            </article>

            <div class="col-lg-6">
                <div class="home-side-list">
                    <a class="home-side-item" href="episodes/episode-02.php">
                        <img src="<?php echo ROOT_URL . '/src/uploads/article/article-38.jpg'; ?>" alt="Épisode 02">
                        <div>
                            <p class="home-feature-tag">Épisode 02</p>
                            <h3>Les nouvelles scènes culturelles nocturnes</h3>
                        </div>
                    </a>
                    <a class="home-side-item" href="episodes/episode-03.php">
                        <img src="<?php echo ROOT_URL . '/src/images/background/background-actualite.jpg'; ?>" alt="Épisode 03">
                        <div>
                            <p class="home-feature-tag">Épisode 03</p>
                            <h3>Quand la nuit devient un terrain d'expression</h3>
                        </div>
                    </a>
                    <a class="home-side-item" href="contact.php">
                        <img src="<?php echo ROOT_URL . '/src/images/background/background-article.jpg'; ?>" alt="Réseaux sociaux">
                        <div>
                            <p class="home-feature-tag">Communauté</p>
                            <h3>Suivez-nous pour les extraits et annonces exclusives</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'footer.php'; ?>
