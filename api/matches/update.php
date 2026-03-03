<?php
/*
 * Endpoint API: api/matches/update.php
 * Rôle: met à jour un(e) matche existant(e).
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

sql_connect();

$ba_bec_numMatch = (int) ($_POST['numMatch'] ?? 0);
$ba_bec_codeEquipe = ctrlSaisies($_POST['codeEquipe'] ?? '');
$ba_bec_saison = ctrlSaisies($_POST['saison'] ?? '');
$ba_bec_phase = ctrlSaisies($_POST['phase'] ?? '');
$ba_bec_journee = ctrlSaisies($_POST['journee'] ?? '');
$ba_bec_dateMatch = ctrlSaisies($_POST['dateMatch'] ?? '');
$ba_bec_heureMatch = ctrlSaisies($_POST['heureMatch'] ?? '');
$ba_bec_lieuMatch = ctrlSaisies($_POST['lieuMatch'] ?? '');
$ba_bec_clubAdversaire = ctrlSaisies($_POST['clubAdversaire'] ?? '');
$ba_bec_numeroEquipeAdverse = (int) ($_POST['numeroEquipeAdverse'] ?? 0);
$ba_bec_scoreBec = ctrlSaisies($_POST['scoreBec'] ?? '');
$ba_bec_scoreAdversaire = ctrlSaisies($_POST['scoreAdversaire'] ?? '');

if ($ba_bec_numMatch <= 0 || $ba_bec_codeEquipe === '' || $ba_bec_saison === '' || $ba_bec_phase === '' || $ba_bec_journee === '' || $ba_bec_dateMatch === '' || $ba_bec_clubAdversaire === '') {
    header('Location: ../../views/backend/matches/edit.php?numMatch=' . $ba_bec_numMatch . '&error=missing');
    exit;
}

$ba_bec_heureValue = $ba_bec_heureMatch !== '' ? $ba_bec_heureMatch : null;
$ba_bec_lieuValue = $ba_bec_lieuMatch !== '' ? $ba_bec_lieuMatch : 'Domicile';
$ba_bec_scoreBecValue = $ba_bec_scoreBec !== '' ? (int) $ba_bec_scoreBec : null;
$ba_bec_scoreAdversaireValue = $ba_bec_scoreAdversaire !== '' ? (int) $ba_bec_scoreAdversaire : null;
$ba_bec_numeroEquipeAdverseValue = $ba_bec_numeroEquipeAdverse > 0 ? $ba_bec_numeroEquipeAdverse : null;

$matchStmt = $DB->prepare(
    'UPDATE `MATCH`
        SET codeEquipe = :codeEquipe,
            clubAdversaire = :clubAdversaire,
            numEquipeAdverse = :numEquipeAdverse,
            saison = :saison,
            phase = :phase,
            journee = :journee,
            dateMatch = :dateMatch,
            heureMatch = :heureMatch,
            lieuMatch = :lieuMatch,
            scoreBec = :scoreBec,
            scoreAdversaire = :scoreAdversaire
      WHERE numMatch = :numMatch'
);
$matchStmt->execute([
    ':codeEquipe' => $ba_bec_codeEquipe,
    ':clubAdversaire' => $ba_bec_clubAdversaire,
    ':numEquipeAdverse' => $ba_bec_numeroEquipeAdverseValue,
    ':saison' => $ba_bec_saison,
    ':phase' => $ba_bec_phase,
    ':journee' => $ba_bec_journee,
    ':dateMatch' => $ba_bec_dateMatch,
    ':heureMatch' => $ba_bec_heureValue,
    ':lieuMatch' => $ba_bec_lieuValue,
    ':scoreBec' => $ba_bec_scoreBecValue,
    ':scoreAdversaire' => $ba_bec_scoreAdversaireValue,
    ':numMatch' => $ba_bec_numMatch,
]);

header('Location: ../../views/backend/matches/list.php');
