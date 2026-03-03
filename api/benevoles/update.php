<?php
/*
 * Endpoint API: api/benevoles/update.php
 * Rôle: met à jour un(e) benevole existant(e).
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

$ba_bec_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ba_bec_numPersonnel = ctrlSaisies($_POST['numPersonnel'] ?? '');
    $ba_bec_prenomPersonnel = ctrlSaisies($_POST['prenomPersonnel'] ?? '');
    $ba_bec_nomPersonnel = ctrlSaisies($_POST['nomPersonnel'] ?? '');
    $ba_bec_estStaffEquipe = !empty($_POST['estStaffEquipe']) ? 1 : 0;
    $ba_bec_numEquipeStaff = ctrlSaisies($_POST['numEquipeStaff'] ?? '');
    $ba_bec_roleStaffEquipe = ctrlSaisies($_POST['roleStaffEquipe'] ?? '');
    $ba_bec_estDirection = !empty($_POST['estDirection']) ? 1 : 0;
    $ba_bec_posteDirection = ctrlSaisies($_POST['posteDirection'] ?? '');
    $ba_bec_estCommissionTechnique = !empty($_POST['estCommissionTechnique']) ? 1 : 0;
    $ba_bec_posteCommissionTechnique = ctrlSaisies($_POST['posteCommissionTechnique'] ?? '');
    $ba_bec_estCommissionAnimation = !empty($_POST['estCommissionAnimation']) ? 1 : 0;
    $ba_bec_posteCommissionAnimation = ctrlSaisies($_POST['posteCommissionAnimation'] ?? '');
    $ba_bec_estCommissionCommunication = !empty($_POST['estCommissionCommunication']) ? 1 : 0;
    $ba_bec_posteCommissionCommunication = ctrlSaisies($_POST['posteCommissionCommunication'] ?? '');

    $ba_bec_photoPath = null;

    $ba_bec_normalize = static function (string $value): string {
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($normalized === false) {
            $normalized = $value;
        }
        $normalized = strtolower($normalized);
        $normalized = preg_replace('/[^a-z0-9]/', '', $normalized);
        return $normalized ?? '';
    };

    if (isset($_FILES['photoPersonnel']) && $_FILES['photoPersonnel']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['photoPersonnel']['error'] !== UPLOAD_ERR_OK) {
            $ba_bec_errors[] = "Erreur lors de l'upload de la photo.";
        } else {
            $ba_bec_tmpName = $_FILES['photoPersonnel']['tmp_name'];
            $ba_bec_name = $_FILES['photoPersonnel']['name'];
            $ba_bec_size = $_FILES['photoPersonnel']['size'];
            $ba_bec_allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $ba_bec_allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $ba_bec_maxSize = 5 * 1024 * 1024;

            if ($ba_bec_size > $ba_bec_maxSize) {
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
                    } elseif (getimagesize($ba_bec_tmpName) === false) {
                        $ba_bec_errors[] = "Le fichier n'est pas une image valide.";
                    }
                }
            }

            if (empty($ba_bec_errors)) {
                $ba_bec_nomNormalise = $ba_bec_normalize($ba_bec_nomPersonnel);
                $ba_bec_prenomNormalise = $ba_bec_normalize($ba_bec_prenomPersonnel);
                $ba_bec_prefix = substr($ba_bec_nomNormalise, 0, 2);
                if ($ba_bec_prefix === '') {
                    $ba_bec_prefix = 'xx';
                }
                if ($ba_bec_prenomNormalise === '') {
                    $ba_bec_prenomNormalise = 'prenom';
                }
                $ba_bec_fileName = $ba_bec_prefix . '.' . $ba_bec_prenomNormalise . '.' . $ba_bec_extension;
                $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/photos-benevoles/';
                if (!is_dir($ba_bec_uploadDir)) {
                    mkdir($ba_bec_uploadDir, 0775, true);
                }
                $ba_bec_destination = $ba_bec_uploadDir . $ba_bec_fileName;
                if (!move_uploaded_file($ba_bec_tmpName, $ba_bec_destination)) {
                    $ba_bec_errors[] = "Erreur lors de l'upload de la photo.";
                } else {
                    $ba_bec_photoPath = '/src/uploads/photos-benevoles/' . $ba_bec_fileName;
                }
            }
        }
    }

    if ($ba_bec_numPersonnel === '' || $ba_bec_prenomPersonnel === '' || $ba_bec_nomPersonnel === '') {
        $ba_bec_errors[] = 'Le prénom et le nom sont obligatoires.';
    }

    if ($ba_bec_estStaffEquipe && $ba_bec_numEquipeStaff === '') {
        $ba_bec_errors[] = 'Veuillez sélectionner une équipe rattachée.';
    }

    if ($ba_bec_estStaffEquipe && $ba_bec_roleStaffEquipe === '') {
        $ba_bec_errors[] = 'Veuillez préciser le rôle du staff équipe.';
    }

    if ($ba_bec_estDirection && $ba_bec_posteDirection === '') {
        $ba_bec_errors[] = 'Veuillez préciser le poste en direction.';
    }

    if ($ba_bec_estCommissionTechnique && $ba_bec_posteCommissionTechnique === '') {
        $ba_bec_errors[] = 'Veuillez préciser le poste en commission technique.';
    }

    if ($ba_bec_estCommissionAnimation && $ba_bec_posteCommissionAnimation === '') {
        $ba_bec_errors[] = 'Veuillez préciser le poste en commission animation.';
    }

    if ($ba_bec_estCommissionCommunication && $ba_bec_posteCommissionCommunication === '') {
        $ba_bec_errors[] = 'Veuillez préciser le poste en commission communication.';
    }

    if (empty($ba_bec_errors)) {
        if ($ba_bec_estStaffEquipe) {
            $ba_bec_estCommissionTechnique = 1;
        }
        if ($ba_bec_photoPath === null) {
            $ba_bec_existing = sql_select('PERSONNEL', 'urlPhotoPersonnel', "numPersonnel = '$ba_bec_numPersonnel'");
            $ba_bec_photoPath = $ba_bec_existing[0]['urlPhotoPersonnel'] ?? null;
        }
        $ba_bec_photoValue = $ba_bec_photoPath !== null ? "'" . $ba_bec_photoPath . "'" : 'NULL';
        $ba_bec_equipeValue = $ba_bec_numEquipeStaff !== '' ? "'" . $ba_bec_numEquipeStaff . "'" : 'NULL';
        if (!$ba_bec_estStaffEquipe) {
            $ba_bec_equipeValue = 'NULL';
        }
        $ba_bec_roleStaffValue = $ba_bec_roleStaffEquipe !== '' ? "'" . $ba_bec_roleStaffEquipe . "'" : 'NULL';
        if (!$ba_bec_estStaffEquipe) {
            $ba_bec_roleStaffValue = 'NULL';
        }
        $ba_bec_posteDirectionValue = $ba_bec_posteDirection !== '' ? "'" . $ba_bec_posteDirection . "'" : 'NULL';
        $ba_bec_posteCommissionTechniqueValue = $ba_bec_posteCommissionTechnique !== '' ? "'" . $ba_bec_posteCommissionTechnique . "'" : 'NULL';
        $ba_bec_posteCommissionAnimationValue = $ba_bec_posteCommissionAnimation !== '' ? "'" . $ba_bec_posteCommissionAnimation . "'" : 'NULL';
        $ba_bec_posteCommissionCommunicationValue = $ba_bec_posteCommissionCommunication !== '' ? "'" . $ba_bec_posteCommissionCommunication . "'" : 'NULL';
        if (!$ba_bec_estDirection) {
            $ba_bec_posteDirectionValue = 'NULL';
        }
        if (!$ba_bec_estCommissionTechnique) {
            $ba_bec_posteCommissionTechniqueValue = 'NULL';
        }
        if (!$ba_bec_estCommissionAnimation) {
            $ba_bec_posteCommissionAnimationValue = 'NULL';
        }
        if (!$ba_bec_estCommissionCommunication) {
            $ba_bec_posteCommissionCommunicationValue = 'NULL';
        }
        $ba_bec_updates = "prenomPersonnel = '$ba_bec_prenomPersonnel', nomPersonnel = '$ba_bec_nomPersonnel', urlPhotoPersonnel = $ba_bec_photoValue, estStaffEquipe = '$ba_bec_estStaffEquipe', numEquipeStaff = $ba_bec_equipeValue, roleStaffEquipe = $ba_bec_roleStaffValue, estDirection = '$ba_bec_estDirection', posteDirection = $ba_bec_posteDirectionValue, estCommissionTechnique = '$ba_bec_estCommissionTechnique', posteCommissionTechnique = $ba_bec_posteCommissionTechniqueValue, estCommissionAnimation = '$ba_bec_estCommissionAnimation', posteCommissionAnimation = $ba_bec_posteCommissionAnimationValue, estCommissionCommunication = '$ba_bec_estCommissionCommunication', posteCommissionCommunication = $ba_bec_posteCommissionCommunicationValue";
        sql_update('PERSONNEL', $ba_bec_updates, "numPersonnel = '$ba_bec_numPersonnel'");
        header('Location: ../../views/backend/benevoles/list.php');
        exit();
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
            <a href="<?php echo ROOT_URL . '/views/backend/benevoles/list.php'; ?>" class="btn btn-secondary">Retour</a>
        </div>
    </div>
</div>
