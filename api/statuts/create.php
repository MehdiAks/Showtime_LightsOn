<?php
/*
 * Endpoint API: api/statuts/create.php
 * Rôle: crée un(e) statut en base.
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

$ba_bec_libStat = ctrlSaisies($_POST['libStat'] ?? '');

if ($ba_bec_libStat === '') {
    http_response_code(400);
    echo "Le nom du statut est requis.";
    exit;
}

$ba_bec_currentMax = sql_select('STATUT', 'MAX(numStat) AS maxStat');
$ba_bec_nextNumStat = 1;
if (!empty($ba_bec_currentMax) && isset($ba_bec_currentMax[0]['maxStat'])) {
    $ba_bec_nextNumStat = (int)$ba_bec_currentMax[0]['maxStat'] + 1;
}

$ba_bec_result = sql_insert('STATUT', 'numStat, libStat', "'$ba_bec_nextNumStat', '$ba_bec_libStat'");
if ($ba_bec_result['success']) {
    flash_success();
} else {
    flash_error();
}

header('Location: ../../views/backend/statuts/list.php');

?>