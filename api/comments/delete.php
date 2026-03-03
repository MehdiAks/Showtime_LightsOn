<?php
/*
 * Endpoint API: api/comments/delete.php
 * Rôle: supprime (ou marque supprimé) un(e) comment.
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

$ba_bec_numCom = ctrlSaisies($_POST['numCom']);

sql_delete('comment', "numCom = $ba_bec_numCom");


header('Location: ../../views/backend/comments/list.php'); 

?>
