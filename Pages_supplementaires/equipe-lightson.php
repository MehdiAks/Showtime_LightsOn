<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/equipe-lightson.css'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$teamProfiles = [
    [
        'role' => 'Présentateur',
        'name' => 'Nolan Vasseur',
        'photo' => ROOT_URL . '/src/images/notre-histoire/notre-histoire-5.webp',
        'bio' => "Visage de l'émission, Nolan anime les échanges avec énergie et bienveillance.\nIl relie les sujets de plateau aux reportages terrain pour garder un fil éditorial clair.\nSa mission : faire dialoguer les univers de la nuit avec authenticité et rythme.",
    ],
    [
        'role' => 'Réalisateur',
        'name' => 'Inès Moreau',
        'photo' => ROOT_URL . '/src/images/notre-histoire/notre-histoire-3.webp',
        'bio' => "Inès pilote la mise en scène globale de chaque épisode, du conducteur au direct.\nElle coordonne la régie, les caméras et les transitions pour préserver la fluidité du récit.\nSon regard garantit une émission lisible, vivante et toujours immersive.",
    ],
    [
        'role' => 'Responsable DA',
        'name' => 'Malo Kermorvan',
        'photo' => ROOT_URL . '/src/images/notre-histoire/notre-histoire-1.webp',
        'bio' => "Malo construit l'identité visuelle de Lights On : décors, couleurs, typographies et habillages.\nIl transforme les références culturelles nocturnes en univers graphique cohérent.\nSon travail donne à l'émission sa signature pop, élégante et urbaine.",
    ],
    [
        'role' => 'Monteur',
        'name' => 'Sarah Benali',
        'photo' => ROOT_URL . '/src/images/notre-histoire/notre-histoire-2.webp',
        'bio' => "Sarah assemble les séquences, calibre le rythme et affine la narration en post-production.\nElle harmonise images, musiques et interventions pour renforcer l'impact des contenus.\nGrâce à son montage, chaque épisode gagne en tension et en clarté.",
    ],
    [
        'role' => 'Chroniqueur',
        'name' => 'Yanis Ribeiro',
        'photo' => ROOT_URL . '/src/images/notre-histoire/notre-histoire-4.webp',
        'bio' => "Yanis apporte une lecture terrain des tendances nocturnes, entre culture, musique et société.\nSes chroniques donnent du contexte, du recul et une touche d'analyse à chaque thème.\nIl incarne le lien entre actualité locale et dynamiques nationales.",
    ],
];
?>

<main class="team-page container py-5">
    <section class="team-hero text-center">
        <p class="team-hero__eyebrow">L'Équipe</p>
        <h1>Les visages de Lights On</h1>
        <p>
            Une équipe complémentaire qui imagine, produit et fait vivre l'émission à chaque numéro.
        </p>
    </section>

    <section class="team-grid" aria-label="Profils de l'équipe Lights On">
        <?php foreach ($teamProfiles as $profile): ?>
            <article class="team-card">
                <img src="<?php echo htmlspecialchars($profile['photo']); ?>" alt="<?php echo htmlspecialchars($profile['role']); ?> - <?php echo htmlspecialchars($profile['name']); ?>">
                <div class="team-card__content">
                    <p class="team-card__role"><?php echo htmlspecialchars($profile['role']); ?></p>
                    <h2><?php echo htmlspecialchars($profile['name']); ?></h2>
                    <?php foreach (explode("\n", $profile['bio']) as $line): ?>
                        <p><?php echo htmlspecialchars($line); ?></p>
                    <?php endforeach; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
