<?php
/*
 * Endpoint API: api/security/signup.php
 * Rôle: créer un nouveau compte membre avec validations métier et sécurité.
 *
 * Déroulé détaillé:
 * 1) Démarre la session et charge la config + helpers de sanitisation.
 * 2) Récupère les champs de formulaire (identité, login, email) et initialise le tableau d'erreurs.
 * 3) Valide reCAPTCHA, contraintes sur pseudo/mot de passe/email et consentement RGPD.
 * 4) Si aucune erreur, hash le mot de passe puis insère le membre en base.
 * 5) Redirige vers login en succès ou vers le formulaire d'inscription en cas d'erreur.
 */
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$ba_bec_signupDisabled = true;
$ba_bec_signupDisabledMessage = 'La création de compte est pour le moment désactivée.';

if ($ba_bec_signupDisabled) {
    $_SESSION['signup_disabled_message'] = $ba_bec_signupDisabledMessage;
    $_SESSION['old'] = $_POST;
    header('Location: ../../../views/backend/security/signup.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['errors'] = [];
    $_SESSION['old'] = $_POST;

    // Récupération des données
    $ba_bec_nomMemb = ctrlSaisies($_POST['nomMemb'] ?? '');
    $ba_bec_prenomMemb = ctrlSaisies($_POST['prenomMemb'] ?? '');
    $ba_bec_pseudoMemb = ctrlSaisies($_POST['pseudoMemb'] ?? '');
    $ba_bec_passMemb = $_POST['passMemb'] ?? '';
    $ba_bec_passMemb2 = $_POST['passMemb2'] ?? '';
    $ba_bec_eMailMemb = ctrlSaisies($_POST['eMailMemb'] ?? '');
    $ba_bec_eMailMemb2 = ctrlSaisies($_POST['eMailMemb2'] ?? '');
    $ba_bec_accordMemb = isset($_POST['accordMemb']) ? 1 : 0;
    $ba_bec_numStat = 3;

    // Vérification anti-bot via reCAPTCHA.
    $ba_bec_recaptcha = verifyRecaptcha($_POST['g-recaptcha-response'] ?? '', 'signup');
    if (!$ba_bec_recaptcha['valid']) {
        $_SESSION['errors'][] = $ba_bec_recaptcha['message'] ?: 'Échec de la vérification reCAPTCHA.';
    }

    // Validation prénom et nom
    if (empty($ba_bec_prenomMemb)) {
        $_SESSION['errors'][] = 'Le prénom est obligatoire';
    }

    if (empty($ba_bec_nomMemb)) {
        $_SESSION['errors'][] = 'Le nom est obligatoire';
    }

    // Validation nom d'utilisateur
    if (strlen($ba_bec_pseudoMemb) < 6 || strlen($ba_bec_pseudoMemb) > 70) {
        $_SESSION['errors'][] = "Le nom d'utilisateur doit contenir entre 6 et 70 caractères";
    } elseif (!empty(sql_select('MEMBRE', 'pseudoMemb', "pseudoMemb = '$ba_bec_pseudoMemb'"))) {
        $_SESSION['errors'][] = "Nom d'utilisateur déjà utilisé";
    }

    // Validation mot de passe
    if (strlen($ba_bec_passMemb) < 8 || strlen($ba_bec_passMemb) > 15) {
        $_SESSION['errors'][] = 'Le mot de passe doit contenir entre 8 et 15 caractères';
    } elseif (
        !preg_match('/[A-Z]/', $ba_bec_passMemb) ||
        !preg_match('/[a-z]/', $ba_bec_passMemb) ||
        !preg_match('/[0-9]/', $ba_bec_passMemb) ||
        !preg_match('/[^a-zA-Z0-9]/', $ba_bec_passMemb)
    ) {
        $_SESSION['errors'][] = 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial';
    } elseif ($ba_bec_passMemb !== $ba_bec_passMemb2) {
        $_SESSION['errors'][] = 'Les mots de passe ne correspondent pas';
    }

    // Validation email
    if (!filter_var($ba_bec_eMailMemb, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors'][] = 'Email invalide';
    } elseif ($ba_bec_eMailMemb !== $ba_bec_eMailMemb2) {
        $_SESSION['errors'][] = 'Les emails ne correspondent pas';
    } elseif (!empty(sql_select('MEMBRE', 'eMailMemb', "eMailMemb = '$ba_bec_eMailMemb'"))) {
        $_SESSION['errors'][] = 'Email déjà utilisé';
    }

    // Validation accord
    if ($ba_bec_accordMemb !== 1) {
        $_SESSION['errors'][] = 'Vous devez accepter la conservation des données (RGPD) pour créer un compte.';
    }

    // Si aucune erreur
    if (empty($_SESSION['errors'])) {
        try {
            // Hash du mot de passe et génération de la date de création.
            $ba_bec_hashedPass = password_hash($ba_bec_passMemb, PASSWORD_DEFAULT);
            $ba_bec_dtCreaMemb = date('Y-m-d H:i:s');

            // Insertion du membre en base.
            sql_insert(
                'MEMBRE',
                'nomMemb, prenomMemb, pseudoMemb, passMemb, eMailMemb, dtCreaMemb, accordMemb, numStat',
                "'$ba_bec_nomMemb','$ba_bec_prenomMemb','$ba_bec_pseudoMemb','$ba_bec_hashedPass','$ba_bec_eMailMemb','$ba_bec_dtCreaMemb','$ba_bec_accordMemb','$ba_bec_numStat'"
            );

            // Redirection en succès.
            $_SESSION['success'] = 'Inscription réussie !';
            header('Location: ../../../views/backend/security/login.php');
            exit();
        } catch (Exception $ba_bec_e) {
            $_SESSION['errors'][] = 'Erreur technique : ' . $ba_bec_e->getMessage();
        }
    }

    // Redirection en cas d'erreur
    header('Location: ../../../views/backend/security/signup.php');
    exit();
}

?>
