<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [ROOT_URL . '/src/css/team-detail.css'];

function render_missing_table_page(PDOException $exception): void
{
    $errorInfo = $exception->errorInfo ?? [];
    $isMissingTable = $exception->getCode() === '42S02'
        || (isset($errorInfo[1]) && (int) $errorInfo[1] === 1146);

    if ($isMissingTable) {
        http_response_code(404);
        require_once $_SERVER['DOCUMENT_ROOT'] . '/erreur404.php';
        exit;
    }
}

function ba_bec_team_photo_url(?string $photoPath, ?string $nomEquipe, ?string $codeEquipe, string $suffix): string
{
    if (!empty($photoPath)) {
        $relative = ltrim((string) $photoPath, '/');
        if (strpos($relative, 'src/uploads/') === 0) {
            $relative = substr($relative, strlen('src/uploads/'));
        }
        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $relative;
        if (file_exists($absolutePath)) {
            return ROOT_URL . '/src/uploads/' . $relative;
        }
    }

    $slug = strtolower((string) iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', (string) ($nomEquipe ?: $codeEquipe)));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim((string) $slug, '-');
    if ($slug === '') {
        return '';
    }

    foreach (['jpg', 'jpeg', 'png', 'avif', 'svg', 'webp', 'gif'] as $ext) {
        $relativePath = '/src/uploads/photos-equipes/' . $slug . '-' . $suffix . '.' . $ext;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $relativePath)) {
            return ROOT_URL . $relativePath;
        }
    }

    return '';
}

function format_poste(?int $poste): string
{
    $labels = [
        1 => 'Meneur',
        2 => 'Arrière',
        3 => 'Ailier',
        4 => 'Ailier fort',
        5 => 'Pivot',
    ];
    if (!$poste) {
        return 'Poste non renseigné';
    }
    return $labels[$poste] ?? ('Poste ' . $poste);
}

function resolve_match_side(?string $location): string
{
    $location = strtolower(trim((string) $location));
    if ($location === '') {
        return 'home';
    }
    if (str_contains($location, 'exterieur') || str_contains($location, 'extérieur') || str_contains($location, 'away')) {
        return 'away';
    }
    if (str_contains($location, 'domicile') || str_contains($location, 'home') || str_contains($location, 'barbey')) {
        return 'home';
    }
    return 'home';
}

$teamId = filter_input(INPUT_GET, 'numEquipe', FILTER_VALIDATE_INT);
if (!$teamId) {
    http_response_code(404);
    require_once $_SERVER['DOCUMENT_ROOT'] . '/erreur404.php';
    exit;
}

$dbAvailable = getenv('DB_HOST') && getenv('DB_USER') && getenv('DB_DATABASE');
$team = null;
$players = [];
$coaches = [];
$teamMatches = [];

if ($dbAvailable) {
    try {
        sql_connect();

        $teamStmt = $DB->prepare(
            'SELECT numEquipe, codeEquipe, nomEquipe, descriptionEquipe, photoDLequipe, photoStaff, categorie, section, niveau
             FROM EQUIPE
             WHERE numEquipe = :teamId'
        );
        $teamStmt->execute(['teamId' => $teamId]);
        $team = $teamStmt->fetch(PDO::FETCH_ASSOC) ?: null;

        if (!$team) {
            http_response_code(404);
            require_once $_SERVER['DOCUMENT_ROOT'] . '/erreur404.php';
            exit;
        }

        $playersStmt = $DB->prepare(
            'SELECT prenomJoueur, nomJoueur, posteJoueur, urlPhotoJoueur
             FROM JOUEUR
             WHERE codeEquipe = :codeEquipe
             ORDER BY nomJoueur ASC'
        );
        $playersStmt->execute(['codeEquipe' => $team['codeEquipe']]);
        $players = $playersStmt->fetchAll(PDO::FETCH_ASSOC);

        $coachesStmt = $DB->prepare(
            'SELECT prenomPersonnel, nomPersonnel, roleStaffEquipe AS libRolePersonnel
             FROM PERSONNEL
             WHERE estStaffEquipe = 1 AND numEquipeStaff = :codeEquipe
             ORDER BY nomPersonnel ASC'
        );
        $coachesStmt->execute(['codeEquipe' => $team['codeEquipe']]);
        $coaches = $coachesStmt->fetchAll(PDO::FETCH_ASSOC);

        $matchesStmt = $DB->prepare(
            'SELECT m.dateMatch, m.heureMatch, m.lieuMatch,
                    m.scoreBec AS scoreBec,
                    m.scoreAdversaire AS scoreAdversaire,
                    m.clubAdversaire AS clubAdversaire,
                    m.numEquipeAdverse AS numEquipeAdverse,
                    e.nomEquipe AS teamName
             FROM `MATCH` m
             INNER JOIN EQUIPE e ON m.codeEquipe = e.codeEquipe
             WHERE m.codeEquipe = :codeEquipe
             ORDER BY m.dateMatch DESC, m.heureMatch DESC'
        );
        $matchesStmt->execute(['codeEquipe' => $team['codeEquipe']]);
        $teamMatches = $matchesStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        render_missing_table_page($exception);
        throw $exception;
    }
} else {
    $team = [
        'numEquipe' => $teamId,
        'codeEquipe' => 'BEC',
        'nomEquipe' => 'Équipe du BEC',
        'categorie' => 'Seniors',
        'section' => 'Championnat régional',
        'niveau' => 'Niveau 1',
        'descriptionEquipe' => 'Une équipe engagée et soudée qui défend fièrement les couleurs du BEC tout au long de la saison.',
        'photoDLequipe' => '',
        'photoStaff' => '',
    ];
    $players = [
        ['prenomJoueur' => 'Léo', 'nomJoueur' => 'Martin', 'posteJoueur' => 1],
        ['prenomJoueur' => 'Amélie', 'nomJoueur' => 'Durand', 'posteJoueur' => 3],
        ['prenomJoueur' => 'Hugo', 'nomJoueur' => 'Bernard', 'posteJoueur' => 5],
        ['prenomJoueur' => 'Sara', 'nomJoueur' => 'Lopez', 'posteJoueur' => 2],
    ];
    $coaches = [
        ['prenomPersonnel' => 'Camille', 'nomPersonnel' => 'Roche', 'libRolePersonnel' => 'Coach'],
        ['prenomPersonnel' => 'Paul', 'nomPersonnel' => 'Girard', 'libRolePersonnel' => 'Assistant'],
    ];
    $teamMatches = [
        ['teamHome' => 'BEC', 'teamAway' => 'US Talence', 'scoreHome' => 82, 'scoreAway' => 68, 'dateMatch' => '2024-10-05', 'isHome' => true],
        ['teamHome' => 'Union Saint-Bruno', 'teamAway' => 'BEC', 'scoreHome' => 79, 'scoreAway' => 74, 'dateMatch' => '2024-10-12', 'isHome' => false],
    ];
}

$teamMatches = array_map(
    static function (array $match) use ($team): array {
        if (empty($team) || empty($team['nomEquipe'])) {
            return $match;
        }
        $opponent = trim((string) ($match['clubAdversaire'] ?? ''));
        if (!empty($match['numEquipeAdverse'])) {
            $opponent = trim($opponent . ' ' . $match['numEquipeAdverse']);
        }
        if ($opponent === '') {
            $opponent = 'Adversaire';
        }
        $side = resolve_match_side($match['lieuMatch'] ?? '');
        $isHome = $side === 'home';
        $teamHome = $isHome ? $team['nomEquipe'] : $opponent;
        $teamAway = $isHome ? $opponent : $team['nomEquipe'];
        $scoreHome = $isHome ? ($match['scoreBec'] ?? null) : ($match['scoreAdversaire'] ?? null);
        $scoreAway = $isHome ? ($match['scoreAdversaire'] ?? null) : ($match['scoreBec'] ?? null);

        return array_merge($match, [
            'teamHome' => $teamHome,
            'teamAway' => $teamAway,
            'scoreHome' => $scoreHome,
            'scoreAway' => $scoreAway,
            'isHome' => $isHome,
        ]);
    },
    $teamMatches
);

$defaultTeamImage = ROOT_URL . '/src/images/image-defaut.jpeg';

$teamName = $team['nomEquipe'] ?? '';
$teamPhotoUrl = ba_bec_team_photo_url($team['photoDLequipe'] ?? null, $team['nomEquipe'] ?? null, $team['codeEquipe'] ?? null, 'photo-equipe') ?: $defaultTeamImage;
$staffPhotoUrl = ba_bec_team_photo_url($team['photoStaff'] ?? null, $team['nomEquipe'] ?? null, $team['codeEquipe'] ?? null, 'photo-staff') ?: $defaultTeamImage;
$bannerImage = $teamPhotoUrl;

$stats = [
    'home' => ['matches' => 0, 'pointsFor' => 0, 'pointsAgainst' => 0],
    'away' => ['matches' => 0, 'pointsFor' => 0, 'pointsAgainst' => 0],
    'total' => ['matches' => 0, 'pointsFor' => 0, 'pointsAgainst' => 0],
    'wins' => 0,
    'bestWin' => null,
];

foreach ($teamMatches as $match) {
    $scoreHome = $match['scoreHome'];
    $scoreAway = $match['scoreAway'];
    if ($scoreHome === null || $scoreAway === null) {
        continue;
    }

    $isHome = (bool) ($match['isHome'] ?? false);
    $locationKey = $isHome ? 'home' : 'away';
    $pointsFor = $isHome ? $scoreHome : $scoreAway;
    $pointsAgainst = $isHome ? $scoreAway : $scoreHome;

    $stats[$locationKey]['matches']++;
    $stats[$locationKey]['pointsFor'] += (int) $pointsFor;
    $stats[$locationKey]['pointsAgainst'] += (int) $pointsAgainst;

    $stats['total']['matches']++;
    $stats['total']['pointsFor'] += (int) $pointsFor;
    $stats['total']['pointsAgainst'] += (int) $pointsAgainst;

    if ($pointsFor > $pointsAgainst) {
        $stats['wins']++;
        $diff = (int) $pointsFor - (int) $pointsAgainst;
        if (!$stats['bestWin'] || $diff > $stats['bestWin']['diff']) {
            $opponent = $isHome ? ($match['teamAway'] ?? '') : ($match['teamHome'] ?? '');
            $stats['bestWin'] = [
                'diff' => $diff,
                'opponent' => $opponent,
                'date' => $match['dateMatch'] ?? '',
            ];
        }
    }
}

$totalMatches = (int) $stats['total']['matches'];
$totalPoints = (int) $stats['total']['pointsFor'];
$totalWins = (int) $stats['wins'];
$totalLosses = max(0, $totalMatches - $totalWins);

$recentFor = [];
$recentAgainst = [];
foreach ($teamMatches as $match) {
    $scoreHome = $match['scoreHome'] ?? null;
    $scoreAway = $match['scoreAway'] ?? null;
    if ($scoreHome === null || $scoreAway === null) {
        continue;
    }
    $isHome = (bool) ($match['isHome'] ?? false);
    $pointsFor = $isHome ? $scoreHome : $scoreAway;
    $pointsAgainst = $isHome ? $scoreAway : $scoreHome;
    $recentFor[] = (int) $pointsFor;
    $recentAgainst[] = (int) $pointsAgainst;
}
$recentFor = array_reverse(array_slice($recentFor, 0, 10));
$recentAgainst = array_reverse(array_slice($recentAgainst, 0, 10));

$recentForCumulative = [];
$recentAgainstCumulative = [];
$sumFor = 0;
$sumAgainst = 0;
foreach ($recentFor as $index => $value) {
    $sumFor += $value;
    $sumAgainst += $recentAgainst[$index] ?? 0;
    $recentForCumulative[] = $sumFor;
    $recentAgainstCumulative[] = $sumAgainst;
}

$axisLabels = [];
$recentCount = count($recentForCumulative);
if ($recentCount > 0) {
    if ($recentCount === 1) {
        $axisLabels[] = ['index' => 1, 'pos' => 0];
    } else {
        $indices = [
            1,
            (int) round(($recentCount + 1) / 3),
            (int) round(2 * ($recentCount + 1) / 3),
            $recentCount,
        ];
        $indices = array_values(array_unique($indices));
        sort($indices);
        foreach ($indices as $index) {
            $pos = (($index - 1) / ($recentCount - 1)) * 100;
            $axisLabels[] = ['index' => $index, 'pos' => $pos];
        }
    }
}

$upcomingMatches = array_values(array_filter(
    $teamMatches,
    static function (array $match): bool {
        if (empty($match['dateMatch'])) {
            return false;
        }
        return $match['dateMatch'] >= date('Y-m-d');
    }
));
$nextMatch = $upcomingMatches[0] ?? null;
$nextMatchOthers = array_slice($upcomingMatches, 1, 4);

$coachLead = null;
$assistantCoaches = [];
$otherCoaches = [];

foreach ($coaches as $coach) {
    $role = strtolower($coach['libRolePersonnel'] ?? '');
    if (str_contains($role, 'assistant')) {
        $assistantCoaches[] = $coach;
        continue;
    }

    if (!$coachLead) {
        $coachLead = $coach;
        continue;
    }

    $otherCoaches[] = $coach;
}

if (!$coachLead && !empty($assistantCoaches)) {
    $coachLead = array_shift($assistantCoaches);
}
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>

<section class="team-detail">
    <header class="team-detail-header">
        <div class="team-detail-banner" style="background-image: url('<?php echo htmlspecialchars($bannerImage); ?>');">
            <div class="team-detail-banner-content">
                <p class="team-detail-category">
                    <?php echo htmlspecialchars($team['categorie'] ?: 'Catégorie non renseignée'); ?>
                </p>
                <h1><?php echo htmlspecialchars($teamName); ?></h1>
                <p class="team-detail-meta">
                    <?php echo htmlspecialchars($team['section'] ?: 'Section non renseignée'); ?> ·
                    <?php echo htmlspecialchars($team['niveau'] ?: 'Niveau non renseigné'); ?>
                </p>
            </div>
        </div>
    </header>

    <section class="team-detail-section">
        <div class="team-profile card shadow-sm">
            <div class="row g-0 align-items-center">
                <div class="col-md-4 team-profile-media">
                    <div class="team-profile-gallery">
                        <div class="team-profile-photo">
                            <p class="team-profile-label">Photo équipe</p>
                            <img src="<?php echo htmlspecialchars($teamPhotoUrl); ?>"
                                alt="<?php echo htmlspecialchars($teamName); ?>">
                        </div>
                        <div class="team-profile-photo">
                            <p class="team-profile-label">Photo staff</p>
                            <img src="<?php echo htmlspecialchars($staffPhotoUrl); ?>"
                                alt="Photo du staff <?php echo htmlspecialchars($teamName); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h2>Le staff en action</h2>
                        <p class="mb-0">
                            <?php echo htmlspecialchars($team['descriptionEquipe'] ?: 'La description de l\'équipe sera bientôt disponible.'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-detail-section team-stats-section">
        <h2>Quelques statistiques</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="team-stat-card h-100">
                    <div class="team-stat-card-body">
                        <h3 class="team-stat-title">Matchs joués</h3>
                        <div class="team-stat-list">
                            <p><span>Domicile</span><strong><?php echo htmlspecialchars((string) $stats['home']['matches']); ?></strong></p>
                            <p><span>Extérieur</span><strong><?php echo htmlspecialchars((string) $stats['away']['matches']); ?></strong></p>
                            <p class="team-stat-total"><span>Total</span><strong><?php echo htmlspecialchars((string) $stats['total']['matches']); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-stat-card h-100">
                    <div class="team-stat-card-body">
                        <h3 class="team-stat-title">Points marqués</h3>
                        <div class="team-stat-list">
                            <p><span>Domicile</span><strong><?php echo htmlspecialchars((string) $stats['home']['pointsFor']); ?></strong></p>
                            <p><span>Extérieur</span><strong><?php echo htmlspecialchars((string) $stats['away']['pointsFor']); ?></strong></p>
                            <p class="team-stat-total"><span>Total</span><strong><?php echo htmlspecialchars((string) $stats['total']['pointsFor']); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team-stat-card h-100">
                    <div class="team-stat-card-body">
                        <h3 class="team-stat-title">Points encaissés</h3>
                        <div class="team-stat-list">
                            <p><span>Domicile</span><strong><?php echo htmlspecialchars((string) $stats['home']['pointsAgainst']); ?></strong></p>
                            <p><span>Extérieur</span><strong><?php echo htmlspecialchars((string) $stats['away']['pointsAgainst']); ?></strong></p>
                            <p class="team-stat-total"><span>Total</span><strong><?php echo htmlspecialchars((string) $stats['total']['pointsAgainst']); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="team-stat-card team-stat-highlight h-100">
                    <div class="team-stat-card-body">
                        <h3 class="team-stat-title">Matchs gagnés</h3>
                        <p class="team-stat-big-number"><?php echo htmlspecialchars((string) $stats['wins']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="team-stat-card team-stat-highlight h-100">
                    <div class="team-stat-card-body">
                        <h3 class="team-stat-title">Meilleure différence</h3>
                        <?php if ($stats['bestWin']): ?>
                            <p class="team-stat-big-number team-stat-big-number-sm">
                                +<?php echo htmlspecialchars((string) $stats['bestWin']['diff']); ?>
                            </p>
                            <?php if (!empty($stats['bestWin']['opponent'])): ?>
                                <p class="team-stat-subtitle mb-0">
                                    <?php echo htmlspecialchars($stats['bestWin']['opponent']); ?>
                                    <?php if (!empty($stats['bestWin']['date'])): ?>
                                        · <?php echo htmlspecialchars($stats['bestWin']['date']); ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="team-stat-subtitle mb-0">Aucune victoire enregistrée.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-detail-section chart-section">
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <article class="chart-card h-100">
                    <div class="chart-header">
                        <h3>Évolution des points</h3>
                        <p class="text-body-secondary mb-0">
                            Courbe personnalisée sur les 10 derniers matchs.
                        </p>
                    </div>
                    <div class="line-chart"
                        data-for="<?php echo htmlspecialchars(implode(',', $recentForCumulative)); ?>"
                        data-against="<?php echo htmlspecialchars(implode(',', $recentAgainstCumulative)); ?>">
                        <div class="line-chart-layout">
                            <div class="line-chart-y">
                                <span class="line-y-label" data-y="max"></span>
                                <span class="line-y-label" data-y="mid"></span>
                                <span class="line-y-label" data-y="min"></span>
                            </div>
                            <div class="line-chart-frame">
                                <svg class="line-chart-svg" viewBox="0 0 100 60" preserveAspectRatio="none" aria-hidden="true">
                                    <path class="line-area line-area-for" d=""></path>
                                    <path class="line-area line-area-against" d=""></path>
                                    <path class="line-stroke line-stroke-for" d=""></path>
                                    <path class="line-stroke line-stroke-against" d=""></path>
                                </svg>
                            </div>
                        </div>
                        <p class="line-chart-empty text-body-secondary mb-0">Aucun match terminé.</p>
                        <div class="line-chart-axis">
                            <?php foreach ($axisLabels as $label) : ?>
                                <span class="line-axis-label" style="--pos: <?php echo number_format((float) $label['pos'], 2, '.', ''); ?>%;">
                                    <?php echo 'J' . (int) $label['index']; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <div class="line-chart-legend">
                            <span><i class="legend-dot legend-for"></i>Points marqués (cumulés)</span>
                            <span><i class="legend-dot legend-against"></i>Points encaissés (cumulés)</span>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-lg-5">
                <article class="chart-card h-100">
                    <div class="chart-header">
                        <h3>Victoires / défaites</h3>
                        <p class="text-body-secondary mb-0">
                            Répartition des matchs gagnés et perdus.
                        </p>
                    </div>
                    <div class="donut"
                        role="img"
                        aria-label="Victoires <?php echo (int) $totalWins; ?>, défaites <?php echo (int) $totalLosses; ?>"
                        data-wins="<?php echo (int) $totalWins; ?>"
                        data-losses="<?php echo (int) $totalLosses; ?>">
                        <div class="donut-center">
                            <span class="donut-label">Bilan</span>
                            <strong class="donut-value" data-record><?php echo htmlspecialchars($totalWins . ' - ' . $totalLosses); ?></strong>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <span><i class="legend-dot legend-wins"></i>Victoires</span>
                        <span><i class="legend-dot legend-losses"></i>Défaites</span>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="team-detail-section">
        <h2>Prochain match</h2>
        <?php if ($nextMatch): ?>
            <article class="match-highlight">
                <div class="match-clubs">
                    <div class="match-team">
                        <span><?php echo htmlspecialchars($nextMatch['teamHome'] ?? 'Domicile'); ?></span>
                    </div>
                    <span class="match-versus">VS</span>
                    <div class="match-team">
                        <span><?php echo htmlspecialchars($nextMatch['teamAway'] ?? 'Extérieur'); ?></span>
                    </div>
                </div>
                <div class="match-details">
                    <p><?php echo htmlspecialchars($nextMatch['dateMatch'] ?? ''); ?><?php echo !empty($nextMatch['heureMatch']) ? ' · ' . htmlspecialchars($nextMatch['heureMatch']) : ''; ?>
                    </p>
                    <p class="text-muted"><?php echo htmlspecialchars($nextMatch['lieuMatch'] ?? 'Lieu à confirmer'); ?></p>
                </div>
            </article>
        <?php else: ?>
            <p class="text-muted">Aucun match à venir.</p>
        <?php endif; ?>

        <?php if (!empty($nextMatchOthers)): ?>
            <div class="match-upcoming">
                <?php foreach ($nextMatchOthers as $match): ?>
                    <article class="match-card">
                        <p class="match-card-opponent">
                            <?php echo htmlspecialchars(($match['teamHome'] ?? '') . ' vs ' . ($match['teamAway'] ?? '')); ?>
                        </p>
                        <p class="match-card-date"><?php echo htmlspecialchars($match['dateMatch'] ?? ''); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="team-detail-section">
        <h2>Staff technique</h2>
        <div class="staff-grid">
            <article class="staff-card">
                <h3>Coach principal</h3>
                <?php if ($coachLead): ?>
                    <p><?php echo htmlspecialchars($coachLead['prenomPersonnel'] . ' ' . $coachLead['nomPersonnel']); ?></p>
                    <?php if (!empty($coachLead['libRolePersonnel'])): ?>
                        <p class="text-muted"><?php echo htmlspecialchars($coachLead['libRolePersonnel']); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted">Aucun coach renseigné.</p>
                <?php endif; ?>
            </article>
            <article class="staff-card">
                <h3>Assistant</h3>
                <?php if (!empty($assistantCoaches)): ?>
                    <ul>
                        <?php foreach ($assistantCoaches as $assistant): ?>
                            <li><?php echo htmlspecialchars($assistant['prenomPersonnel'] . ' ' . $assistant['nomPersonnel']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Aucun assistant renseigné.</p>
                <?php endif; ?>
            </article>
        </div>
    </section>

    <section class="team-detail-section">
        <h2>Joueurs</h2>
        <?php if (empty($players)): ?>
            <p class="text-muted">Aucun joueur renseigné.</p>
        <?php else: ?>
            <div class="players-grid">
                <?php foreach ($players as $player): ?>
                    <?php
                    $playerPhoto = $player['urlPhotoJoueur'] ?? '';
                    $playerPhotoUrl = '';
                    if (!empty($playerPhoto)) {
                        $playerPhotoUrl = preg_match('/^(https?:\/\/|\/)/', $playerPhoto)
                            ? $playerPhoto
                            : ROOT_URL . '/src/uploads/' . $playerPhoto;
                    }
                    ?>
                    <article class="player-card">
                        <h3><?php echo htmlspecialchars($player['prenomJoueur'] . ' ' . $player['nomJoueur']); ?></h3>
                        <div class="player-photo">
                            <?php if ($playerPhotoUrl): ?>
                                <img src="<?php echo htmlspecialchars($playerPhotoUrl); ?>"
                                    alt="<?php echo htmlspecialchars($player['prenomJoueur'] . ' ' . $player['nomJoueur']); ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <span class="player-photo-placeholder">Photo à venir</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($player['posteJoueur'])): ?>
                            <p class="text-muted"><?php echo htmlspecialchars(format_poste((int) $player['posteJoueur'])); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</section>

<script>
    (function () {
        const lineCharts = document.querySelectorAll(".line-chart");
        lineCharts.forEach((chart) => {
            const forValues = (chart.dataset["for"] || "")
                .split(",")
                .map((value) => Number.parseInt(value, 10))
                .filter((value) => Number.isFinite(value));
            const againstValues = (chart.dataset.against || "")
                .split(",")
                .map((value) => Number.parseInt(value, 10))
                .filter((value) => Number.isFinite(value));

            const areaFor = chart.querySelector(".line-area-for");
            const areaAgainst = chart.querySelector(".line-area-against");
            const lineFor = chart.querySelector(".line-stroke-for");
            const lineAgainst = chart.querySelector(".line-stroke-against");
            const yLabelMax = chart.querySelector('.line-y-label[data-y="max"]');
            const yLabelMid = chart.querySelector('.line-y-label[data-y="mid"]');
            const yLabelMin = chart.querySelector('.line-y-label[data-y="min"]');

            const hasFor = forValues.length > 0;
            const hasAgainst = againstValues.length > 0;

            if (!hasFor && !hasAgainst) {
                chart.classList.add("is-empty");
                return;
            }

            chart.classList.remove("is-empty");

            const allValues = [...forValues, ...againstValues];
            const minValue = Math.min(...allValues);
            const maxValue = Math.max(...allValues);
            const range = Math.max(1, maxValue - minValue);
            const height = 60;
            const width = 100;
            const paddingTop = 6;
            const paddingBottom = 8;
            const usableHeight = height - paddingTop - paddingBottom;

            const buildPath = (values) => {
                const count = values.length;
                if (count === 0) {
                    return { line: "", area: "" };
                }
                const points = values.map((value, index) => {
                    const x = count === 1 ? width / 2 : (index / (count - 1)) * width;
                    const y = paddingTop + ((maxValue - value) / range) * usableHeight;
                    return [x, y];
                });
                const linePath = points
                    .map((point, index) => `${index === 0 ? "M" : "L"} ${point[0].toFixed(2)} ${point[1].toFixed(2)}`)
                    .join(" ");
                const baseline = height - paddingBottom;
                const areaPath = `M ${points[0][0].toFixed(2)} ${baseline.toFixed(2)} ${points
                    .map((point) => `L ${point[0].toFixed(2)} ${point[1].toFixed(2)}`)
                    .join(" ")} L ${points[points.length - 1][0].toFixed(2)} ${baseline.toFixed(2)} Z`;
                return { line: linePath, area: areaPath };
            };

            if (areaFor && lineFor) {
                const paths = buildPath(forValues);
                lineFor.setAttribute("d", paths.line);
                areaFor.setAttribute("d", paths.area);
            }

            if (areaAgainst && lineAgainst) {
                const paths = buildPath(againstValues);
                lineAgainst.setAttribute("d", paths.line);
                areaAgainst.setAttribute("d", paths.area);
            }

            const midValue = Math.round((minValue + maxValue) / 2);
            if (yLabelMax) {
                yLabelMax.textContent = maxValue.toLocaleString("fr-FR");
            }
            if (yLabelMid) {
                yLabelMid.textContent = midValue.toLocaleString("fr-FR");
            }
            if (yLabelMin) {
                yLabelMin.textContent = minValue.toLocaleString("fr-FR");
            }
        });

        const donuts = document.querySelectorAll(".donut");
        donuts.forEach((donut) => {
            const wins = Number.parseInt(donut.dataset.wins || "0", 10) || 0;
            const losses = Number.parseInt(donut.dataset.losses || "0", 10) || 0;
            const total = Math.max(wins + losses, 1);
            const winAngle = Math.round((wins / total) * 360);
            donut.style.setProperty("--win-angle", `${winAngle}deg`);

            const recordValue = donut.querySelector("[data-record]");
            if (recordValue) {
                recordValue.textContent = `${wins} - ${losses}`;
            }
        });
    })();
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
