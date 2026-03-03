<?php
/*
 * Endpoint API: api/matches/delete.php
 * Rôle: supprime (ou marque supprimé) un(e) matche.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration applicative et les helpers (session/DB/sanitisation).
 * 2) Récupère les paramètres POST (et éventuellement FILES) puis les nettoie via ctrlSaisies.
 * 3) Valide les contraintes métier (champs obligatoires, types, formats, tailles).
 * 4) Exécute la requête SQL adaptée (INSERT/UPDATE/DELETE) avec les valeurs préparées.
 * 5) Gère le feedback (flash/session/erreur) et redirige l'utilisateur vers l'écran cible.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

sql_connect();

$ba_bec_numMatch = (int) ($_POST['numMatch'] ?? 0);
if ($ba_bec_numMatch > 0) {
    $deleteStmt = $DB->prepare('DELETE FROM `MATCH` WHERE numMatch = :numMatch');
    $deleteStmt->execute([':numMatch' => $ba_bec_numMatch]);
}

header('Location: ../../views/backend/matches/list.php');
