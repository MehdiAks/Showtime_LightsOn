<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [ROOT_URL . '/src/css/club-structure.css'];

function is_missing_table(PDOException $exception): bool
{
    $errorInfo = $exception->errorInfo ?? [];

    return $exception->getCode() === '42S02'
        || (isset($errorInfo[1]) && (int) $errorInfo[1] === 1146);
}

$dbAvailable = getenv('DB_HOST') && getenv('DB_USER') && getenv('DB_DATABASE');

$branches = [
    ['numBranche' => 1, 'libBranche' => 'Bureau'],
    ['numBranche' => 2, 'libBranche' => 'Équipe technique'],
    ['numBranche' => 3, 'libBranche' => 'Équipe animation'],
    ['numBranche' => 4, 'libBranche' => 'Équipe communication'],
];

if ($dbAvailable) {
    try {
        sql_connect();

        $staffStmt = $DB->prepare(
            'SELECT numPersonnel, prenomPersonnel, nomPersonnel, urlPhotoPersonnel,
                    estDirection, posteDirection,
                    estCommissionTechnique, posteCommissionTechnique,
                    estCommissionAnimation, posteCommissionAnimation,
                    estCommissionCommunication, posteCommissionCommunication
                FROM PERSONNEL
                WHERE estDirection = 1
                    OR estCommissionTechnique = 1
                    OR estCommissionAnimation = 1
                    OR estCommissionCommunication = 1
                ORDER BY nomPersonnel ASC, prenomPersonnel ASC'
        );
        $staffStmt->execute();
        $staff = $staffStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        if (is_missing_table($exception)) {
            $staff = [];
        } else {
            throw $exception;
        }
    }
} else {
    $staff = [];
}

$staffByBranch = [];
foreach ($branches as $branch) {
    $staffByBranch[$branch['libBranche']] = [];
}

$roleLabels = [
    'Bureau' => 'Membre du bureau',
    'Équipe technique' => 'Commission technique',
    'Équipe animation' => 'Commission animation',
    'Équipe communication' => 'Commission communication',
];

foreach ($staff as $member) {
    if (!empty($member['estDirection'])) {
        $poste = trim((string) ($member['posteDirection'] ?? ''));
        $staffByBranch['Bureau'][] = array_merge(
            $member,
            ['libPoste' => $poste !== '' ? $poste : $roleLabels['Bureau']]
        );
    }
    if (!empty($member['estCommissionTechnique'])) {
        $poste = trim((string) ($member['posteCommissionTechnique'] ?? ''));
        $staffByBranch['Équipe technique'][] = array_merge(
            $member,
            ['libPoste' => $poste !== '' ? $poste : $roleLabels['Équipe technique']]
        );
    }
    if (!empty($member['estCommissionAnimation'])) {
        $poste = trim((string) ($member['posteCommissionAnimation'] ?? ''));
        $staffByBranch['Équipe animation'][] = array_merge(
            $member,
            ['libPoste' => $poste !== '' ? $poste : $roleLabels['Équipe animation']]
        );
    }
    if (!empty($member['estCommissionCommunication'])) {
        $poste = trim((string) ($member['posteCommissionCommunication'] ?? ''));
        $staffByBranch['Équipe communication'][] = array_merge(
            $member,
            ['libPoste' => $poste !== '' ? $poste : $roleLabels['Équipe communication']]
        );
    }
}

$branchDescriptions = [
    'Bureau' => 'Le bureau pilote la vie du club et définit les orientations stratégiques.',
    'Équipe technique' => 'L\'équipe technique encadre les coachs et structure la performance sportive.',
    'Équipe animation' => 'L\'équipe animation coordonne les événements et l\'ambiance au sein du club.',
    'Équipe communication' => 'L\'équipe communication valorise les actions du club et relaie les informations.',
];

$defaultPhoto = ROOT_URL . '/src/images/image-defaut.jpeg';

function branch_id(string $label): string
{
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $label);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $slug));
    return trim($slug, '-');
}
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>

<section class="club-page">
    <header class="club-header">
        <h1>Organigramme &amp; bénévoles</h1>
        <p class="lead">
            Découvrez les personnes engagées dans la vie du club, des membres du bureau aux équipes techniques,
            animation et communication. Plusieurs bénévoles peuvent intervenir sur différentes missions.
        </p>
    </header>

    <?php foreach ($branches as $branch) : ?>
        <?php
        $branchName = $branch['libBranche'];
        $branchMembers = $staffByBranch[$branchName] ?? [];
        ?>
        <?php $branchAnchor = branch_id($branchName); ?>
        <section class="club-section" aria-labelledby="branch-<?php echo htmlspecialchars($branchAnchor); ?>">
            <div class="club-section-header">
                <h2 id="branch-<?php echo htmlspecialchars($branchAnchor); ?>"><?php echo htmlspecialchars($branchName); ?></h2>
                <p><?php echo htmlspecialchars($branchDescriptions[$branchName] ?? ''); ?></p>
            </div>

            <div class="members-divider" role="presentation" aria-hidden="true">
                <span>Afficher les membres&nbsp;: <?php echo htmlspecialchars($branchName); ?></span>
            </div>

            <?php if (empty($branchMembers)) : ?>
                <div class="empty-state">
                    <p>Aucun bénévole n'est encore renseigné pour cette branche.</p>
                </div>
            <?php else : ?>
                <div class="club-grid">
                    <?php foreach ($branchMembers as $member) : ?>
                        <article class="club-card">
                            <img src="<?php echo htmlspecialchars($member['urlPhotoPersonnel'] ?: $defaultPhoto); ?>" alt="<?php echo htmlspecialchars($member['prenomPersonnel'] . ' ' . $member['nomPersonnel']); ?>">
                            <div class="club-card-body">
                                <h3 class="club-card-title">
                                    <?php echo htmlspecialchars($member['prenomPersonnel'] . ' ' . $member['nomPersonnel']); ?>
                                </h3>
                                <p class="club-card-meta"><?php echo htmlspecialchars($member['libPoste']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
