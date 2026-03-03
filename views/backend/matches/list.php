<?php
/*
 * Vue d'administration (liste) pour le module matches.
 * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
 * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
 * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
 * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
 * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

sql_connect();

$ba_bec_showAll = ($_GET['show'] ?? '') === 'all';
$ba_bec_perPage = (int) ($_GET['per_page'] ?? 10);
$ba_bec_allowedPerPage = [10, 20, 50];
if (!in_array($ba_bec_perPage, $ba_bec_allowedPerPage, true)) {
    $ba_bec_perPage = 10;
}
$ba_bec_page = max(1, (int) ($_GET['page'] ?? 1));
$ba_bec_offset = ($ba_bec_page - 1) * $ba_bec_perPage;

$ba_bec_whereClause = $ba_bec_showAll ? '' : 'WHERE (m.scoreBec IS NULL OR m.scoreAdversaire IS NULL)';
$ba_bec_order = 'ORDER BY m.dateMatch ASC, m.heureMatch ASC';
$ba_bec_limit = 'LIMIT ' . $ba_bec_offset . ', ' . $ba_bec_perPage;

$ba_bec_select = "SELECT
        m.numMatch AS numMatch,
        m.saison AS saison,
        m.phase AS phase,
        m.journee AS journee,
        m.dateMatch AS matchDate,
        m.heureMatch AS matchTime,
        m.lieuMatch AS location,
        m.scoreBec AS scoreBec,
        m.scoreAdversaire AS scoreAdversaire,
        m.clubAdversaire AS clubAdversaire,
        m.numEquipeAdverse AS numEquipeAdverse,
        e.nomEquipe AS teamName
    FROM `MATCH` m
    INNER JOIN EQUIPE e ON m.codeEquipe = e.codeEquipe
    {$ba_bec_whereClause}
    {$ba_bec_order}
    {$ba_bec_limit}";

$ba_bec_matches = [];
try {
    $ba_bec_matches = $DB->query($ba_bec_select)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    $ba_bec_matches = [];
}

$ba_bec_totalQuery = "SELECT COUNT(*) AS total FROM `MATCH` m {$ba_bec_whereClause}";
$ba_bec_totalRow = $DB->query($ba_bec_totalQuery)->fetch(PDO::FETCH_ASSOC);
$ba_bec_total = $ba_bec_totalRow['total'] ?? 0;
$ba_bec_hasNextPage = ($ba_bec_offset + $ba_bec_perPage) < $ba_bec_total;

$ba_bec_queryBase = [
    'show' => $ba_bec_showAll ? 'all' : 'pending',
    'per_page' => $ba_bec_perPage,
];
$ba_bec_nextQuery = array_merge($ba_bec_queryBase, ['page' => $ba_bec_page + 1]);

$ba_bec_pendingLabel = $ba_bec_showAll ? 'Tous les matchs' : 'Matchs sans score';
$ba_bec_formatTime = static function ($time): string {
    if (empty($time)) {
        return '';
    }
    $timestamp = strtotime($time);
    if ($timestamp === false) {
        return (string) $time;
    }
    return date('H:i', $timestamp);
};

$ba_bec_resolveSide = static function (?string $location): string {
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
};

$ba_bec_buildOpponent = static function (array $match): string {
    $opponent = trim((string) ($match['clubAdversaire'] ?? ''));
    if (!empty($match['numEquipeAdverse'])) {
        $opponent = trim($opponent . ' ' . $match['numEquipeAdverse']);
    }
    return $opponent !== '' ? $opponent : 'Adversaire';
};
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
            </div>
            <h1><?php echo $ba_bec_pendingLabel; ?></h1>
            <p>Affichage : <?php echo (int) $ba_bec_total; ?> match(s) au total.</p>
            <div class="mb-3">
                <?php if ($ba_bec_showAll) : ?>
                    <a href="list.php" class="btn btn-secondary">Afficher uniquement les matchs sans score</a>
                <?php else : ?>
                    <a href="list.php?show=all&per_page=<?php echo $ba_bec_perPage; ?>" class="btn btn-secondary">Afficher tous les matchs</a>
                <?php endif; ?>
                <a href="create.php" class="btn btn-success">Create</a>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Saison</th>
                        <th>Phase</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Domicile</th>
                        <th>Extérieur</th>
                        <th>Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ba_bec_matches as $ba_bec_match) { ?>
                        <?php
                        $side = $ba_bec_resolveSide($ba_bec_match['location'] ?? '');
                        $opponent = $ba_bec_buildOpponent($ba_bec_match);
                        $isHome = $side !== 'away';
                        $teamHome = $isHome ? ($ba_bec_match['teamName'] ?? 'BEC') : $opponent;
                        $teamAway = $isHome ? $opponent : ($ba_bec_match['teamName'] ?? 'BEC');
                        $scoreHome = $isHome ? ($ba_bec_match['scoreBec'] ?? null) : ($ba_bec_match['scoreAdversaire'] ?? null);
                        $scoreAway = $isHome ? ($ba_bec_match['scoreAdversaire'] ?? null) : ($ba_bec_match['scoreBec'] ?? null);
                        ?>
                        <tr>
                            <td><?php echo $ba_bec_match['numMatch']; ?></td>
                            <td><?php echo htmlspecialchars($ba_bec_match['saison'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($ba_bec_match['phase'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($ba_bec_match['matchDate'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($ba_bec_formatTime($ba_bec_match['matchTime'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($teamHome); ?></td>
                            <td><?php echo htmlspecialchars($teamAway); ?></td>
                            <td>
                                <?php if ($scoreHome !== null && $scoreAway !== null) : ?>
                                    <?php echo (int) $scoreHome; ?> - <?php echo (int) $scoreAway; ?>
                                <?php else : ?>
                                    À compléter
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?numMatch=<?php echo $ba_bec_match['numMatch']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete.php?numMatch=<?php echo $ba_bec_match['numMatch']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="mt-3">
                <span class="me-2">Afficher :</span>
                <a href="list.php?<?php echo http_build_query(array_merge($ba_bec_queryBase, ['per_page' => 20, 'page' => 1])); ?>" class="btn btn-outline-primary">20 matchs</a>
                <a href="list.php?<?php echo http_build_query(array_merge($ba_bec_queryBase, ['per_page' => 50, 'page' => 1])); ?>" class="btn btn-outline-primary">50 matchs</a>
                <?php if ($ba_bec_perPage !== 10) : ?>
                    <a href="list.php?<?php echo http_build_query(array_merge($ba_bec_queryBase, ['per_page' => 10, 'page' => 1])); ?>" class="btn btn-outline-secondary">10 matchs</a>
                <?php endif; ?>
            </div>
            <div class="mt-3">
                <?php if ($ba_bec_hasNextPage) : ?>
                    <a href="list.php?<?php echo http_build_query($ba_bec_nextQuery); ?>" class="btn btn-primary">Page suivante</a>
                <?php else : ?>
                    <button class="btn btn-secondary" disabled>Page suivante</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
