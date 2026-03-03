<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$ba_bec_numMemb = $_SESSION['user_id'] ?? null;
$ba_bec_numStat = $_SESSION['numStat'] ?? null;
if (!$ba_bec_numMemb) {
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    exit();
}

$memberData = sql_select(
    "MEMBRE INNER JOIN STATUT ON MEMBRE.numStat = STATUT.numStat",
    "MEMBRE.numMemb, MEMBRE.pseudoMemb, MEMBRE.prenomMemb, MEMBRE.nomMemb, MEMBRE.eMailMemb, STATUT.libStat",
    "MEMBRE.numMemb = $ba_bec_numMemb"
)[0] ?? [];

$ba_bec_recaptchaSiteKey = getenv('RECAPTCHA_SITE_KEY');
$ba_bec_recaptchaSiteKeyEscaped = htmlspecialchars($ba_bec_recaptchaSiteKey ?? '', ENT_QUOTES, 'UTF-8');

$ba_bec_success = $_SESSION['success'] ?? null;
$ba_bec_error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

$totalComments = sql_select("comment", "COUNT(*) as total", "numMemb = $ba_bec_numMemb")[0]['total'] ?? 0;
$pendingComments = sql_select(
    "comment",
    "COUNT(*) as total",
    "numMemb = $ba_bec_numMemb AND attModOK = 0 AND delLogiq = 0"
)[0]['total'] ?? 0;
$publishedComments = sql_select(
    "comment",
    "COUNT(*) as total",
    "numMemb = $ba_bec_numMemb AND attModOK = 1 AND delLogiq = 0"
)[0]['total'] ?? 0;

$recentComments = sql_select(
    "comment c INNER JOIN article a ON c.numArt = a.numArt",
    "c.numCom, c.libCom, c.dtCreaCom, c.attModOK, c.delLogiq, a.libTitrArt",
    "c.numMemb = $ba_bec_numMemb",
    null,
    "c.dtCreaCom DESC",
    5
);

$recentLikes = sql_select(
    "likeart l INNER JOIN article a ON l.numArt = a.numArt",
    "l.numArt, l.likeA, a.libTitrArt",
    "l.numMemb = $ba_bec_numMemb",
    null,
    "a.libTitrArt ASC",
    5
);

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<main class="container my-5">
    <h1 class="mb-4">Mon compte</h1>
    <?php if ($ba_bec_success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($ba_bec_success); ?></div>
    <?php endif; ?>
    <?php if ($ba_bec_error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($ba_bec_error); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Accès rapide</h2>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary" href="<?php echo ROOT_URL . '/Pages_supplementaires/compte.php'; ?>">Mon compte</a>
                        <?php if ($ba_bec_numStat === 1 || $ba_bec_numStat === 2): ?>
                            <a class="btn btn-outline-secondary" href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>">Panneau admin</a>
                        <?php endif; ?>
                        <a class="btn btn-outline-danger" href="<?php echo ROOT_URL . '/api/security/disconnect.php'; ?>">Déconnexion</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Statut</h2>
                    <p class="mb-1"><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($memberData['pseudoMemb'] ?? ''); ?></p>
                    <p class="mb-1"><strong>Statut :</strong> <?php echo htmlspecialchars($memberData['libStat'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Coordonnées</h2>
                    <p class="mb-1"><strong>Prénom :</strong> <?php echo htmlspecialchars($memberData['prenomMemb'] ?? ''); ?></p>
                    <p class="mb-1"><strong>Nom :</strong> <?php echo htmlspecialchars($memberData['nomMemb'] ?? ''); ?></p>
                    <p class="mb-1"><strong>Email :</strong> <?php echo htmlspecialchars($memberData['eMailMemb'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h5 mb-3">Commentaires</h2>
                    <p class="mb-1"><strong>Total :</strong> <?php echo htmlspecialchars((string) $totalComments); ?></p>
                    <p class="mb-1"><strong>En attente :</strong> <?php echo htmlspecialchars((string) $pendingComments); ?></p>
                    <p class="mb-1"><strong>Publiés :</strong> <?php echo htmlspecialchars((string) $publishedComments); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Derniers commentaires</h2>
            <?php if (!empty($recentComments)): ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentComments as $ba_bec_comment): ?>
                                <?php
                                $statusLabel = 'En attente';
                                if ((int) $ba_bec_comment['delLogiq'] === 1) {
                                    $statusLabel = 'Supprimé';
                                } elseif ((int) $ba_bec_comment['attModOK'] === 1) {
                                    $statusLabel = 'Publié';
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ba_bec_comment['libTitrArt']); ?></td>
                                    <td><?php echo htmlspecialchars($ba_bec_comment['libCom']); ?></td>
                                    <td><?php echo htmlspecialchars($ba_bec_comment['dtCreaCom']); ?></td>
                                    <td><?php echo htmlspecialchars($statusLabel); ?></td>
                                    <td>
                                        <form action="<?php echo ROOT_URL . '/api/account/delete-comment.php'; ?>" method="post" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                            <input type="hidden" name="numCom" value="<?php echo (int) $ba_bec_comment['numCom']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="mb-0">Vous n'avez pas encore publié de commentaire.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Mes derniers likes</h2>
            <?php if (!empty($recentLikes)): ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentLikes as $ba_bec_like): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ba_bec_like['libTitrArt']); ?></td>
                                    <td><?php echo ((int) $ba_bec_like['likeA'] === 1) ? 'Like' : 'Dislike'; ?></td>
                                    <td>
                                        <form action="<?php echo ROOT_URL . '/api/account/delete-like.php'; ?>" method="post" onsubmit="return confirm('Supprimer ce like ?');">
                                            <input type="hidden" name="numArt" value="<?php echo (int) $ba_bec_like['numArt']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="mb-0">Vous n'avez pas encore liké d'article.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-4 border-danger">
        <div class="card-body">
            <h2 class="h5 mb-3 text-danger">Supprimer mon compte</h2>
            <p class="mb-3">La suppression est définitive et effacera vos likes et commentaires.</p>
            <form action="<?php echo ROOT_URL . '/api/account/delete.php'; ?>" method="post" id="delete-account-form" onsubmit="return confirm('Confirmer la suppression définitive de votre compte ?');">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-delete-account">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmDeleteAccount" name="confirmDeleteAccount" value="1" required>
                    <label class="form-check-label" for="confirmDeleteAccount">Je confirme vouloir supprimer mon compte.</label>
                </div>
                <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
            </form>
        </div>
    </div>
</main>

<?php if (!empty($ba_bec_recaptchaSiteKey)): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $ba_bec_recaptchaSiteKeyEscaped; ?>"></script>
<script>
    (function () {
        var form = document.getElementById('delete-account-form');
        var tokenInput = document.getElementById('g-recaptcha-response-delete-account');
        var siteKey = '<?php echo $ba_bec_recaptchaSiteKeyEscaped; ?>';

        if (!form || !tokenInput || !siteKey || typeof grecaptcha === 'undefined') {
            return;
        }

        form.addEventListener('submit', function (event) {
            if (tokenInput.value) {
                return;
            }

            event.preventDefault();

            grecaptcha.ready(function () {
                grecaptcha.execute(siteKey, {action: 'delete-account'})
                    .then(function (token) {
                        tokenInput.value = token;
                        form.submit();
                    })
                    .catch(function () {
                        form.submit();
                    });
            });
        });
    })();
</script>
<?php endif; ?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
