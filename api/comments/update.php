<?php
/*
 * Endpoint API: api/comments/update.php
 * Rôle: met à jour un(e) comment existant(e).
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
$ba_bec_libCom = ctrlSaisies($_POST['libCom']);
$ba_bec_delLogiq = ctrlSaisies($_POST['delLogiq']);
$ba_bec_attModOK = ctrlSaisies($_POST['attModOK']);
$ba_bec_notifComKOAff = ctrlSaisies($_POST['notifComKOAff']);


//sql_delete('STATUT', "numStat = $numStat");
sql_update('comment', "libCom = '$ba_bec_libCom'", "numCom = $ba_bec_numCom");
sql_update('comment', "delLogiq = '$ba_bec_delLogiq'", "numCom = $ba_bec_numCom");
sql_update('comment', "attModOK = '$ba_bec_attModOK'", "numCom = $ba_bec_numCom");
sql_update('comment', "notifComKOAff = '$ba_bec_notifComKOAff'", "numCom = $ba_bec_numCom");

header('Location: ../../views/backend/comments/list.php');

?>
