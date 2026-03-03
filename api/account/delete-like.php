<?php
/*
 * Endpoint API: api/account/delete-like.php
 * Rôle: permettre à un membre connecté de retirer un like associé à un article.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration (session/DB) pour accéder aux helpers et à ROOT_URL.
 * 2) Vérifie l'authentification via la session puis bloque les requêtes non POST.
 * 3) Valide l'identifiant d'article reçu et vérifie que le like appartient au membre courant.
 * 4) Supprime l'entrée LIKEART correspondante.
 * 5) Retourne l'utilisateur vers sa page compte avec un message de confirmation.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Étape 1: vérifier l'authentification.
$ba_bec_numMemb = $_SESSION['user_id'] ?? null;
if (!$ba_bec_numMemb) {
    $_SESSION['error'] = 'Vous devez être connecté pour supprimer un like.';
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit();
}

// Étape 2: vérifier la méthode HTTP.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Requête invalide.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 3: sécuriser l'identifiant d'article transmis.
$ba_bec_numArt = isset($_POST['numArt']) ? (int) $_POST['numArt'] : 0;
if ($ba_bec_numArt <= 0) {
    $_SESSION['error'] = 'Like introuvable.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 4: vérifier la propriété du like avant suppression.
$ba_bec_like = sql_select('LIKEART', 'numArt', "numArt = $ba_bec_numArt AND numMemb = $ba_bec_numMemb")[0] ?? null;
if (!$ba_bec_like) {
    $_SESSION['error'] = 'Vous ne pouvez pas supprimer ce like.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 5: suppression et retour utilisateur.
sql_delete('LIKEART', "numArt = $ba_bec_numArt AND numMemb = $ba_bec_numMemb");
$_SESSION['success'] = 'Votre like a été supprimé.';
header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
exit();

?>
