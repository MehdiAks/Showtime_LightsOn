<?php
/*
 * Vue d'administration (authentification/inscription).
 * - Cette page expose un formulaire de sécurité pour se connecter, s'inscrire ou réinitialiser un mot de passe.
 * - Les champs sont validés via les attributs HTML et envoyés vers la route d'authentification dédiée.
 * - Les messages d'aide guident l'utilisateur sur la procédure à suivre.
 * - La vue reste passive : elle ne fait que collecter les données et afficher les retours serveur.
 */
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/signup.css',
];

include '../../../header.php';

$ba_bec_signupDisabled = true;
$ba_bec_signupDisabledMessage = $_SESSION['signup_disabled_message'] ?? 'La création de compte est pour le moment désactivée.';

// Récupération des données de session
$ba_bec_errors = $_SESSION['errors'] ?? [];
$ba_bec_old = $_SESSION['old'] ?? [];
$ba_bec_recaptchaSiteKey = getenv('RECAPTCHA_SITE_KEY');
$ba_bec_recaptchaSiteKeyEscaped = htmlspecialchars($ba_bec_recaptchaSiteKey ?? '', ENT_QUOTES, 'UTF-8');

// Nettoyage des données de session après récupération
unset($_SESSION['signup_disabled_message'], $_SESSION['errors'], $_SESSION['old']);
?>

<main class="auth-page">
    <section class="auth-card">
        <h1>Créer mon compte</h1>

        <?php if ($ba_bec_signupDisabled): ?>
            <div class="alert alert-warning" role="alert">
                <?= htmlspecialchars($ba_bec_signupDisabledMessage) ?>
            </div>
        <?php endif; ?>

        <div class="container mb-4">
            <?php if (!empty($ba_bec_errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-2">
                        <?php foreach ($ba_bec_errors as $ba_bec_error): ?>
                            <?= htmlspecialchars($ba_bec_error) ?><br>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <form action="<?php echo ROOT_URL . '/api/security/signup.php' ?>" method="post" class="auth-form">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-signup">
            <div class="signup-grid">
                <!-- Prénom -->
                <div class="champ">
                    <label for="prenomMemb">Prénom :</label>
                    <input type="text" id="prenomMemb" name="prenomMemb" value="<?= htmlspecialchars($ba_bec_old['prenomMemb'] ?? '') ?>" required>
                </div>

                <!-- Nom -->
                <div class="champ">
                    <label for="nomMemb">Nom :</label>
                    <input type="text" id="nomMemb" name="nomMemb" value="<?= htmlspecialchars($ba_bec_old['nomMemb'] ?? '') ?>" required>
                </div>

                <!-- Nom d'utilisateur -->
                <div class="champ full">
                    <label for="pseudoMemb" placeholder="6 à 70 caractères">Nom d'utilisateur</label>
                    <input type="text"
                            id="pseudoMemb"
                            name="pseudoMemb"
                            value="<?= htmlspecialchars($ba_bec_old['pseudoMemb'] ?? '') ?>"
                            required>
                    <small class="form-text text-muted">6 à 70 caractères</small>
                </div>

                <!-- Email -->
                <div class="champ full">
                    <label for="eMailMemb">Email :</label>
                    <input type="email" id="eMailMemb" name="eMailMemb" value="<?= htmlspecialchars($ba_bec_old['eMailMemb'] ?? '') ?>" required>
                </div>

                <!-- Confirmation Email -->
                <div class="champ full offset-right">
                    <label for="eMailMemb2">Confirmer l'email :</label>
                    <input type="email" id="eMailMemb2" name="eMailMemb2" value="<?= htmlspecialchars($ba_bec_old['eMailMemb2'] ?? '') ?>" required>
                </div>

                <!-- Mot de passe -->
                <div class="champ full">
                    <label for="passMemb">Mot de passe :</label>
                    <div class="input-with-icon">
                        <input type="password" id="passMemb" name="passMemb" required>
                        <button
                            class="password-toggle"
                            type="button"
                            data-target="passMemb"
                            aria-label="Afficher le mot de passe"
                        >
                            <span class="icon icon-closed">Afficher</span>
                            <span class="icon icon-open">Masquer</span>
                        </button>
                    </div>
                    <small class="form-text text-muted">Entre 8 et 15 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial</small>
                </div>

                <!-- Confirmation mot de passe -->
                <div class="champ full offset-right">
                    <label for="passMemb2">Confirmation du mot de passe :</label>
                    <div class="input-with-icon">
                        <input type="password" id="passMemb2" name="passMemb2" required>
                        <button
                            class="password-toggle"
                            type="button"
                            data-target="passMemb2"
                            aria-label="Afficher le mot de passe"
                        >
                            <span class="icon icon-closed">Afficher</span>
                            <span class="icon icon-open">Masquer</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Accord données -->
            <div class="champ checkbox-row">
                <label for="accordMemb">J'accepte la conservation de mes données conformément au RGPD, aux CGU et aux obligations légales.</label>
                <input type="checkbox" id="accordMemb" name="accordMemb" value="1" <?= isset($ba_bec_old['accordMemb']) ? 'checked' : '' ?> required>
            </div>
            <!-- Boutons -->
            <div class="btn-se-connecter">
                <button type="submit" <?= $ba_bec_signupDisabled ? "disabled" : ""; ?>><?= $ba_bec_signupDisabled ? "Création de compte désactivée" : "Créer mon compte"; ?></button>
            </div>

            <p>Vous possédez déjà un compte ? <a href="/views/backend/security/login.php" class="link">Se connecter</a></p>

        </form>
    </section>

</main>
<?php if (!empty($ba_bec_recaptchaSiteKey)): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $ba_bec_recaptchaSiteKeyEscaped; ?>"></script>
<?php endif; ?>
<script>
    document.querySelectorAll('.password-toggle').forEach((button) => {
        const input = document.getElementById(button.dataset.target);
        const show = () => {
            input.type = 'text';
            button.classList.add('is-visible');
        };
        const hide = () => {
            input.type = 'password';
            button.classList.remove('is-visible');
        };

        button.addEventListener('pointerdown', (event) => {
            event.preventDefault();
            show();
        });
        button.addEventListener('pointerleave', hide);
        button.addEventListener('pointercancel', hide);
        button.addEventListener('keydown', (event) => {
            if (event.code === 'Space' || event.code === 'Enter') {
                show();
            }
        });
        button.addEventListener('keyup', hide);

        document.addEventListener('pointerup', hide);
        document.addEventListener('pointercancel', hide);
        document.addEventListener('touchend', hide);
    });
</script>
<script>
    (function () {
        var form = document.querySelector('.auth-form');
        var tokenInput = document.getElementById('g-recaptcha-response-signup');
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
                grecaptcha.execute(siteKey, {action: 'signup'})
                    .then(function (token) {
                        tokenInput.value = token;
                        isSubmitting = true;
                        form.submit();
                    });
            });
        });
    })();
</script>
