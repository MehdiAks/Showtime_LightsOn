<?php
/*
 * Endpoint API: api/keywords/update.php
 * Rôle: met à jour un(e) keyword existant(e).
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

$ba_bec_numMotCle = ctrlSaisies($_POST['numMotCle']);
$ba_bec_libMotCle = ctrlSaisies($_POST['libMotCle']);

//sql_delete('STATUT', "numMotCle = $numMotCle");
$ba_bec_result = sql_update(table: 'MOTCLE', attributs: 'libMotCle = "'.$ba_bec_libMotCle.'"' , where: "numMotCle = $ba_bec_numMotCle");
if ($ba_bec_result['success']) {
    flash_success();
} else {
    flash_error();
}

header(header: 'Location: ../../views/backend/keywords/list.php');

?>