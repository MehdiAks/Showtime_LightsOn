<?php
/*
 * Endpoint API: api/articles/update.php
 * Rôle: met à jour un(e) article existant(e).
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

// Activer l'affichage des erreurs pour le développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

function ensure_upload_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

function build_article_image_path(int $numArt, string $extension): string
{
    return 'article/article-' . $numArt . '.' . $extension;
}

function normalize_upload_path(?string $path): ?string
{
    if (!$path) {
        return null;
    }

    if (strpos($path, '/src/uploads/') !== false) {
        $relative = substr($path, strpos($path, '/src/uploads/') + strlen('/src/uploads/'));
        return ltrim($relative, '/');
    }

    return ltrim($path, '/');
}

// Récupération des données du formulaire
$ba_bec_dtMajArt = date("Y-m-d H:i:s");
$ba_bec_libTitrArt = ctrlSaisies($_POST['libTitrArt']);
$ba_bec_libChapoArt = ctrlSaisies($_POST['libChapoArt']);
$ba_bec_libAccrochArt = ctrlSaisies($_POST['libAccrochArt']);
$ba_bec_parag1Art = ctrlSaisies($_POST['parag1Art']);
$ba_bec_libSsTitr1Art = ctrlSaisies($_POST['libSsTitr1Art']);
$ba_bec_parag2Art = ctrlSaisies($_POST['parag2Art']);
$ba_bec_libSsTitr2Art = ctrlSaisies($_POST['libSsTitr2Art']);
$ba_bec_parag3Art = ctrlSaisies($_POST['parag3Art']);
$ba_bec_libConclArt = ctrlSaisies($_POST['libConclArt']);
$ba_bec_numThem = ctrlSaisies($_POST['numThem']);
$ba_bec_numArt = ctrlSaisies($_POST['numArt']);
$ba_bec_numMotCle = isset($_POST['motCle']) ? array_values(array_filter((array) $_POST['motCle'], 'strlen')) : [];
if (count($ba_bec_numMotCle) < 3) {
    http_response_code(400);
    echo "Veuillez sélectionner au moins 3 mots-clés.";
    exit;
}

$ba_bec_bbcodeFields = [
    'libTitrArt' => $ba_bec_libTitrArt,
    'libChapoArt' => $ba_bec_libChapoArt,
    'libAccrochArt' => $ba_bec_libAccrochArt,
    'parag1Art' => $ba_bec_parag1Art,
    'libSsTitr1Art' => $ba_bec_libSsTitr1Art,
    'parag2Art' => $ba_bec_parag2Art,
    'libSsTitr2Art' => $ba_bec_libSsTitr2Art,
    'parag3Art' => $ba_bec_parag3Art,
    'libConclArt' => $ba_bec_libConclArt,
];

foreach ($ba_bec_bbcodeFields as $ba_bec_fieldName => $ba_bec_fieldValue) {
    if (!isValidBbcodeContent($ba_bec_fieldValue)) {
        http_response_code(400);
        echo "Le contenu du champ {$ba_bec_fieldName} contient du BBCode non autorisé.";
        exit;
    }
}

if (function_exists('mb_substr')) {
    $ba_bec_libAccrochArt = mb_substr($ba_bec_libAccrochArt, 0, 100);
} else {
    $ba_bec_libAccrochArt = substr($ba_bec_libAccrochArt, 0, 100);
}

// Récupérer l'ancienne image de l'article
$ba_bec_article = sql_select("ARTICLE", "urlPhotArt", "numArt = '$ba_bec_numArt'")[0];
$ba_bec_ancienneImage = normalize_upload_path($ba_bec_article['urlPhotArt'] ?? null);

// Gestion de l'image
if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] === 0) {
    $ba_bec_tmpName = $_FILES['urlPhotArt']['tmp_name'];
    $ba_bec_name = $_FILES['urlPhotArt']['name'];
    $ba_bec_size = $_FILES['urlPhotArt']['size'];
    $ba_bec_allowedExtensions = ['jpg', 'jpeg', 'png', 'avif', 'svg'];
    $ba_bec_allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/avif',
        'image/svg+xml',
        'image/svg',
        'text/xml',
        'application/xml',
    ];

    // Vérification de la taille de l'image
    if ($ba_bec_size > 10000000) {
        die("Le fichier est trop volumineux.");
    }

    $ba_bec_extension = strtolower(pathinfo($ba_bec_name, PATHINFO_EXTENSION));
    if (!in_array($ba_bec_extension, $ba_bec_allowedExtensions, true)) {
        die("Format d'image non autorisé.");
    }

    $ba_bec_mimeType = null;
    if (function_exists('finfo_open')) {
        $ba_bec_finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($ba_bec_finfo) {
            $ba_bec_mimeType = finfo_file($ba_bec_finfo, $ba_bec_tmpName);
            finfo_close($ba_bec_finfo);
        }
    }

    if ($ba_bec_mimeType && !in_array($ba_bec_mimeType, $ba_bec_allowedMimeTypes, true)) {
        die("Format d'image non autorisé.");
    }

    if (!in_array($ba_bec_extension, ['svg', 'avif'], true)) {
        $ba_bec_dimensions = getimagesize($ba_bec_tmpName);
        if ($ba_bec_dimensions === false) {
            die("Le fichier n'est pas une image valide.");
        }
        [$ba_bec_width, $ba_bec_height] = $ba_bec_dimensions;
        if ($ba_bec_width > 5000 || $ba_bec_height > 5000) {
            die("L'image est trop grande.");
        }
    }

    $ba_bec_nom_image = build_article_image_path((int) $ba_bec_numArt, $ba_bec_extension);
    $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/article/';
    ensure_upload_dir($ba_bec_uploadDir);
    $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;

    if (!move_uploaded_file($ba_bec_tmpName, $ba_bec_destination)) {
        die("Erreur lors de l'upload de l'image.");
    }

    // Supprimer l'ancienne image du serveur si elle existe et n'est pas celle par défaut
    if ($ba_bec_ancienneImage) {
        $ba_bec_oldPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_ancienneImage;
        if (file_exists($ba_bec_oldPath)) {
            unlink($ba_bec_oldPath);
        }
    }
    $ba_bec_nom_image = $ba_bec_relativePath;

} else {
    // Si aucune nouvelle image n'est téléchargée, conserver l'image existante
    $ba_bec_nom_image = $ba_bec_ancienneImage;
    if ($ba_bec_nom_image && strpos($ba_bec_nom_image, 'article/') !== 0) {
        $ba_bec_legacyPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;
        if (file_exists($ba_bec_legacyPath)) {
            $ba_bec_extension = strtolower(pathinfo($ba_bec_nom_image, PATHINFO_EXTENSION));
            $ba_bec_nom_image = build_article_image_path((int) $ba_bec_numArt, $ba_bec_extension);
            $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/article/';
            ensure_upload_dir($ba_bec_uploadDir);
            $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;
            if (!rename($ba_bec_legacyPath, $ba_bec_destination)) {
                $ba_bec_nom_image = $ba_bec_ancienneImage;
            }
        }
    }
}

// Variables pour la mise à jour de l'article
$ba_bec_set_art = "dtMajArt = '$ba_bec_dtMajArt',
libTitrArt = '$ba_bec_libTitrArt',
libChapoArt = '$ba_bec_libChapoArt', 
libAccrochArt = '$ba_bec_libAccrochArt',
parag1Art = '$ba_bec_parag1Art', 
libSsTitr1Art = '$ba_bec_libSsTitr1Art',
parag2Art = '$ba_bec_parag2Art',
libSsTitr2Art = '$ba_bec_libSsTitr2Art',
parag3Art = '$ba_bec_parag3Art',
libConclArt = '$ba_bec_libConclArt', 
urlPhotArt = '$ba_bec_nom_image', 
numThem = '$ba_bec_numThem'";

$ba_bec_where_num = "numArt = '$ba_bec_numArt'";
$ba_bec_table_art = "ARTICLE";

// Mise à jour de l'article
$ba_bec_update_result = sql_update($ba_bec_table_art, $ba_bec_set_art, $ba_bec_where_num);
if (!$ba_bec_update_result['success']) {
    flash_error();
    header('Location: ../../views/backend/articles/list.php');
    exit;
}

// Mise à jour des mots-clés liés à l'article (ajouts/suppressions)
$ba_bec_existingKeywords = sql_select('MOTCLEARTICLE', 'numMotCle', $ba_bec_where_num);
$ba_bec_existingIds = array_map('intval', array_column($ba_bec_existingKeywords, 'numMotCle'));
$ba_bec_newIds = array_map('intval', $ba_bec_numMotCle);
$ba_bec_newIds = array_values(array_unique($ba_bec_newIds));

$ba_bec_toAdd = array_diff($ba_bec_newIds, $ba_bec_existingIds);
$ba_bec_toRemove = array_diff($ba_bec_existingIds, $ba_bec_newIds);

foreach ($ba_bec_toAdd as $ba_bec_mot) {
    sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', "$ba_bec_numArt, $ba_bec_mot");
}

foreach ($ba_bec_toRemove as $ba_bec_mot) {
    $ba_bec_where_mot = "numArt = '$ba_bec_numArt' AND numMotCle = '$ba_bec_mot'";
    sql_delete('MOTCLEARTICLE', $ba_bec_where_mot);
}

// Redirection après la mise à jour
if ($ba_bec_has_error) {
    flash_error();
} else {
    flash_success();
}
header('Location: ../../views/backend/articles/list.php');
exit;
?>
