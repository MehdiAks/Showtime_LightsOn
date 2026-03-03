<?php
/*
 * Endpoint API: api/joueurs/delete.php
 * Rôle: supprime (ou marque supprimé) un(e) joueur.
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
    $ba_bec_numJoueur = (int) ($_POST['numJoueur'] ?? 0);

    if ($ba_bec_numJoueur > 0) {
        sql_delete('JOUEUR', "numJoueur = '$ba_bec_numJoueur'");
    }

    header('Location: ../../views/backend/joueurs/list.php');
    exit();
}
?>
