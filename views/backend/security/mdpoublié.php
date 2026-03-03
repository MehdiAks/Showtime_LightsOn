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
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

$ba_bec_email = '';
$ba_bec_errorEmail = '';
$ba_bec_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ba_bec_email = ctrlSaisies($_POST['email'] ?? '');

    if (empty($ba_bec_email)) {
        $ba_bec_errorEmail = "L'adresse email est requise.";
    } elseif (!filter_var($ba_bec_email, FILTER_VALIDATE_EMAIL)) {
        $ba_bec_errorEmail = "L'adresse email n'est pas valide.";
    } else {
        $ba_bec_success = "Si un compte est associé à cet email, un lien de réinitialisation vient d'être envoyé.";
    }
}

$pageStyles = [
    ROOT_URL . '/src/css/login.css',
];

include '../../../header.php';
?>

<main class="auth-page">
    <section class="auth-card">
        <h1>Mot de passe oublié</h1>
        <p class="text-muted text-center">Indiquez votre adresse email pour recevoir un lien de réinitialisation.</p>

        <?php if (!empty($ba_bec_success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($ba_bec_success) ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="auth-form">
            <div class="auth-stack">
                <div class="champ">
                    <label for="email">Adresse email :</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($ba_bec_email) ?>" required>
                    <?php if (!empty($ba_bec_errorEmail)): ?>
                        <div class="alert alert-danger mt-2"><?= htmlspecialchars($ba_bec_errorEmail) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="btn-se-connecter">
                <button type="submit">Envoyer le lien</button>
                <a href="/views/backend/security/login.php" class="link">Retour à la connexion</a>
            </div>
        </form>
    </section>
</main>
