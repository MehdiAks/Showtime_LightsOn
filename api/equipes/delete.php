<?php
/*
 * Endpoint API: api/equipes/delete.php
 * Rôle: supprime (ou marque supprimé) un(e) equipe.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration applicative et les helpers (session/DB/sanitisation).
 * 2) Récupère les paramètres POST (et éventuellement FILES) puis les nettoie via ctrlSaisies.
 * 3) Valide les contraintes métier (champs obligatoires, types, formats, tailles).
 * 4) Exécute la requête SQL adaptée (INSERT/UPDATE/DELETE) avec les valeurs préparées.
 * 5) Gère le feedback (flash/session/erreur) et redirige l'utilisateur vers l'écran cible.
 */
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ba_bec_numEquipe = (int) ($_POST['numEquipe'] ?? 0);

    if ($ba_bec_numEquipe > 0) {
        $ba_bec_result = sql_delete('EQUIPE', "numEquipe = '$ba_bec_numEquipe'");
        if ($ba_bec_result['success']) {
            flash_success();
        } elseif (!empty($ba_bec_result['constraint']) || sql_is_foreign_key_error($ba_bec_result['message'] ?? '', $ba_bec_result['code'] ?? null)) {
            flash_delete_impossible('Suppression impossible : cette équipe est utilisée dans d’autres tables (joueurs, matchs, staff).');
        } else {
            flash_error();
        }
    }

    header('Location: ../../views/backend/equipes/list.php');
    exit();
}
?>
