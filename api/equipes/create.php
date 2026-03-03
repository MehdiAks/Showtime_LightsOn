<?php
/*
 * Endpoint API: api/equipes/create.php
 * Rôle: crée un(e) equipe en base.
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

function ensure_upload_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

function team_upload_dir(): string
{
    $baseDir = realpath(__DIR__ . '/../../');
    if ($baseDir === false) {
        $baseDir = dirname(__DIR__, 2);
    }
    return rtrim($baseDir, '/') . '/src/uploads/photos-equipes/';
}

function sanitize_team_slug(string $teamName, string $fallbackCode = ''): string
{
    $base = trim($teamName) !== '' ? $teamName : $fallbackCode;
    $slug = strtolower((string) iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $base));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim((string) $slug, '-');

    return $slug !== '' ? $slug : 'equipe';
}

function upload_error_message(int $errorCode): string
{
    return match ($errorCode) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Le fichier est trop volumineux.',
        UPLOAD_ERR_PARTIAL => 'Le fichier a été envoyé partiellement.',
        UPLOAD_ERR_NO_FILE => 'Aucun fichier n’a été envoyé.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant.',
        UPLOAD_ERR_CANT_WRITE => 'Impossible d’écrire le fichier sur le disque.',
        UPLOAD_ERR_EXTENSION => 'Envoi stoppé par une extension PHP.',
        default => 'Erreur lors de l’envoi du fichier.',
    };
}

function upload_team_photo(string $fileKey, string $teamSlug, string $suffix, array &$errors): ?string
{
    if (!isset($_FILES[$fileKey])) {
        return null;
    }

    if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = upload_error_message($_FILES[$fileKey]['error']);
        }
        return null;
    }

    $tmpName = $_FILES[$fileKey]['tmp_name'];
    $name = $_FILES[$fileKey]['name'];
    $size = $_FILES[$fileKey]['size'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'avif', 'svg', 'webp', 'gif'];
    $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/avif',
        'image/webp',
        'image/gif',
        'image/svg+xml',
        'image/svg',
        'text/xml',
        'application/xml',
    ];

    if ($size > 10000000) {
        $errors[] = "Le fichier est trop volumineux.";
        return null;
    }

    $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        $errors[] = "Format d'image non autorisé.";
        return null;
    }

    $mimeType = null;
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mimeType = finfo_file($finfo, $tmpName);
            finfo_close($finfo);
        }
    }

    if ($mimeType && !in_array($mimeType, $allowedMimeTypes, true)) {
        $errors[] = "Format d'image non autorisé.";
        return null;
    }

    if (!in_array($extension, ['svg', 'avif'], true)) {
        $dimensions = getimagesize($tmpName);
        if ($dimensions === false) {
            $errors[] = "Le fichier n'est pas une image valide.";
            return null;
        }
    }

    $safeTeamSlug = sanitize_team_slug($teamSlug);
    $fileName = $safeTeamSlug . '-' . $suffix . '.' . $extension;
    $uploadDir = team_upload_dir();
    ensure_upload_dir($uploadDir);
    $destination = $uploadDir . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        $errors[] = "Erreur lors de l'upload de l'image.";
        return null;
    }

    return 'photos-equipes/' . $fileName;
}

function process_equipe_upload(string $fileKey, string $teamSlug, string $suffix, array &$errors): ?string
{
    return upload_team_photo($fileKey, $teamSlug, $suffix, $errors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sql_connect();

    $ba_bec_errors = [];
    if (empty($_POST) && empty($_FILES)) {
        $ba_bec_errors[] = 'Le formulaire est vide. Vérifiez la taille des fichiers envoyés et réessayez.';
    }

    $ba_bec_codeEquipe = ctrlSaisies($_POST['codeEquipe'] ?? '');
    $ba_bec_nomEquipe = ctrlSaisies($_POST['nomEquipe'] ?? '');
    $ba_bec_club = ctrlSaisies($_POST['club'] ?? '');
    $ba_bec_descriptionEquipe = ctrlSaisies($_POST['descriptionEquipe'] ?? '');
    $ba_bec_categorieEquipe = ctrlSaisies($_POST['categorie'] ?? '');
    $ba_bec_sectionEquipe = ctrlSaisies($_POST['section'] ?? '');
    $ba_bec_niveauEquipe = ctrlSaisies($_POST['niveau'] ?? '');

    if (empty($ba_bec_errors) && ($ba_bec_nomEquipe === '' || $ba_bec_codeEquipe === '')) {
        $ba_bec_errors[] = 'Le code et le nom de l\'équipe sont obligatoires.';
    }
    if (empty($ba_bec_errors) && $ba_bec_club === '') {
        $ba_bec_errors[] = 'Le club est obligatoire.';
    }

    $ba_bec_photoEquipe = null;
    $ba_bec_photoStaff = null;
    if (empty($ba_bec_errors)) {
        $ba_bec_teamSlug = sanitize_team_slug($ba_bec_nomEquipe, $ba_bec_codeEquipe);
        $ba_bec_photoEquipe = process_equipe_upload('photoDLequipe', $ba_bec_teamSlug, 'photo-equipe', $ba_bec_errors);
        $ba_bec_photoStaff = process_equipe_upload('photoStaff', $ba_bec_teamSlug, 'photo-staff', $ba_bec_errors);
    }

    if (empty($ba_bec_errors)) {
        try {
            $insertEquipe = $DB->prepare(
                'INSERT INTO EQUIPE (codeEquipe, nomEquipe, club, categorie, section, niveau, descriptionEquipe, photoDLequipe, photoStaff)
                 VALUES (:codeEquipe, :nomEquipe, :club, :categorie, :section, :niveau, :descriptionEquipe, :photoEquipe, :photoStaff)'
            );
            $insertEquipe->execute([
                ':codeEquipe' => $ba_bec_codeEquipe,
                ':nomEquipe' => $ba_bec_nomEquipe,
                ':club' => $ba_bec_club,
                ':categorie' => $ba_bec_categorieEquipe !== '' ? $ba_bec_categorieEquipe : 'Non renseigné',
                ':section' => $ba_bec_sectionEquipe !== '' ? $ba_bec_sectionEquipe : 'Non renseigné',
                ':niveau' => $ba_bec_niveauEquipe !== '' ? $ba_bec_niveauEquipe : 'Non renseigné',
                ':descriptionEquipe' => $ba_bec_descriptionEquipe !== '' ? $ba_bec_descriptionEquipe : null,
                ':photoEquipe' => $ba_bec_photoEquipe,
                ':photoStaff' => $ba_bec_photoStaff,
            ]);
            header('Location: ../../views/backend/equipes/list.php');
            exit();
        } catch (PDOException $ba_bec_exception) {
            $ba_bec_message = strtolower($ba_bec_exception->getMessage());
            if (str_contains($ba_bec_message, 'duplicate') || str_contains($ba_bec_message, 'unique')) {
                $ba_bec_errors[] = 'Ce code équipe existe déjà. Merci d’en choisir un autre.';
            } else {
                $ba_bec_errors[] = 'Une erreur est survenue lors de la création. Merci de réessayer.';
            }
        } catch (Throwable $ba_bec_exception) {
            $ba_bec_errors[] = 'Une erreur inattendue est survenue pendant le traitement des images. Merci de réessayer.';
        }
    }
}
?>

<?php include '../../header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($ba_bec_errors ?? [])): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($ba_bec_errors as $ba_bec_error): ?>
                            <li><?= $ba_bec_error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <a href="<?php echo ROOT_URL . '/views/backend/equipes/create.php'; ?>" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
