<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/matches.css',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$events = [
    [
        'date' => '22 mars',
        'title' => 'Diffusion Épisode 02',
        'type' => 'Diffusion',
    ],
    [
        'date' => '30 mars',
        'title' => 'Tournage plateau',
        'type' => 'Production',
    ],
    [
        'date' => '12 avril',
        'title' => 'Événement partenaire',
        'type' => 'Partenariat',
    ],
    [
        'date' => '20 avril',
        'title' => 'Sortie Épisode 03',
        'type' => 'Diffusion',
    ],
];
?>

<main class="container py-5">
    <section class="matches-hero">
        <p class="matches-hero__eyebrow">Calendrier</p>
        <h1 class="matches-hero__title">Les prochains rendez-vous de l'émission</h1>
        <p class="matches-hero__text">
            Retrouvez les prochaines diffusions, tournages et événements partenaires de Lights On.
        </p>
    </section>

    <section class="matches-list" aria-live="polite">
        <h2 class="matches-list__title">Programmation culturelle</h2>

        <ul class="events-list" role="list">
            <?php foreach ($events as $event): ?>
                <li class="event-item">
                    <article class="event-card">
                        <p class="event-card__date"><?php echo htmlspecialchars($event['date']); ?></p>
                        <div class="event-card__content">
                            <h3 class="event-card__title"><?php echo htmlspecialchars($event['title']); ?></h3>
                            <p class="event-card__type"><?php echo htmlspecialchars($event['type']); ?></p>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
