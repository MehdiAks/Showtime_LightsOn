<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/style.css',
    ROOT_URL . '/src/css/actualites.css',
    ROOT_URL . '/src/css/episodes.css',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<main class="container py-5">
    <article class="episode-detail">
        <h1 class="episode-detail__title">Épisode 01 – Quand la nuit devient culture</h1>

        <div class="ratio ratio-16x9 episode-detail__video">
            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Épisode 01" allowfullscreen></iframe>
        </div>

        <p>
            Dans ce premier épisode, nous suivons les trajectoires de celles et ceux qui font vibrer la nuit culturelle.
            Entre scènes alternatives, lieux patrimoniaux et collectifs émergents, la création se déploie dans toute sa diversité.
            Les intervenants racontent comment la nuit libère la parole, favorise l'expérimentation et provoque des rencontres inédites.
            On comprend aussi les enjeux de transmission, de sécurité et d'accessibilité pour que la culture reste ouverte à tous.
            La caméra capte les coulisses d'événements où chaque détail compte, de la programmation à l'accueil du public.
            Ce récit met en avant la richesse d'une vie artistique souvent invisible le jour.
            L'épisode interroge enfin la place des institutions, des associations et des indépendants dans cet écosystème nocturne.
            Il dresse le portrait d'une nuit vivante, créative et profondément collective.
        </p>

        <section class="episode-detail__section">
            <h2>Invités</h2>
            <ul class="episode-detail__list">
                <li>Amel K., directrice artistique d'un festival urbain.</li>
                <li>Yanis R., musicien et programmateur indépendant.</li>
                <li>Clara D., médiatrice culturelle de nuit.</li>
            </ul>
        </section>

        <section class="episode-detail__section">
            <h2>Thématiques abordées</h2>
            <ul class="episode-detail__list">
                <li>Création artistique nocturne.</li>
                <li>Accès à la culture et inclusion des publics.</li>
                <li>Coopérations entre institutions et collectifs.</li>
            </ul>
        </section>

        <section class="episode-detail__section">
            <h2>Crédits</h2>
            <p>Réalisation : Équipe Lights On · Montage : Studio 26 · Production éditoriale : Rédaction Nightscope.</p>
        </section>

        <a href="<?php echo ROOT_URL; ?>/episodes.php" class="btn btn-episode mt-3">Retour aux épisodes</a>
    </article>
</main>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
