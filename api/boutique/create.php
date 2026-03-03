<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$ba_bec_parse_to_json = static function (string $value): string {
    $items = array_values(array_filter(array_map('trim', explode(',', $value)), 'strlen'));
    return json_encode($items, JSON_UNESCAPED_UNICODE);
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ba_bec_lib = ctrlSaisies($_POST['libArtBoutique'] ?? '');
    $ba_bec_desc = ctrlSaisies($_POST['descArtBoutique'] ?? '');
    $ba_bec_couleurs = ctrlSaisies($_POST['couleursArtBoutique'] ?? '');
    $ba_bec_tailles = ctrlSaisies($_POST['taillesArtBoutique'] ?? '');
    $ba_bec_prix_adulte = (float) ($_POST['prixAdulteArtBoutique'] ?? 0);
    $ba_bec_prix_enfant_raw = trim((string) ($_POST['prixEnfantArtBoutique'] ?? ''));
    $ba_bec_prix_enfant = $ba_bec_prix_enfant_raw !== '' ? (float) $ba_bec_prix_enfant_raw : null;
    $ba_bec_photo = ctrlSaisies($_POST['urlPhotoArtBoutique'] ?? '');
    $ba_bec_categorie = ctrlSaisies($_POST['categorieArtBoutique'] ?? '');

    if ($ba_bec_lib !== '' && $ba_bec_categorie !== '' && $ba_bec_prix_adulte >= 0) {
        $ba_bec_json_couleurs = $ba_bec_parse_to_json($ba_bec_couleurs);
        $ba_bec_json_tailles = $ba_bec_parse_to_json($ba_bec_tailles);
        $ba_bec_json_photo = json_encode($ba_bec_photo !== '' ? [$ba_bec_photo] : [], JSON_UNESCAPED_UNICODE);
        $ba_bec_desc_value = $ba_bec_desc !== '' ? "'" . addslashes($ba_bec_desc) . "'" : 'NULL';
        $ba_bec_prix_enfant_value = $ba_bec_prix_enfant !== null ? "'" . number_format($ba_bec_prix_enfant, 2, '.', '') . "'" : 'NULL';

        sql_insert(
            'boutique',
            'libArtBoutique, descArtBoutique, couleursArtBoutique, taillesArtBoutique, prixAdulteArtBoutique, prixEnfantArtBoutique, urlPhotoArtBoutique, categorieArtBoutique',
            "'" . addslashes($ba_bec_lib) . "', $ba_bec_desc_value, '" . addslashes($ba_bec_json_couleurs) . "', '" . addslashes($ba_bec_json_tailles) . "', '" . number_format($ba_bec_prix_adulte, 2, '.', '') . "', $ba_bec_prix_enfant_value, '" . addslashes($ba_bec_json_photo) . "', '" . addslashes($ba_bec_categorie) . "'"
        );
    }

    header('Location: ../../views/backend/boutique/list.php');
    exit();
}
