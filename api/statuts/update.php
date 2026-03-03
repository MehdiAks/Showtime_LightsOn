<?php
/*
 * Endpoint API: api/statuts/update.php
 * Rôle: met à jour un(e) statut existant(e).
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

$ba_bec_numStat = ctrlSaisies($_POST['numStat']);
$ba_bec_libStat = ctrlSaisies($_POST['libStat']);

//sql_delete('STATUT', "numStat = $numStat");
$ba_bec_result = sql_update(table: 'STATUT', attributs: 'libStat = "'.$ba_bec_libStat.'"' , where: "numStat = $ba_bec_numStat");
if ($ba_bec_result['success']) {
    flash_success();
} else {
    flash_error();
}

header(header: 'Location: ../../views/backend/statuts/list.php');

?>