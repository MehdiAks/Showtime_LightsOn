<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Ajouter un article boutique</h1>
            <form action="<?php echo ROOT_URL . '/api/boutique/create.php'; ?>" method="post">
                <div class="form-group mt-2">
                    <label for="libArtBoutique">Nom *</label>
                    <input id="libArtBoutique" name="libArtBoutique" class="form-control" type="text" required maxlength="255">
                </div>
                <div class="form-group mt-2">
                    <label for="categorieArtBoutique">Catégorie *</label>
                    <input id="categorieArtBoutique" name="categorieArtBoutique" class="form-control" type="text" required maxlength="100" placeholder="Vêtement, Accessoire...">
                </div>
                <div class="form-group mt-2">
                    <label for="descArtBoutique">Description</label>
                    <textarea id="descArtBoutique" name="descArtBoutique" class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="couleursArtBoutique">Couleurs (séparées par des virgules)</label>
                    <input id="couleursArtBoutique" name="couleursArtBoutique" class="form-control" type="text" placeholder="Rouge, Blanc">
                </div>
                <div class="form-group mt-2">
                    <label for="taillesArtBoutique">Tailles (séparées par des virgules)</label>
                    <input id="taillesArtBoutique" name="taillesArtBoutique" class="form-control" type="text" placeholder="XS, S, M, L">
                </div>
                <div class="form-group mt-2">
                    <label for="prixAdulteArtBoutique">Prix adulte (€) *</label>
                    <input id="prixAdulteArtBoutique" name="prixAdulteArtBoutique" class="form-control" type="number" step="0.01" min="0" required>
                </div>
                <div class="form-group mt-2">
                    <label for="prixEnfantArtBoutique">Prix enfant (€)</label>
                    <input id="prixEnfantArtBoutique" name="prixEnfantArtBoutique" class="form-control" type="number" step="0.01" min="0">
                </div>
                <div class="form-group mt-2">
                    <label for="urlPhotoArtBoutique">Nom du fichier image</label>
                    <input id="urlPhotoArtBoutique" name="urlPhotoArtBoutique" class="form-control" type="text" placeholder="tshirt-bec.jpg">
                    <small class="form-text text-muted">Image cherchée dans /src/images/article-boutique/</small>
                </div>

                <div class="form-group mt-3 d-flex gap-2">
                    <a href="<?php echo ROOT_URL . '/views/backend/boutique/list.php'; ?>" class="btn btn-secondary">Retour</a>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
