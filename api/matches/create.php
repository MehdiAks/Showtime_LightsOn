<?php
/*
 * Endpoint API: api/matches/create.php
 * Rôle: crée un(e) matche en base.
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
$ba_bec_createRetour = isset($_POST['createRetour']);

if ($ba_bec_codeEquipe === '' || $ba_bec_saison === '' || $ba_bec_phase === '' || $ba_bec_journee === '' || $ba_bec_dateMatch === '' || $ba_bec_clubAdversaire === '') {
    header('Location: ../../views/backend/matches/create.php?error=missing');
    exit;
}

$ba_bec_heureValue = $ba_bec_heureMatch !== '' ? $ba_bec_heureMatch : null;
$ba_bec_lieuValue = $ba_bec_lieuMatch !== '' ? $ba_bec_lieuMatch : 'Domicile';
$ba_bec_scoreBecValue = $ba_bec_scoreBec !== '' ? (int) $ba_bec_scoreBec : null;
$ba_bec_scoreAdversaireValue = $ba_bec_scoreAdversaire !== '' ? (int) $ba_bec_scoreAdversaire : null;
$ba_bec_numeroEquipeAdverseValue = $ba_bec_numeroEquipeAdverse > 0 ? $ba_bec_numeroEquipeAdverse : null;

$matchStmt = $DB->prepare(
    'INSERT INTO `MATCH` (codeEquipe, clubAdversaire, numEquipeAdverse, saison, phase, journee, dateMatch, heureMatch, lieuMatch, scoreBec, scoreAdversaire)
     VALUES (:codeEquipe, :clubAdversaire, :numEquipeAdverse, :saison, :phase, :journee, :dateMatch, :heureMatch, :lieuMatch, :scoreBec, :scoreAdversaire)'
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
]);

if ($ba_bec_createRetour) {
    $ba_bec_lieuRetour = $ba_bec_lieuValue;
    if ($ba_bec_lieuValue === 'Domicile') {
        $ba_bec_lieuRetour = 'Extérieur';
    } elseif ($ba_bec_lieuValue === 'Extérieur') {
        $ba_bec_lieuRetour = 'Domicile';
    }

    $ba_bec_dateRetour = $ba_bec_dateMatch;
    if ($ba_bec_dateMatch !== '') {
        $ba_bec_dateObj = DateTime::createFromFormat('Y-m-d', $ba_bec_dateMatch);
        if ($ba_bec_dateObj instanceof DateTime) {
            $ba_bec_dateObj->modify('+7 days');
            $ba_bec_dateRetour = $ba_bec_dateObj->format('Y-m-d');
        }
    }

    $ba_bec_query = http_build_query([
        'codeEquipe' => $ba_bec_codeEquipe,
        'saison' => $ba_bec_saison,
        'phase' => $ba_bec_phase,
        'journee' => $ba_bec_journee,
        'dateMatch' => $ba_bec_dateRetour,
        'heureMatch' => $ba_bec_heureMatch,
        'lieuMatch' => $ba_bec_lieuRetour,
        'clubAdversaire' => $ba_bec_clubAdversaire,
        'numeroEquipeAdverse' => $ba_bec_numeroEquipeAdverseValue,
        'scoreBec' => $ba_bec_scoreBec,
        'scoreAdversaire' => $ba_bec_scoreAdversaire,
    ]);

    header('Location: ../../views/backend/matches/create.php?' . $ba_bec_query);
    exit;
}

header('Location: ../../views/backend/matches/list.php');
