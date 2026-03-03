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
        <h1 class="episode-detail__title">Épisode 02 – Les visages de la nuit</h1>

        <div class="ratio ratio-16x9 episode-detail__video">
            <iframe src="https://www.youtube.com/embed/ysz5S6PUM-U" title="Épisode 02" allowfullscreen></iframe>
        </div>

        <p>
            Ce deuxième épisode donne la parole à celles et ceux qui incarnent la nuit dans toute sa pluralité.
            On y rencontre des professionnels, des bénévoles, des artistes et des habitants qui partagent un même territoire nocturne.
            Chacun raconte son rythme, ses contraintes et ses raisons d'aimer cette temporalité particulière.
            Les témoignages montrent comment la nuit peut être un espace d'opportunités, d'émancipation mais aussi de fragilités.
            Le documentaire révèle des solidarités discrètes, indispensables à l'équilibre des quartiers et des événements.
            Il met en lumière la cohabitation de mondes qui se croisent rarement en journée.
            Au-delà des stéréotypes, l'épisode révèle une mosaïque de parcours, de métiers et d'engagements.
            Un portrait choral qui redonne un visage humain aux dynamiques nocturnes.
        </p>

        <section class="episode-detail__section">
            <h2>Invités</h2>
            <ul class="episode-detail__list">
                <li>Sofiane M., agent de prévention nocturne.</li>
                <li>Julie T., cheffe de projet événementiel.</li>
                <li>Marco L., chauffeur et observateur des mobilités de nuit.</li>
            </ul>
        </section>

        <section class="episode-detail__section">
            <h2>Thématiques abordées</h2>
            <ul class="episode-detail__list">
                <li>Travail de nuit et reconnaissance des métiers.</li>
                <li>Mobilités et sécurité des publics.</li>
                <li>Représentations sociales de la nuit.</li>
            </ul>
        </section>

        <section class="episode-detail__section">
            <h2>Crédits</h2>
            <p>Réalisation : Équipe Lights On · Image : Collectif Noctambule · Coordination : Atelier Territoires Nocturnes.</p>
        </section>

        <a href="<?php echo ROOT_URL; ?>/episodes.php" class="btn btn-episode mt-3">Retour aux épisodes</a>
    </article>
</main>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
