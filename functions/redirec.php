<?php
// Récupère le statut utilisateur depuis la session (numStat).
$ba_bec_numStat = $_SESSION['numStat'] ?? null;

// Si aucun statut n'est défini, l'utilisateur n'est pas authentifié.
if ($ba_bec_numStat === null) {
    // Redirige vers la page de connexion backend.
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    // Stoppe l'exécution du script.
    exit();
}

// Si le statut n'est pas égal à 1 (administrateur), refuse l'accès.
if ((int)$ba_bec_numStat !== 1) {
    // Redirige également vers la page de connexion.
    header("Location: " . ROOT_URL . "/views/backend/security/login.php");
    // Stoppe l'exécution du script.
    exit();
}

?>
