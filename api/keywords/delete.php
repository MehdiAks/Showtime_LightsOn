<?php
/*
 * Endpoint API: api/keywords/delete.php
 * Rôle: supprime (ou marque supprimé) un(e) keyword.
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

// Vérifie si le statut est utilisé
$ba_bec_countnumMotCle = sql_select("MOTCLEARTICLE", "COUNT(*) AS total", "numMotCle = $ba_bec_numMotCle")[0]['total'];

if ($ba_bec_countnumMotCle > 0) {
    // Redirection avec message d'erreur
    flash_delete_impossible();
    header('Location: ../../views/backend/keywords/list.php');
    exit;
}

// Si le statut n'est pas utilisé, suppression
$ba_bec_result = sql_delete('MOTCLE', "numMotCle = $ba_bec_numMotCle");
if ($ba_bec_result['success']) {
    flash_success();
} else {
    flash_error();
}

header('Location: ../../views/backend/keywords/list.php');
exit;

?>
