<?php
/*
 * Endpoint API: api/security/disconnect.php
 * Rôle: déconnecter l'utilisateur en détruisant sa session.
 *
 * Déroulé détaillé:
 * 1) Démarre la session PHP pour accéder aux données existantes.
 * 2) Vide toutes les variables de session puis détruit la session côté serveur.
 * 3) Redirige vers la page d'accueil.
 */
session_start(); 
// Étape 1: suppression des données de session.
session_unset();
session_destroy();

// Étape 2: redirection après déconnexion.
header("Location: /index.php");
exit();

?>
