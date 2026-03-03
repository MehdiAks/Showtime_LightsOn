<?php
/*
 * Endpoint API: api/account/delete-comment.php
 * Rôle: permet à un membre connecté de masquer (suppression logique) un commentaire depuis son compte.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration applicative (session, DB, constantes) pour accéder à ROOT_URL et aux helpers SQL.
 * 2) Vérifie l'authentification via la session; si absent, redirige vers la page de connexion.
 * 3) Valide la méthode HTTP (POST attendu) puis la présence d'un identifiant de commentaire numérique.
 * 4) Vérifie que le commentaire appartient bien au membre connecté avant toute action.
 * 5) Marque le commentaire comme supprimé (suppression logique) et notifie l'utilisateur via la session.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Étape 1: récupérer l'identifiant membre depuis la session.
$ba_bec_numMemb = $_SESSION['user_id'] ?? null;
if (!$ba_bec_numMemb) {
    $_SESSION['error'] = 'Vous devez être connecté pour supprimer un commentaire.';
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    exit();
}

// Étape 2: bloquer les requêtes non POST pour éviter les suppressions involontaires.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Requête invalide.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 3: récupérer l'identifiant du commentaire et s'assurer qu'il est valide.
$ba_bec_numCom = isset($_POST['numCom']) ? (int) $_POST['numCom'] : 0;
if ($ba_bec_numCom <= 0) {
    $_SESSION['error'] = 'Commentaire introuvable.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 4: vérifier que le commentaire appartient bien au membre courant.
$ba_bec_comment = sql_select('comment', 'numCom', "numCom = $ba_bec_numCom AND numMemb = $ba_bec_numMemb")[0] ?? null;
if (!$ba_bec_comment) {
    $_SESSION['error'] = 'Vous ne pouvez pas supprimer ce commentaire.';
    header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
    exit();
}

// Étape 5: suppression logique (flag + date) et confirmation utilisateur.
sql_update('comment', "delLogiq = 1, dtDelLogCom = NOW()", "numCom = $ba_bec_numCom AND numMemb = $ba_bec_numMemb");
$_SESSION['success'] = 'Votre commentaire a été masqué.';
header('Location: ' . ROOT_URL . '/Pages_supplementaires/compte.php');
exit();

?>
