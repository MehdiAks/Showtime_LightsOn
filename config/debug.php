<?php
// Active l'affichage des erreurs à l'écran (utile en développement).
ini_set('display_errors', 1);
// Active l'affichage des erreurs survenues au démarrage de PHP.
ini_set('display_startup_errors', 1);
// Active le reporting complet, sauf les notices (E_NOTICE).
error_reporting(E_ALL & ~E_NOTICE);
?>
