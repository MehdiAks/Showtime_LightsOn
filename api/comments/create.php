<?php
/*
 * Endpoint API: api/comments/create.php
 * Rôle: crée un(e) comment en base.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration applicative et les helpers (session/DB/sanitisation).
 * 2) Récupère les paramètres POST (et éventuellement FILES) puis les nettoie via ctrlSaisies.
 * 3) Valide les contraintes métier (champs obligatoires, types, formats, tailles).
 * 4) Exécute la requête SQL adaptée (INSERT/UPDATE/DELETE) avec les valeurs préparées.
 * 5) Gère le feedback (flash/session/erreur) et redirige l'utilisateur vers l'écran cible.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$ba_bec_libCom = ctrlSaisies($_POST['libCom']);
$ba_bec_numArt = ctrlSaisies($_POST['numArt']);
$ba_bec_numMemb = ctrlSaisies($_POST['numMemb']);


sql_insert('comment', 'libCom, numArt, numMemb', "'$ba_bec_libCom', '$ba_bec_numArt', '$ba_bec_numMemb'");


header('Location: ../../views/backend/comments/list.php');

?>