<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';

$pageStyles = [
    ROOT_URL . '/src/css/boutique-admin-edit.css',
];

include '../../../header.php';

$ba_bec_article = null;
$ba_bec_numArtBoutique = (int) ($_GET['numArtBoutique'] ?? 0);
if ($ba_bec_numArtBoutique > 0) {
    $ba_bec_rows = sql_select('boutique', '*', "numArtBoutique = '$ba_bec_numArtBoutique'");
    $ba_bec_article = $ba_bec_rows[0] ?? null;
}

$ba_bec_parse_json_list = static function ($value): string {
    if ($value === null || $value === '') {
        return '';
    }
    if (is_array($value)) {
        return implode(', ', $value);
    }
    if (!is_string($value)) {
        return '';
    }
    $decoded = json_decode($value, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return implode(', ', $decoded);
    }
    return $value;
};

$ba_bec_parse_first_image = static function ($value): string {
    if ($value === null || $value === '') {
        return '';
    }
    if (is_array($value)) {
        return (string) ($value[0] ?? '');
    }
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return (string) ($decoded[0] ?? '');
        }
        return $value;
    }
    return '';
};


$resolve_boutique_image_url = static function (string $value): string {
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (strpos($value, '/src/') === 0) {
        return ROOT_URL . $value;
    }

    return ROOT_URL . '/src/images/article-boutique/' . rawurlencode($value);
};
?>

<div class="container boutique-admin-edit py-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Modifier un article boutique</h1>

            <?php if (!$ba_bec_article): ?>
                <div class="alert alert-danger">Article introuvable.</div>
                <a href="<?php echo ROOT_URL . '/views/backend/boutique/list.php'; ?>" class="btn btn-secondary">Retour</a>
            <?php else: ?>
                <form action="<?php echo ROOT_URL . '/api/boutique/update.php'; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="numArtBoutique" value="<?php echo (int) $ba_bec_article['numArtBoutique']; ?>">

                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="boutique-admin-edit__image-panel">
                                <?php $ba_bec_currentImage = $ba_bec_parse_first_image($ba_bec_article['urlPhotoArtBoutique'] ?? ''); ?>
                                <?php if (!empty($ba_bec_currentImage)): ?>
                                    <img src="<?php echo htmlspecialchars($resolve_boutique_image_url($ba_bec_currentImage)); ?>" alt="Image de l'article">
                                <?php else: ?>
                                    <div class="boutique-admin-edit__placeholder">Image de l'article (placeholder)</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="boutique-admin-edit__panel">
                                <div class="form-group mt-2">
                                    <label for="libArtBoutique">Nom *</label>
                                    <input id="libArtBoutique" name="libArtBoutique" class="form-control" type="text" required maxlength="255" placeholder="Ex : Maillot domicile" value="<?php echo htmlspecialchars($ba_bec_article['libArtBoutique'] ?? ''); ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="categorieArtBoutique">Catégorie *</label>
                                    <input id="categorieArtBoutique" name="categorieArtBoutique" class="form-control" type="text" required maxlength="100" placeholder="Ex : Textile" value="<?php echo htmlspecialchars($ba_bec_article['categorieArtBoutique'] ?? ''); ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="descArtBoutique">Description</label>
                                    <textarea id="descArtBoutique" name="descArtBoutique" class="form-control" rows="4" placeholder="Ajoutez une description de l'article pour l'aperçu boutique."><?php echo htmlspecialchars($ba_bec_article['descArtBoutique'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="couleursArtBoutique">Couleurs (séparées par des virgules)</label>
                                    <input id="couleursArtBoutique" name="couleursArtBoutique" class="form-control" type="text" placeholder="Ex : Bleu marine, Blanc" value="<?php echo htmlspecialchars($ba_bec_parse_json_list($ba_bec_article['couleursArtBoutique'] ?? '')); ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="taillesArtBoutique">Tailles (séparées par des virgules)</label>
                                    <input id="taillesArtBoutique" name="taillesArtBoutique" class="form-control" type="text" placeholder="Ex : XS, S, M, L, XL" value="<?php echo htmlspecialchars($ba_bec_parse_json_list($ba_bec_article['taillesArtBoutique'] ?? '')); ?>">
                                </div>
                                <div class="row g-2 mt-1">
                                    <div class="col-md-6">
                                        <label for="prixAdulteArtBoutique">Prix adulte (€) *</label>
                                        <input id="prixAdulteArtBoutique" name="prixAdulteArtBoutique" class="form-control" type="number" step="0.01" min="0" required placeholder="Ex : 39.90" value="<?php echo htmlspecialchars((string) ($ba_bec_article['prixAdulteArtBoutique'] ?? '')); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="prixEnfantArtBoutique">Prix enfant (€)</label>
                                        <input id="prixEnfantArtBoutique" name="prixEnfantArtBoutique" class="form-control" type="number" step="0.01" min="0" placeholder="Ex : 29.90" value="<?php echo htmlspecialchars((string) ($ba_bec_article['prixEnfantArtBoutique'] ?? '')); ?>">
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="urlPhotoArtBoutique">Lien de l'image</label>
                                    <input id="urlPhotoArtBoutique" name="urlPhotoArtBoutique" class="form-control" type="text" placeholder="Ex : /src/uploads/photos-boutiques/maillot.jpg" value="<?php echo htmlspecialchars($ba_bec_currentImage); ?>">
                                    <small class="form-text text-muted">Vous pouvez coller un lien existant ou envoyer un nouveau fichier ci-dessous.</small>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="photoArtBoutique">Uploader une nouvelle image</label>
                                    <input id="photoArtBoutique" name="photoArtBoutique" class="form-control" type="file" accept=".jpg,.jpeg,.png,.webp,.gif">
                                    <small class="form-text text-muted">Le fichier sera stocké dans /src/uploads/photos-boutiques/</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3 d-flex gap-2">
                        <a href="<?php echo ROOT_URL . '/views/backend/boutique/list.php'; ?>" class="btn btn-secondary">Retour</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
