<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

$ba_bec_parse_to_json = static function (string $value): string {
    $items = array_values(array_filter(array_map('trim', explode(',', $value)), 'strlen'));
    return json_encode($items, JSON_UNESCAPED_UNICODE);
};

$ba_bec_store_boutique_photo = static function (array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        return null;
    }

    $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($extension, $allowedExtensions, true)) {
        return null;
    }

    $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/photos-boutiques';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0775, true);
    }

    $baseName = pathinfo((string) ($file['name'] ?? ''), PATHINFO_FILENAME);
    $sanitizedBaseName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $baseName);
    $sanitizedBaseName = trim((string) $sanitizedBaseName, '-');
    if ($sanitizedBaseName === '') {
        $sanitizedBaseName = 'photo-boutique';
    }

    $fileName = $sanitizedBaseName . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $uploadDirectory . '/' . $fileName;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        return null;
    }

    return '/src/uploads/photos-boutiques/' . $fileName;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ba_bec_num = (int) ($_POST['numArtBoutique'] ?? 0);
    $ba_bec_lib = ctrlSaisies($_POST['libArtBoutique'] ?? '');
    $ba_bec_desc = ctrlSaisies($_POST['descArtBoutique'] ?? '');
    $ba_bec_couleurs = ctrlSaisies($_POST['couleursArtBoutique'] ?? '');
    $ba_bec_tailles = ctrlSaisies($_POST['taillesArtBoutique'] ?? '');
    $ba_bec_prix_adulte = (float) ($_POST['prixAdulteArtBoutique'] ?? 0);
    $ba_bec_prix_enfant_raw = trim((string) ($_POST['prixEnfantArtBoutique'] ?? ''));
    $ba_bec_prix_enfant = $ba_bec_prix_enfant_raw !== '' ? (float) $ba_bec_prix_enfant_raw : null;
    $ba_bec_photo = ctrlSaisies($_POST['urlPhotoArtBoutique'] ?? '');
    $ba_bec_categorie = ctrlSaisies($_POST['categorieArtBoutique'] ?? '');
    $ba_bec_uploaded_photo = $ba_bec_store_boutique_photo($_FILES['photoArtBoutique'] ?? []);

    if ($ba_bec_uploaded_photo !== null) {
        $ba_bec_photo = $ba_bec_uploaded_photo;
    }

    if ($ba_bec_num > 0 && $ba_bec_lib !== '' && $ba_bec_categorie !== '' && $ba_bec_prix_adulte >= 0) {
        $ba_bec_json_couleurs = $ba_bec_parse_to_json($ba_bec_couleurs);
        $ba_bec_json_tailles = $ba_bec_parse_to_json($ba_bec_tailles);
        $ba_bec_json_photo = json_encode($ba_bec_photo !== '' ? [$ba_bec_photo] : [], JSON_UNESCAPED_UNICODE);
        $ba_bec_desc_value = $ba_bec_desc !== '' ? "'" . addslashes($ba_bec_desc) . "'" : 'NULL';
        $ba_bec_prix_enfant_value = $ba_bec_prix_enfant !== null ? "'" . number_format($ba_bec_prix_enfant, 2, '.', '') . "'" : 'NULL';

        sql_update(
            'boutique',
            "libArtBoutique = '" . addslashes($ba_bec_lib) . "', descArtBoutique = $ba_bec_desc_value, couleursArtBoutique = '" . addslashes($ba_bec_json_couleurs) . "', taillesArtBoutique = '" . addslashes($ba_bec_json_tailles) . "', prixAdulteArtBoutique = '" . number_format($ba_bec_prix_adulte, 2, '.', '') . "', prixEnfantArtBoutique = $ba_bec_prix_enfant_value, urlPhotoArtBoutique = '" . addslashes($ba_bec_json_photo) . "', categorieArtBoutique = '" . addslashes($ba_bec_categorie) . "'",
            "numArtBoutique = '$ba_bec_num'"
        );
    }

    header('Location: ../../views/backend/boutique/list.php');
    exit();
}
