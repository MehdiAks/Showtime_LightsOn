<?php
/*
 * Endpoint API: api/thematiques/delete.php
 * Rôle: supprime (ou marque supprimé) un(e) thematique.
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

$ba_bec_numThem = ctrlSaisies($_POST['numThem']);

// Vérifie si le statut est utilisé
$ba_bec_countnumThem = sql_select("ARTICLE", "COUNT(*) AS total", "numThem = $ba_bec_numThem")[0]['total'];
if ($ba_bec_countnumThem > 0) {
    // Redirection avec message d'erreur
    flash_delete_impossible();
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}

// Si le statut n'est pas utilisé, suppression
$ba_bec_result = sql_delete('THEMATIQUE', "numThem = $ba_bec_numThem");
if ($ba_bec_result['success']) {
    flash_success();
} else {
    flash_error();
}

header('Location: ../../views/backend/thematiques/list.php');
exit;

?>
