<?php
/*
 * Vue d'administration (suppression) pour le module members.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

// Récupération des erreurs flash
$ba_bec_errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
$ba_bec_recaptchaSiteKey = getenv('RECAPTCHA_SITE_KEY');
$ba_bec_recaptchaSiteKeyEscaped = htmlspecialchars($ba_bec_recaptchaSiteKey ?? '', ENT_QUOTES, 'UTF-8');

if(isset($_GET['numMemb'])){
    $ba_bec_numMemb = $_GET['numMemb'];
    $ba_bec_member = sql_select('MEMBRE', '*', "numMemb = '$ba_bec_numMemb'")[0];
    $ba_bec_pseudoMemb = $ba_bec_member['pseudoMemb'];
    $ba_bec_prenomMemb = $ba_bec_member['prenomMemb'];
    $ba_bec_nomMemb = $ba_bec_member['nomMemb'];
    $ba_bec_eMailMemb = $ba_bec_member['eMailMemb'];
    $ba_bec_dtCreaMemb = $ba_bec_member['dtCreaMemb'];
    $ba_bec_numStat = $ba_bec_member['numStat'];
?>
<!-- Formulaire Bootstrap pour supprimer un membre -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression du membre</h1>
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
            <!-- Formulaire pour supprimer le membre -->
            <form action="<?php echo ROOT_URL . '/api/members/delete.php' ?>" method="post">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-delete">
                <div class="form-group">
                    <!-- NUM -->
                    <label for="numMemb">Numéro du membre</label>
                    <input id="numMemb" name="numMemb" class="form-control" style="display: none" type="text" value="<?php echo($ba_bec_numMemb); ?>" readonly="readonly" />
                    <!-- PRENOM -->
                    <label for="prenomMemb">Prénom du membre</label>
                    <input id="prenomMemb" name="prenomMemb" class="form-control" type="text" value="<?php echo($ba_bec_prenomMemb); ?>" readonly="readonly" disabled />
                    <!-- NOM -->
                    <label for="nomMemb">Nom du membre</label>
                    <input id="nomMemb" name="nomMemb" class="form-control" type="text" value="<?php echo($ba_bec_nomMemb); ?>" readonly="readonly" disabled />
                    <!-- NOM D'UTILISATEUR -->
                    <label for="pseudoMemb">Nom d'utilisateur du membre</label>
                    <input id="pseudoMemb" name="pseudoMemb" class="form-control" type="text" value="<?php echo($ba_bec_pseudoMemb); ?>" readonly="readonly" disabled />
                    <!-- MAIL -->
                    <label for="eMailMemb">Adresse e-mail du membre</label>
                    <input id="eMailMemb" name="eMailMemb" class="form-control" type="text" value="<?php echo($ba_bec_eMailMemb); ?>" readonly="readonly" disabled />
                    <!-- DATE CREA -->
                    <label for="dtCreaMemb">Date de création du membre</label>
                    <input id="dtCreaMemb" name="dtCreaMemb" class="form-control" type="text" value="<?php echo($ba_bec_dtCreaMemb); ?>" readonly="readonly" disabled />
                    <!-- STATUT -->
                    <label for="numStat">Statut du membre</label>
                    <input id="statutMemb" name="statutMemb" class="form-control" type="text" value="<?php 
                        if ($ba_bec_numStat == '1'){
                            echo 'Administrateur';
                        } 
                        if ($ba_bec_numStat == '2'){
                            echo 'Modérateur';
                        }
                        if ($ba_bec_numStat == '3'){
                            echo 'Membre';
                        }
                     ?>" readonly="readonly" disabled />
                     <input id="idMemb" name="idMemb" class="form-control" style="display: none" type="text" value="<?php echo($ba_bec_numStat); ?>" readonly="readonly" />
                </div>
                <br />
            <?php 
                if ($ba_bec_numStat == 1){
                    echo '<p>Un administrateur ne peut pas être supprimé.</p>';
                } else { ?>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr(e) de vouloir supprimer ce membre ?')">Confirmer la suppression</button>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
</div> 
<?php
} else {
    header('Location: list.php');
    exit();
}
?>

<?php if (!empty($ba_bec_recaptchaSiteKey)): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $ba_bec_recaptchaSiteKeyEscaped; ?>"></script>
<?php endif; ?>
<script>
    (function () {
        var form = document.querySelector('form');
        var tokenInput = document.getElementById('g-recaptcha-response-delete');
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
                grecaptcha.execute(siteKey, {action: 'delete'})
                    .then(function (token) {
                        tokenInput.value = token;
                        isSubmitting = true;
                        form.submit();
                    });
            });
        });
    })();
</script>
