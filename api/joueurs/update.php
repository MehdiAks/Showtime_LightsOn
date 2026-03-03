<?php
/*
 * Endpoint API: api/joueurs/update.php
 * Rôle: met à jour un(e) joueur existant(e).
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

function build_player_photo_name(string $nom, string $prenom, string $extension): string
{
    $nomNettoye = preg_replace('/[^A-Za-zÀ-ÿ]/u', '', $nom);
    $prefix = $nomNettoye !== '' ? $nomNettoye : 'XX';
    if (function_exists('mb_substr')) {
        $prefix = mb_substr($prefix, 0, 2);
    } else {
        $prefix = substr($prefix, 0, 2);
    }
    $prefix = strtoupper($prefix);
    if (strlen($prefix) < 2) {
        $prefix = str_pad($prefix, 2, 'X');
    }
    $prenomNettoye = preg_replace('/[^A-Za-z0-9]+/u', '', $prenom);
    $prenomSlug = strtolower($prenomNettoye !== '' ? $prenomNettoye : 'joueur');

    return $prefix . '.' . $prenomSlug . '.' . $extension;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sql_connect();

    $ba_bec_numJoueur = (int) ($_POST['numJoueur'] ?? 0);
    $ba_bec_surnomJoueur = ctrlSaisies($_POST['surnomJoueur'] ?? '');
    $ba_bec_prenomJoueur = ctrlSaisies($_POST['prenomJoueur'] ?? '');
    $ba_bec_nomJoueur = ctrlSaisies($_POST['nomJoueur'] ?? '');
    $ba_bec_posteJoueur = (int) ($_POST['posteJoueur'] ?? 0);
    $ba_bec_photoActuelle = ctrlSaisies($_POST['photoActuelle'] ?? '');
    $ba_bec_photoActuelleRelative = normalize_upload_path($ba_bec_photoActuelle);
    $ba_bec_numeroMaillot = ctrlSaisies($_POST['numeroMaillot'] ?? '');
    $ba_bec_codeEquipe = ctrlSaisies($_POST['codeEquipe'] ?? '');
    $ba_bec_dateRecrutement = ctrlSaisies($_POST['dateRecrutement'] ?? '');
    $ba_bec_dateNaissance = ctrlSaisies($_POST['dateNaissance'] ?? '');
    $ba_bec_return_teams = $_POST['teams'] ?? [];
    if (!is_array($ba_bec_return_teams)) {
        $ba_bec_return_teams = [$ba_bec_return_teams];
    }
    $ba_bec_return_teams = array_values(array_unique(array_filter(array_map('strval', $ba_bec_return_teams), 'strlen')));
    $ba_bec_errors = [];
    $ba_bec_clubsPrecedentsInput = $_POST['clubsPrecedents'] ?? '';
    $ba_bec_clubsList = [];
    if (is_array($ba_bec_clubsPrecedentsInput)) {
        $ba_bec_clubsList = array_values(array_filter(array_map('trim', $ba_bec_clubsPrecedentsInput), 'strlen'));
    } elseif ($ba_bec_clubsPrecedentsInput !== '') {
        $ba_bec_clubsList = [trim((string) $ba_bec_clubsPrecedentsInput)];
    }

    $ba_bec_nom_image = null;
    if (isset($_FILES['photoJoueur']) && $_FILES['photoJoueur']['error'] === 0) {
        $ba_bec_tmpName = $_FILES['photoJoueur']['tmp_name'];
        $ba_bec_name = $_FILES['photoJoueur']['name'];
        $ba_bec_size = $_FILES['photoJoueur']['size'];
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

        if ($ba_bec_size > 10000000) {
            $ba_bec_errors[] = "Le fichier est trop volumineux.";
        } else {
            $ba_bec_extension = strtolower(pathinfo($ba_bec_name, PATHINFO_EXTENSION));
            if (!in_array($ba_bec_extension, $ba_bec_allowedExtensions, true)) {
                $ba_bec_errors[] = "Format d'image non autorisé.";
            } else {
                $ba_bec_mimeType = null;
                if (function_exists('finfo_open')) {
                    $ba_bec_finfo = finfo_open(FILEINFO_MIME_TYPE);
                    if ($ba_bec_finfo) {
                        $ba_bec_mimeType = finfo_file($ba_bec_finfo, $ba_bec_tmpName);
                        finfo_close($ba_bec_finfo);
                    }
                }

                if ($ba_bec_mimeType && !in_array($ba_bec_mimeType, $ba_bec_allowedMimeTypes, true)) {
                    $ba_bec_errors[] = "Format d'image non autorisé.";
                } elseif (!in_array($ba_bec_extension, ['svg', 'avif'], true)) {
                    $ba_bec_dimensions = getimagesize($ba_bec_tmpName);
                    if ($ba_bec_dimensions === false) {
                        $ba_bec_errors[] = "Le fichier n'est pas une image valide.";
                    }
                }
            }

            if (empty($ba_bec_errors)) {
                $ba_bec_nom_image = build_player_photo_name($ba_bec_nomJoueur, $ba_bec_prenomJoueur, $ba_bec_extension);
                $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/photos-joueurs/';
                ensure_upload_dir($ba_bec_uploadDir);
                $ba_bec_destination = $ba_bec_uploadDir . $ba_bec_nom_image;
                if (!move_uploaded_file($ba_bec_tmpName, $ba_bec_destination)) {
                    $ba_bec_errors[] = "Erreur lors de l'upload de l'image.";
                } else {
                    $ba_bec_nom_image = 'photos-joueurs/' . $ba_bec_nom_image;
                    $ba_bec_photoActuelleRelative = normalize_upload_path($ba_bec_photoActuelle);
                    if ($ba_bec_photoActuelleRelative) {
                        $ba_bec_oldPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_photoActuelleRelative;
                        if (file_exists($ba_bec_oldPath)) {
                            unlink($ba_bec_oldPath);
                        }
                    }
                }
            }
        }
    }

    if ($ba_bec_numJoueur <= 0 || $ba_bec_surnomJoueur === '' || $ba_bec_prenomJoueur === '' || $ba_bec_nomJoueur === '') {
        $ba_bec_errors[] = 'Le surnom, le prénom et le nom sont obligatoires.';
    }
    if ($ba_bec_codeEquipe === '') {
        $ba_bec_errors[] = 'Veuillez sélectionner une équipe valide.';
    }
    if ($ba_bec_posteJoueur <= 0) {
        $ba_bec_errors[] = 'Veuillez sélectionner un poste valide.';
    }

    if (empty($ba_bec_errors)) {
        $ba_bec_photoActuelleRelative = normalize_upload_path($ba_bec_photoActuelle);
        $ba_bec_photoFinale = $ba_bec_nom_image !== null ? $ba_bec_nom_image : $ba_bec_photoActuelleRelative;
        if ($ba_bec_nom_image === null && $ba_bec_photoActuelleRelative) {
            $ba_bec_extension = strtolower(pathinfo($ba_bec_photoActuelleRelative, PATHINFO_EXTENSION));
            if ($ba_bec_extension !== '') {
                $ba_bec_expectedName = build_player_photo_name($ba_bec_nomJoueur, $ba_bec_prenomJoueur, $ba_bec_extension);
                $ba_bec_expectedRelative = 'photos-joueurs/' . $ba_bec_expectedName;
                if ($ba_bec_photoActuelleRelative !== $ba_bec_expectedRelative) {
                    $ba_bec_legacyPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_photoActuelleRelative;
                    if (file_exists($ba_bec_legacyPath)) {
                        $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/photos-joueurs/';
                        ensure_upload_dir($ba_bec_uploadDir);
                        $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_expectedRelative;
                        if (rename($ba_bec_legacyPath, $ba_bec_destination)) {
                            $ba_bec_photoFinale = $ba_bec_expectedRelative;
                        }
                    }
                }
            }
        }
        $ba_bec_photoValue = $ba_bec_photoFinale !== '' ? $ba_bec_photoFinale : null;
        $ba_bec_dateValue = $ba_bec_dateNaissance !== '' ? $ba_bec_dateNaissance : null;
        $ba_bec_dateRecrutementValue = $ba_bec_dateRecrutement !== '' ? $ba_bec_dateRecrutement : null;
        $ba_bec_clubsValue = !empty($ba_bec_clubsList) ? implode(', ', $ba_bec_clubsList) : null;

        $updateJoueur = $DB->prepare(
            'UPDATE JOUEUR
                SET surnomJoueur = :surnom,
                    prenomJoueur = :prenom,
                    nomJoueur = :nom,
                    urlPhotoJoueur = :photo,
                    dateNaissance = :dateNaissance,
                    codeEquipe = :codeEquipe,
                    posteJoueur = :posteJoueur,
                    numeroMaillot = :numeroMaillot,
                    dateRecrutement = :dateRecrutement,
                    clubsPrecedents = :clubsPrecedents
             WHERE numJoueur = :numJoueur'
        );
        $updateJoueur->execute([
            ':surnom' => $ba_bec_surnomJoueur,
            ':prenom' => $ba_bec_prenomJoueur,
            ':nom' => $ba_bec_nomJoueur,
            ':photo' => $ba_bec_photoValue,
            ':dateNaissance' => $ba_bec_dateValue,
            ':codeEquipe' => $ba_bec_codeEquipe,
            ':posteJoueur' => $ba_bec_posteJoueur,
            ':numeroMaillot' => $ba_bec_numeroMaillot !== '' ? (int) $ba_bec_numeroMaillot : null,
            ':dateRecrutement' => $ba_bec_dateRecrutementValue,
            ':clubsPrecedents' => $ba_bec_clubsValue,
            ':numJoueur' => $ba_bec_numJoueur,
        ]);

        $ba_bec_redirect_url = '../../views/backend/joueurs/list.php';
        if (!empty($ba_bec_return_teams)) {
            $ba_bec_redirect_url .= '?' . http_build_query(['teams' => $ba_bec_return_teams]);
        }
        header('Location: ' . $ba_bec_redirect_url);
        exit();
    }
}
?>

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
            <a href="<?php echo ROOT_URL . '/views/backend/joueurs/list.php'; ?>" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
