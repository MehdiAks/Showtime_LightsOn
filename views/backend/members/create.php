<?php
/*
 * Vue d'administration (création) pour le module members.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';


if (isset($_GET['numCom'])) {
    $ba_bec_numCom = $_GET['numCom'];
    $ba_bec_comment = sql_select('comment', '*', "numCom ='$ba_bec_numCom'")[0];
    $ba_bec_pseudoMemb = $ba_bec_comment['pseudoMemb'];
    $ba_bec_numArt = $ba_bec_comment['numArt'];
    $ba_bec_libCom = $ba_bec_comment['libCom'];
} else {
    header('/index.php');
}

$ba_bec_recaptchaSiteKey = getenv('RECAPTCHA_SITE_KEY');
$ba_bec_recaptchaSiteKeyEscaped = htmlspecialchars($ba_bec_recaptchaSiteKey ?? '', ENT_QUOTES, 'UTF-8');

?>

<!-- Bootstrap form to create a new member -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouveau Membre</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new member -->
            <form action="<?php echo ROOT_URL . '/api/members/create.php' ?>" method="post" id="formCreate">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-create">
                <div class="form-group">
                    <!-- NOM D'UTILISATEUR -->

                    <label for="pseudoMemb">Nom d'utilisateur du membre (non modifiable)</label>
                    <input id="pseudoMemb" name="pseudoMemb" class="form-control" type="text" autofocus="autofocus"
                        placeholder="Pseudo (ex: leo32)" />
                    <p>(entre 6 et 70 caractères)</p>
                    <!-- PRENOM -->
                    <label for="prenomMemb">Prénom du membre</label>
                    <input id="prenomMemb" name="prenomMemb" class="form-control" type="text" autofocus="autofocus"
                        placeholder="Prénom (ex: Léa)" />
                    <!-- NOM -->
                    <label for="nomMemb">Nom du membre</label>
                    <input id="nomMemb" name="nomMemb" class="form-control" type="text" autofocus="autofocus"
                        placeholder="Nom (ex: Martin)" />
                    <!-- MDP -->
                    <label for="passMemb">Mot de passe du membre</label>
                    <input id="passMemb" name="passMemb" class="form-control" type="password" autofocus="autofocus" />
                    <p>(Entre 8 et 15 car., au moins une majuscule, une minuscule, un chiffre et un caractère spécial)</p>
                    <button type="button" id="afficher" class="btn btn-secondary">Afficher le mot de
                        passe</button><br><br>
                    <!-- MDP VERIFICATION -->
                    <label for="passMemb2">Confirmez mot de passe du membre</label>
                    <input id="passMemb2" name="passMemb2" class="form-control" type="password" autofocus="autofocus" />
                    <p>(Entre 8 et 15 car., au moins une majuscule, une minuscule, un chiffre et un caractère spécial)</p>
                    <button type="button" id="afficher2" class="btn btn-secondary">Afficher le mot de
                        passe</button><br><br>
                    <!-- EMAIL -->
                    <label for="eMailMemb">Email du membre</label>
                    <input id="eMailMemb" name="eMailMemb" class="form-control" type="email" autofocus="autofocus"
                        placeholder="prenom.nom@example.com" />
                    <!-- EMAIL VERIFICATION -->
                    <label for="eMailMemb2">Confirmez email du membre</label>
                    <input id="eMailMemb2" name="eMailMemb2" class="form-control" type="email" autofocus="autofocus"
                        placeholder="prenom.nom@example.com" />
                    <!-- PARTAGE DES DONNEES -->
                    <label for="accordMemb">J'accepte que mes données soient conservées :</label>
                    <input type="radio" id="accordMemb" name="accordMemb" value="OUI" />
                    <label for="accordMemb">Oui</label>
                    <input type="radio" id="accordMemb" name="accordMemb" value="NON" checked />
                    <label for="accordMemb">Non</label>
                    <br><br>
                    <!-- STATUT -->
                    <label for="numStat">Statut :</label>
                    <select name="numStat" id="numStat">
                        <option value="1" <?= ($ba_bec_numStat == 1) ? 'selected' : '' ?>>Administrateur</option>
                        <option value="2" <?= ($ba_bec_numStat == 2) ? 'selected' : '' ?>>Modérateur</option>
                        <option value="3" <?= ($ba_bec_numStat == 3) ? 'selected' : '' ?>>Membre</option>
                    </select>
                </div>
                <br />
                <div class="form-group mt-2">
                    <button type="submit" class="btn btn-primary">Confirmer create ?</button>
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

        attribut = document.getElementById('passMemb').getAttribute('type');
        if (attribut == 'password') {
            document.getElementById('passMemb').setAttribute('type', 'text');
        } else {
            document.getElementById('passMemb').setAttribute('type', 'password');
        }

    });

    document.getElementById('afficher2').addEventListener("click", function () {

        attribut = document.getElementById('passMemb2').getAttribute('type');
        if (attribut == 'password') {
            document.getElementById('passMemb2').setAttribute('type', 'text');
        } else {
            document.getElementById('passMemb2').setAttribute('type', 'password');
        }

    });

    (function () {
        var form = document.getElementById('formCreate');
        var tokenInput = document.getElementById('g-recaptcha-response-create');
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
                grecaptcha.execute(siteKey, {action: 'create'})
                    .then(function (token) {
                        tokenInput.value = token;
                        isSubmitting = true;
                        form.submit();
                    });
            });
        });
    })();

</script>
