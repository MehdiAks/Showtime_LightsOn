<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/style.css',
    ROOT_URL . '/src/css/actualites.css',
    ROOT_URL . '/src/css/episodes.css',
];

require_once 'header.php';

$episodes = [
    [
        'title' => 'Épisode 01 – Quand la nuit devient culture',
        'summary' => "Un voyage dans les lieux où la nuit devient un espace de création vivante.\nDes artistes, programmateurs et passionnés racontent leurs inspirations.\nOn y explore la manière dont les scènes nocturnes façonnent les identités urbaines.\nUn épisode pour comprendre comment la culture s'exprime après le coucher du soleil.",
        'link' => ROOT_URL . '/episodes/episode-01.php',
    ],
    [
        'title' => 'Épisode 02 – Les visages de la nuit',
        'summary' => "Au fil de la nuit, des profils variés se croisent et créent un récit collectif.\nTravailleurs, artistes et publics racontent leurs réalités et leurs engagements.\nL'épisode met en lumière des parcours souvent invisibles en journée.\nUne immersion humaine pour découvrir la nuit par celles et ceux qui la vivent.",
        'link' => ROOT_URL . '/episodes/episode-02.php',
    ],
    [
        'title' => 'Épisode 03 – Derrière les lumières',
        'summary' => "Cet épisode dévoile les coulisses d'une production nocturne exigeante.\nRégie, sécurité, coordination : chaque équipe raconte son rôle clé.\nOn découvre les défis techniques et logistiques d'un événement réussi.\nUn regard authentique sur celles et ceux qui rendent la nuit possible.",
        'link' => ROOT_URL . '/episodes/episode-03.php',
    ],
];
?>

<main class="container py-5">
    <section class="news-summary text-center mb-4">
        <h1 class="news-summary__title">Épisodes</h1>
        <p class="news-summary__text mx-auto">
            Découvrez les trois épisodes de Lights On à travers une sélection éditoriale dédiée :
            immersion, rencontres et coulisses du monde nocturne.
        </p>
    </section>

    <section class="news-grid" aria-label="Bibliothèque des épisodes">
        <div class="row g-4">
            <?php foreach ($episodes as $episode): ?>
                <div class="col-12 col-lg-4">
                    <article class="card news-card episode-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h2 class="card-title news-card__title"><?php echo htmlspecialchars($episode['title']); ?></h2>
                            <p class="card-text news-card__excerpt episode-card__summary"><?php echo nl2br(htmlspecialchars($episode['summary'])); ?></p>
                            <a href="<?php echo $episode['link']; ?>" class="btn btn-episode mt-auto">Voir la page détail</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
