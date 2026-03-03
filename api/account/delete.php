<?php
/*
 * Endpoint API: api/account/delete.php
 * Rôle: supprimer le compte d'un membre connecté (avec nettoyage des données liées).
 *
 * Déroulé détaillé:
 * 1) Charge la configuration pour accéder aux helpers SQL et à la vérification reCAPTCHA.
 * 2) Vérifie l'authentification et la méthode HTTP pour éviter les suppressions par GET.
 * 3) Exige une confirmation explicite + un reCAPTCHA valide avant d'aller plus loin.
 * 4) Vérifie l'existence du membre en base puis supprime les données dépendantes.
 * 5) Réinitialise la session et redirige vers la page de connexion.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Étape 1: récupérer l'identifiant du membre connecté.
$ba_bec_numMemb = $_SESSION['user_id'] ?? null;
if (!$ba_bec_numMemb) {
    $_SESSION['error'] = 'Vous devez être connecté pour supprimer votre compte.';
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit();
}

// Étape 2: imposer une requête POST pour la suppression.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Requête invalide.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 3: validation de la confirmation utilisateur.
$ba_bec_confirm = isset($_POST['confirmDeleteAccount']) ? (int) $_POST['confirmDeleteAccount'] : 0;
if ($ba_bec_confirm !== 1) {
    $_SESSION['error'] = 'Vous devez confirmer la suppression de votre compte.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 4: valider le reCAPTCHA dédié à l'action sensible.
$ba_bec_recaptcha = verifyRecaptcha($_POST['g-recaptcha-response'] ?? '', 'delete-account');
if (!$ba_bec_recaptcha['valid']) {
    $_SESSION['error'] = $ba_bec_recaptcha['message'] ?: 'Échec de la vérification reCAPTCHA.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 5: vérifier l'existence du membre puis supprimer ses données associées.
$ba_bec_member = sql_select('MEMBRE', 'numMemb', "numMemb = $ba_bec_numMemb")[0] ?? null;
if (!$ba_bec_member) {
    $_SESSION['error'] = 'Compte introuvable.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Suppressions en cascade côté applicatif: likes, commentaires, puis le membre.
sql_delete('LIKEART', "numMemb = $ba_bec_numMemb");
sql_delete('comment', "numMemb = $ba_bec_numMemb");
sql_delete('MEMBRE', "numMemb = $ba_bec_numMemb");

// Nettoyage de session et redirection finale.
$_SESSION = [];
$_SESSION['success'] = 'Votre compte a bien été supprimé.';
header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
exit();

?>
