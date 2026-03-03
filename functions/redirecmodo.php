<?php
// Récupère le statut utilisateur (numStat) depuis la session.
$ba_bec_numStat = $_SESSION['numStat'] ?? null;

// Si aucun statut en session, l'utilisateur n'est pas connecté.
if ($ba_bec_numStat === null) {
    // Redirige vers la page de login backend.
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    // Stoppe l'exécution du script.
    exit();
}

// Si l'utilisateur n'a pas le statut administrateur (1), on refuse l'accès.
if ((int)$ba_bec_numStat !== 1) {
    // Redirige vers la page de connexion pour l'authentification.
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    // Stoppe l'exécution pour empêcher l'accès au contenu protégé.
    exit();
}
?>
