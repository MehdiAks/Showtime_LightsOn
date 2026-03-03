<?php
/*
 * Endpoint API: api/articles/create.php
 * Rôle: crée un(e) article en base.
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

$ba_bec_nom_image = null;
$ba_bec_imagePayload = null;

$ba_bec_libTitrArt = ctrlSaisies($_POST['libTitrArt'] ?? '');
$ba_bec_libChapoArt = ctrlSaisies($_POST['libChapoArt'] ?? '');
$ba_bec_libAccrochArt = ctrlSaisies($_POST['libAccrochArt'] ?? '');
$ba_bec_parag1Art = ctrlSaisies($_POST['parag1Art'] ?? '');
$ba_bec_libSsTitr1Art = ctrlSaisies($_POST['libSsTitr1Art'] ?? '');
$ba_bec_parag2Art = ctrlSaisies($_POST['parag2Art'] ?? '');
$ba_bec_libSsTitr2Art = ctrlSaisies($_POST['libSsTitr2Art'] ?? '');
$ba_bec_parag3Art = ctrlSaisies($_POST['parag3Art'] ?? '');
$ba_bec_libConclArt = ctrlSaisies($_POST['libConclArt'] ?? '');
$ba_bec_numThem = ctrlSaisies($_POST['numThem'] ?? '');

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

$ba_bec_numMotCle = isset($_POST['motCle']) ? array_values(array_filter((array) $_POST['motCle'], 'strlen')) : [];
if (count($ba_bec_numMotCle) < 3) {
    http_response_code(400);
    echo "Veuillez sélectionner au moins 3 mots-clés.";
    exit;
}
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

    $ba_bec_imagePayload = [
        'tmpName' => $ba_bec_tmpName,
        'extension' => $ba_bec_extension,
    ];

}

if ($ba_bec_numThem === '' || !is_numeric($ba_bec_numThem)) {
    http_response_code(400);
    echo "Veuillez sélectionner une thématique valide.";
    exit;
}

// Insertion dans la table ARTICLE
$ba_bec_insert_result = sql_insert(
    'ARTICLE',
    'libTitrArt, libChapoArt, libAccrochArt, parag1Art, libSsTitr1Art, parag2Art, libSsTitr2Art, parag3Art, libConclArt, urlPhotArt, numThem',
    "'$ba_bec_libTitrArt', '$ba_bec_libChapoArt', '$ba_bec_libAccrochArt', '$ba_bec_parag1Art', '$ba_bec_libSsTitr1Art', '$ba_bec_parag2Art', '$ba_bec_libSsTitr2Art', '$ba_bec_parag3Art', '$ba_bec_libConclArt', NULL, '$ba_bec_numThem'"
);
if (!$ba_bec_insert_result['success']) {
    flash_error();
    header('Location: ../../views/backend/articles/list.php');
    exit;
}
$ba_bec_lastArt = sql_select('ARTICLE', 'numArt', null, null, 'numArt DESC', '1')[0]['numArt'];

if ($ba_bec_imagePayload) {
    $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/article/';
    ensure_upload_dir($ba_bec_uploadDir);
    $ba_bec_nom_image = build_article_image_path((int) $ba_bec_lastArt, $ba_bec_imagePayload['extension']);
    $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;

    if (!move_uploaded_file($ba_bec_imagePayload['tmpName'], $ba_bec_destination)) {
        die("Erreur lors de l'upload de l'image.");
    }

    sql_update('ARTICLE', "urlPhotArt = '$ba_bec_nom_image'", "numArt = '$ba_bec_lastArt'");
}

$ba_bec_has_error = false;
foreach ($ba_bec_numMotCle as $ba_bec_mot){
    $ba_bec_link_result = sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', "$ba_bec_lastArt, $ba_bec_mot");
    if (!$ba_bec_link_result['success']) {
        $ba_bec_has_error = true;
    }
}




// Redirection après l'insertion
if ($ba_bec_has_error) {
    flash_error();
} else {
    flash_success();
}
header('Location: ../../views/backend/articles/list.php');
exit;

?>
