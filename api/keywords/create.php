<?php
/*
 * Endpoint API: api/keywords/create.php
 * Rôle: crée un(e) keyword en base.
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

$ba_bec_libMotCle = ctrlSaisies($_POST['libMotCle'] ?? '');

if ($ba_bec_libMotCle === '') {
    http_response_code(400);
    echo "Le nom du mot-clé est requis.";
    exit;
}

$ba_bec_nextNumMotCle = sql_select('MOTCLE', 'COALESCE(MAX(numMotCle), 0) + 1 AS nextNumMotCle');
$ba_bec_numMotCle = $ba_bec_nextNumMotCle[0]['nextNumMotCle'];

$ba_bec_result = sql_insert('MOTCLE', 'numMotCle, libMotCle', "$ba_bec_numMotCle, '$ba_bec_libMotCle'");
if ($ba_bec_result['success']) {
    flash_success();
} else {
    flash_error();
}

header('Location: ../../views/backend/keywords/list.php');

?>