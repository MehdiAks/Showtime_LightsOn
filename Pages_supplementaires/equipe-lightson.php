<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/equipe-lightson.css'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$defaultPhoto = ROOT_URL . '/src/images/logo/team-default.png';
$teamProfiles = [
    [
        'role' => 'Directeur de production • Réalisateur • Monteur',
        'name' => 'Mehdi AFANKOUS',
        'photo' => $defaultPhoto,
        'bio' => "Mehdi occupe un rôle central dans la production de l’émission. En tant que directeur de production, il coordonne l’organisation générale du projet et veille au bon déroulement des différentes étapes de réalisation. Il intervient également comme réalisateur sur certains contenus éditoriaux et participe activement à la construction narrative de l’émission. Sur le plateau, il contribue aussi à la post-production en assurant le montage, garantissant la cohérence et la fluidité du rendu final.",
    ],
    [
        'role' => 'Assistante réalisation • Réalisation • Cadreuse',
        'name' => 'Louise THOUMAZET',
        'photo' => $defaultPhoto,
        'bio' => "Louise participe activement à la mise en place technique et visuelle de l’émission. Elle intervient comme assistante à la réalisation sur le plateau, aidant à coordonner les actions pendant le tournage. Elle prend également part à la réalisation de contenus, tout en contribuant à la captation d’images en tant que cadreuse, participant ainsi directement à la qualité visuelle des images.",
    ],
    [
        'role' => 'Réalisation plateau • Rédaction',
        'name' => 'Milan TRAPY',
        'photo' => $defaultPhoto,
        'bio' => "Milan occupe un rôle clé dans la réalisation du plateau, en participant à la direction des prises de vue et à la gestion du déroulement de l’émission. Il contribue aussi à la rédaction des contenus éditoriaux, en participant à la construction des textes et des idées développées dans l’émission.",
    ],
    [
        'role' => 'Graphiste • Monteur • Ingénieur du son • Cadreur',
        'name' => 'Djuste KABAMBA',
        'photo' => $defaultPhoto,
        'bio' => "Djuste intervient sur plusieurs aspects techniques et créatifs. En tant que graphiste, il participe à l’identité visuelle de l’émission. Il travaille également sur le montage vidéo, contribuant à la construction narrative des contenus. Sur le plan technique, il assure aussi des fonctions d’ingénieur du son et participe à la captation d’images en tant que cadreur sur le plateau.",
    ],
    [
        'role' => 'Monteur • Ingénieur du son • Création sonore',
        'name' => 'Romain BEZOMBES',
        'photo' => $defaultPhoto,
        'bio' => "Romain est impliqué dans la post-production audiovisuelle, notamment à travers le montage de contenus. Il intervient également comme ingénieur du son, assurant la qualité sonore des enregistrements. En parallèle, il contribue à l’identité sonore de l’émission en créant la musique, les jingles et le sound design.",
    ],
    [
        'role' => 'Rédactrice en chef • Communication • Cadreuse',
        'name' => 'Thaïs LACOME',
        'photo' => $defaultPhoto,
        'bio' => "Thaïs joue un rôle éditorial majeur dans l’émission. En tant que rédactrice en chef, elle supervise la ligne éditoriale et la cohérence des contenus. Elle s’occupe également de la communication du projet, notamment sur les réseaux sociaux et la diffusion. Elle participe aussi aux tournages en tant que cadreuse, contribuant à la captation des images.",
    ],
    [
        'role' => 'Présentateur • Création sonore • Ingénieur du son',
        'name' => 'Cydji GOGAN',
        'photo' => $defaultPhoto,
        'bio' => "Cydji est le présentateur de l’émission, incarnant le lien entre les différents segments et intervenants. Il participe également à la création de l’identité sonore, notamment à travers la musique et le sound design. Sur le plan technique, il intervient aussi comme ingénieur du son lors de certaines captations.",
    ],
    [
        'role' => 'Graphiste • Chroniqueur • Cadreur',
        'name' => 'Joshua RABOTEAU',
        'photo' => $defaultPhoto,
        'bio' => "Joshua contribue à la fois à l’aspect visuel et éditorial du projet. En tant que graphiste, il participe à la création de l’identité graphique de l’émission. Il intervient également comme chroniqueur, apportant du contenu et un regard personnel dans l’émission. Enfin, il participe aux tournages en tant que cadreur.",
    ],
    [
        'role' => 'Assistant rédacteur en chef • Réalisation • Monteur • Cadreur',
        'name' => 'Martin PETIT',
        'photo' => $defaultPhoto,
        'bio' => "Martin participe à la coordination éditoriale en tant qu’assistant rédacteur en chef, en soutenant l’organisation et la préparation des contenus. Il intervient aussi dans la réalisation et le montage, contribuant à la construction narrative et visuelle des séquences. Sur les tournages, il assure également la captation d’images comme cadreur.",
    ],
    [
        'role' => 'Communication • Décor plateau • Cadreuse • Intervenante',
        'name' => 'Phuong-My NGUYEN',
        'photo' => $defaultPhoto,
        'bio' => "Phuong-My contribue à la communication du projet, notamment pour la valorisation et la diffusion de l’émission. Elle participe également à la mise en place du décor du plateau, aidant à construire l’identité visuelle de l’espace de tournage. Elle intervient aussi comme cadreuse et apparaît à l’écran en tant qu’intervenante, participant à certains contenus de l’émission.",
    ],
    [
        'role' => 'Réalisation • Chroniqueuse',
        'name' => 'Julianne ROGAM',
        'photo' => $defaultPhoto,
        'bio' => "Julianne participe à la réalisation de contenus, en s’occupant de la mise en scène et de la direction de certaines séquences. Elle intervient également comme chroniqueuse, apportant une contribution éditoriale et un point de vue personnel au sein de l’émission.",
    ],
    [
        'role' => 'Cadreuse • Ingénieure du son',
        'name' => 'Loana SIM',
        'photo' => $defaultPhoto,
        'bio' => "Loana participe aux tournages en tant que cadreuse, contribuant à la captation des images. Elle assure également des fonctions d’ingénieure du son, veillant à la qualité audio des enregistrements et à la bonne prise de son lors des tournages.",
    ],
    [
        'role' => 'Cheffe décoratrice • Réalisation • Monteuse',
        'name' => 'Juliette RIEUNAU',
        'photo' => $defaultPhoto,
        'bio' => "Juliette est responsable de l’identité visuelle du plateau en tant que cheffe décoratrice, en concevant et mettant en place les éléments de décor. Elle intervient aussi dans la réalisation et le montage, participant à la construction visuelle et narrative des contenus de l’émission.",
    ],
];
?>

<main class="team-page container py-5">
    <section class="team-hero text-center">
        <p class="team-hero__eyebrow">L'Équipe</p>
        <h1>Le staff Lights On</h1>
        <p>
            Découvrez les profils de l’équipe qui conçoit, produit et fait vivre l’émission.
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
