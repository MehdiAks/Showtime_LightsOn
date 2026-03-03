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
        <h1 class="episode-detail__title">Épisode 03 – Derrière les lumières</h1>

        <div class="ratio ratio-16x9 episode-detail__video">
            <iframe src="https://www.youtube.com/embed/tgbNymZ7vqY" title="Épisode 03" allowfullscreen></iframe>
        </div>

        <p>
            Le troisième épisode ouvre les portes des coulisses et suit les équipes avant, pendant et après l'événement.
            On y voit la précision des préparatifs techniques, la gestion des imprévus et la coordination entre métiers complémentaires.
            Régisseurs, techniciens son et lumière, personnels d'accueil et équipes de sécurité racontent leur quotidien.
            Leur travail, souvent discret, conditionne pourtant la qualité de l'expérience vécue par le public.
            Le film s'attarde sur les décisions prises en temps réel pour maintenir fluidité et confort.
            Il aborde aussi la fatigue, la responsabilité collective et les bonnes pratiques de prévention.
            À travers ces regards, l'épisode valorise l'expertise et le professionnalisme des acteurs de l'ombre.
            Une immersion concrète pour comprendre ce qui se joue réellement derrière les projecteurs.
        </p>

        <section class="episode-detail__section">
            <h2>Invités</h2>
            <ul class="episode-detail__list">
                <li>Lina B., régisseuse générale.</li>
                <li>Thomas E., ingénieur son.</li>
                <li>Nora P., responsable sécurité et accueil.</li>
            </ul>
        </section>

        <section class="episode-detail__section">
            <h2>Thématiques abordées</h2>
            <ul class="episode-detail__list">
                <li>Organisation technique d'un tournage nocturne.</li>
                <li>Gestion des risques et coordination terrain.</li>
                <li>Valorisation des métiers de l'ombre.</li>
            </ul>
        </section>

        <section class="episode-detail__section">
            <h2>Crédits</h2>
            <p>Réalisation : Équipe Lights On · Post-production : Nuit Claire Studio · Remerciements : Partenaires culture & terrain.</p>
        </section>

        <a href="<?php echo ROOT_URL; ?>/episodes.php" class="btn btn-episode mt-3">Retour aux épisodes</a>
    </article>
</main>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
