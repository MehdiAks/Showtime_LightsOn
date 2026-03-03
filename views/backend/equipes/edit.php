<?php
/*
 * Vue d'administration (édition) pour le module equipes.
 * - Le formulaire réutilise la structure de création mais avec des valeurs pré-remplies côté serveur.
 * - Les identifiants nécessaires (ID) sont passés via la query string ou des champs cachés.
 * - L'action du formulaire cible la route de mise à jour correspondante.
 * - Les sections HTML isolent les groupes d'attributs pour une édition guidée.
 * - Les actions secondaires permettent de revenir à la liste sans enregistrer.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

if (!isset($_GET['numEquipe'])) {
    header('Location: ' . ROOT_URL . '/views/backend/equipes/list.php');
    exit;
}

sql_connect();

$ba_bec_numEquipe = (int) $_GET['numEquipe'];
$ba_bec_equipe = null;
if ($ba_bec_numEquipe) {
    $stmt = $DB->prepare(
        'SELECT * FROM EQUIPE WHERE numEquipe = :numEquipe'
    );
    $stmt->execute([':numEquipe' => $ba_bec_numEquipe]);
    $ba_bec_equipe = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

if (!$ba_bec_equipe) {
    header('Location: ' . ROOT_URL . '/views/backend/equipes/list.php');
    exit;
}

$ba_bec_photoEquipe = $ba_bec_equipe['photoDLequipe'] ?? '';
$ba_bec_photoStaff = $ba_bec_equipe['photoStaff'] ?? '';

function ba_bec_equipe_photo_url(?string $path): string
{
    if (!$path) {
        return '';
    }

    if (preg_match('/^(https?:\/\/|\/)/', $path)) {
        return $path;
    }

    return ROOT_URL . '/src/uploads/photos-equipes/' . ltrim($path, '/');
}

$ba_bec_photoEquipeUrl = ba_bec_equipe_photo_url($ba_bec_photoEquipe);
$ba_bec_photoStaffUrl = ba_bec_equipe_photo_url($ba_bec_photoStaff);

$ba_bec_clubs = [];
$ba_bec_categories = [];
$ba_bec_sections = [];
$ba_bec_niveaux = [];
$ba_bec_clubs = array_column(
    sql_select('EQUIPE', 'DISTINCT club', "club <> ''", null, 'club ASC'),
    'club'
);
$ba_bec_categories = array_column(
    sql_select('EQUIPE', 'DISTINCT categorie', "categorie <> ''", null, 'categorie ASC'),
    'categorie'
);
$ba_bec_sections = array_column(
    sql_select('EQUIPE', 'DISTINCT section', "section <> ''", null, 'section ASC'),
    'section'
);
$ba_bec_niveaux = array_column(
    sql_select('EQUIPE', 'DISTINCT niveau', "niveau <> ''", null, 'niveau ASC'),
    'niveau'
);

function ba_bec_photo_url(string $codeEquipe, string $suffix): ?string
{
    $codeEquipe = preg_replace('/[^A-Za-z0-9_-]+/', '', $codeEquipe);
    if ($codeEquipe === '') {
        return null;
    }
    $extensions = ['jpg', 'jpeg', 'png', 'avif', 'svg', 'webp', 'gif'];
    foreach ($extensions as $extension) {
        $fileName = $codeEquipe . '-' . $suffix . '.' . $extension;
        $path = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/photos-equipes/' . $fileName;
        if (file_exists($path)) {
            return ROOT_URL . '/src/uploads/photos-equipes/' . $fileName;
        }
    }
    return null;
}

$ba_bec_photoEquipeUrl = $ba_bec_photoEquipeUrl ?: ba_bec_photo_url($ba_bec_equipe['codeEquipe'] ?? '', 'photo-equipe');
$ba_bec_photoStaffUrl = $ba_bec_photoStaffUrl ?: ba_bec_photo_url($ba_bec_equipe['codeEquipe'] ?? '', 'photo-staff');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/equipes/list.php'; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Modifier une équipe</h1>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/equipes/update.php'; ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="numEquipe" value="<?php echo htmlspecialchars($ba_bec_equipe['numEquipe']); ?>" />
                <div class="form-group">
                    <label for="codeEquipe">Code équipe</label>
                    <input id="codeEquipe" name="codeEquipe" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_equipe['codeEquipe']); ?>"
                        placeholder="Code équipe (ex: U18F)" required />
                </div>
                <div class="form-group mt-2">
                    <label for="nomEquipe">Nom de l'équipe</label>
                    <input id="nomEquipe" name="nomEquipe" class="form-control" type="text"
                        value="<?php echo htmlspecialchars($ba_bec_equipe['nomEquipe']); ?>" placeholder="Nom de l'équipe..."
                        required />
                </div>
                <div class="form-group mt-2">
                    <label for="club">Club</label>
                    <input id="club" name="club" class="form-control" type="text" list="clubList"
                        value="<?php echo htmlspecialchars($ba_bec_equipe['club'] ?? ''); ?>"
                        placeholder="Club (ex: Bordeaux étudiant club)" required />
                    <datalist id="clubList">
                        <?php foreach ($ba_bec_clubs as $ba_bec_club): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_club); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="categorie">Catégorie</label>
                    <input id="categorie" name="categorie" class="form-control" type="text" list="categorieList"
                        value="<?php echo htmlspecialchars($ba_bec_equipe['categorie'] ?? ''); ?>"
                        placeholder="Catégorie (ex: Seniors)" />
                    <datalist id="categorieList">
                        <?php foreach ($ba_bec_categories as $ba_bec_categorie): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_categorie); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="section">Section</label>
                    <input id="section" name="section" class="form-control" type="text" list="sectionList"
                        value="<?php echo htmlspecialchars($ba_bec_equipe['section'] ?? ''); ?>"
                        placeholder="Section (ex: Féminine)" />
                    <datalist id="sectionList">
                        <?php foreach ($ba_bec_sections as $ba_bec_section): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_section); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="niveau">Niveau</label>
                    <input id="niveau" name="niveau" class="form-control" type="text" list="niveauList"
                        value="<?php echo htmlspecialchars($ba_bec_equipe['niveau'] ?? ''); ?>"
                        placeholder="Niveau (ex: Régional)" />
                    <datalist id="niveauList">
                        <?php foreach ($ba_bec_niveaux as $ba_bec_niveau): ?>
                            <option value="<?php echo htmlspecialchars($ba_bec_niveau); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="form-group mt-2">
                    <label for="descriptionEquipe">Description</label>
                    <textarea id="descriptionEquipe" name="descriptionEquipe" class="form-control" rows="4"
                        placeholder="Description de l'équipe..."><?php echo htmlspecialchars($ba_bec_equipe['descriptionEquipe'] ?? ''); ?></textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="photoDLequipe">Photo de l'équipe (upload)</label>
                    <input id="photoDLequipe" name="photoDLequipe" class="form-control" type="file"
                        accept=".png, .jpeg, .jpg, .avif, .svg, .webp, .gif" />
                    <?php if ($ba_bec_photoEquipeUrl): ?>
                        <div class="mt-2">
                            <img src="<?php echo htmlspecialchars($ba_bec_photoEquipeUrl); ?>" alt="Photo équipe" style="max-width: 160px;" />
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-2">
                    <label for="photoStaff">Photo staff (upload)</label>
                    <input id="photoStaff" name="photoStaff" class="form-control" type="file"
                        accept=".png, .jpeg, .jpg, .avif, .svg, .webp, .gif" />
                    <?php if ($ba_bec_photoStaffUrl): ?>
                        <div class="mt-2">
                            <img src="<?php echo htmlspecialchars($ba_bec_photoStaffUrl); ?>" alt="Photo staff" style="max-width: 160px;" />
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
