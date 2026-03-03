<?php
/*
 * Vue d'administration (édition) pour le module members.
 * - Le formulaire réutilise la structure de création mais avec des valeurs pré-remplies côté serveur.
 * - Les identifiants nécessaires (ID) sont passés via la query string ou des champs cachés.
 * - L'action du formulaire cible la route de mise à jour correspondante.
 * - Les sections HTML isolent les groupes d'attributs pour une édition guidée.
 * - Les actions secondaires permettent de revenir à la liste sans enregistrer.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

// Récupération des erreurs flash
$ba_bec_errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
$ba_bec_recaptchaSiteKey = getenv('RECAPTCHA_SITE_KEY');
$ba_bec_recaptchaSiteKeyEscaped = htmlspecialchars($ba_bec_recaptchaSiteKey ?? '', ENT_QUOTES, 'UTF-8');

// Seulement si tu es admin ou modérateur, tu as accès à cette page
/*if (!isset($_SESSION['numStat']) || $_SESSION['numStat'] !== 1 && $_SESSION['numStat'] !== 2 ) {
    header('Location: ../../../index.php');
    exit();
}*/

// Initialisation des variables
$ba_bec_numMemb = $ba_bec_pseudoMemb = $ba_bec_prenomMemb = $ba_bec_nomMemb = $ba_bec_passMemb = $ba_bec_eMailMemb = "";
$ba_bec_numStat = 3; // Par défaut, statut "Membre"

if (isset($_GET['numMemb'])) {
    $ba_bec_numMemb = $_GET['numMemb'];
    $ba_bec_membre = sql_select("MEMBRE", "*", "numMemb = $ba_bec_numMemb")[0] ?? [];

    $ba_bec_pseudoMemb = $ba_bec_membre['pseudoMemb'] ?? "";
    $ba_bec_prenomMemb = $ba_bec_membre['prenomMemb'] ?? "";
    $ba_bec_nomMemb = $ba_bec_membre['nomMemb'] ?? "";
    $ba_bec_passMemb = $ba_bec_membre['passMemb'] ?? "";
    $ba_bec_eMailMemb = $ba_bec_membre['eMailMemb'] ?? "";
    $ba_bec_numStat = $ba_bec_membre['numStat'] ?? 3;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification Membre</h1>
        </div>
        <?php if (!empty($ba_bec_errors)): ?>
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($ba_bec_errors as $ba_bec_error): ?>
                            <li><?= htmlspecialchars($ba_bec_error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/members/update.php' ?>" method="post">
                <input name="numMemb" class="form-control" type="hidden"
                    value="<?php echo htmlspecialchars($ba_bec_numMemb); ?>" />
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-update">

                <div class="form-group">
                    <!-- NOM D'UTILISATEUR -->
                    <label for="pseudoMemb">Nom d'utilisateur du membre (non modifiable)</label>
                    <input id="pseudoMemb" name="pseudoMemb" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_pseudoMemb); ?>" readonly disabled />

                    <!-- PRENOM -->
                    <label for="prenomMemb">Prénom du membre</label>
                    <input id="prenomMemb" name="prenomMemb" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_prenomMemb); ?>" placeholder="Prénom (ex: Léa)" />

                    <!-- NOM -->
                    <label for="nomMemb">Nom du membre</label>
                    <input id="nomMemb" name="nomMemb" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_nomMemb); ?>" placeholder="Nom (ex: Martin)" />

                    <!-- MDP -->
                    <label for="passMemb">Mot de Passe</label>
                    <input id="passMemb" name="passMemb" class="form-control" type="password"
                        value="<?php echo htmlspecialchars($ba_bec_passMemb); ?>" />
                    <p>(Entre 8 et 15 car., au moins une majuscule, une minuscule, un chiffre, caractères spéciaux
                        acceptés)</p>
                    <button type="button" id="afficher" class="btn btn-secondary">Afficher le mot de
                        passe</button><br><br>

                    <!-- MDP VERIFICATION -->
                    <label for="passMemb2">Confirmez le mot de passe</label>
                    <input id="passMemb2" name="passMemb2" class="form-control" type="password"
                        value="<?php echo htmlspecialchars($ba_bec_passMemb); ?>" />
                    <button type="button" id="afficher2" class="btn btn-secondary">Afficher le mot de
                        passe</button><br><br>

                    <!-- EMAIL -->
                    <label for="eMailMemb">Email du membre</label>
                    <input id="eMailMemb" name="eMailMemb" class="form-control" type="email"
                        value="<?php echo htmlspecialchars($ba_bec_eMailMemb); ?>"
                        placeholder="prenom.nom@example.com" />

                    <!-- EMAIL VERIFICATION -->
                    <label for="eMailMemb2">Confirmez l'email du membre</label>
                    <input id="eMailMemb2" name="eMailMemb2" class="form-control" type="email"
                        value="<?php echo htmlspecialchars($ba_bec_eMailMemb); ?>"
                        placeholder="prenom.nom@example.com" />
                    <br><br>

                    <!-- STATUT -->
                    <label for="numStat">Statut :</label>
                    <select name="numStat" id="numStat" class="form-control">
                        <option value="1" <?= ($ba_bec_numStat == 1) ? 'selected' : '' ?>>Administrateur</option>
                        <option value="2" <?= ($ba_bec_numStat == 2) ? 'selected' : '' ?>>Modérateur</option>
                        <option value="3" <?= ($ba_bec_numStat == 3) ? 'selected' : '' ?>>Membre</option>
                    </select>
                </div>

                <br />
                <div class="form-group mt-2">
                    <button type="submit" class="btn btn-primary">Confirmer update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($ba_bec_recaptchaSiteKey)): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $ba_bec_recaptchaSiteKeyEscaped; ?>"></script>
<?php endif; ?>
<!-- JS POUR CACHER/AFFICHER MDP-->
<script>
    document.getElementById('afficher').addEventListener("click", function () {
        let passInput = document.getElementById('passMemb');
        passInput.type = (passInput.type === 'password') ? 'text' : 'password';
    });

    document.getElementById('afficher2').addEventListener("click", function () {
        let passInput2 = document.getElementById('passMemb2');
        passInput2.type = (passInput2.type === 'password') ? 'text' : 'password';
    });

    (function () {
        var form = document.querySelector('form');
        var tokenInput = document.getElementById('g-recaptcha-response-update');
        var siteKey = '<?php echo $ba_bec_recaptchaSiteKeyEscaped; ?>';
        if (!form || !tokenInput || !siteKey || typeof grecaptcha === 'undefined') {
            return;
        }

        var isSubmitting = false;
        form.addEventListener('submit', function (event) {
            if (isSubmitting) {
                return;
            }
            event.preventDefault();
            if (typeof grecaptcha === 'undefined') {
                form.submit();
                return;
            }
            grecaptcha.ready(function () {
                grecaptcha.execute(siteKey, {action: 'update'})
                    .then(function (token) {
                        tokenInput.value = token;
                        isSubmitting = true;
                        form.submit();
                    });
            });
        });
    })();
</script>
