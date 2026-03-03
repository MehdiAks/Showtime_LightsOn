<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

$ba_bec_article = null;
$ba_bec_numArtBoutique = (int) ($_GET['numArtBoutique'] ?? 0);
if ($ba_bec_numArtBoutique > 0) {
    $ba_bec_rows = sql_select('boutique', '*', "numArtBoutique = '$ba_bec_numArtBoutique'");
    $ba_bec_article = $ba_bec_rows[0] ?? null;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Supprimer un article boutique</h1>
            <?php if (!$ba_bec_article): ?>
                <div class="alert alert-danger">Article introuvable.</div>
                <a href="<?php echo ROOT_URL . '/views/backend/boutique/list.php'; ?>" class="btn btn-secondary">Retour</a>
            <?php else: ?>
                <form action="<?php echo ROOT_URL . '/api/boutique/delete.php'; ?>" method="post">
                    <div class="form-group mt-2">
                        <label for="numArtBoutique">ID</label>
                        <input id="numArtBoutique" name="numArtBoutique" class="form-control" type="text" readonly value="<?php echo (int) $ba_bec_article['numArtBoutique']; ?>">
                    </div>
                    <div class="form-group mt-2">
                        <label for="libArtBoutique">Nom</label>
                        <input id="libArtBoutique" class="form-control" type="text" readonly value="<?php echo htmlspecialchars($ba_bec_article['libArtBoutique'] ?? ''); ?>">
                    </div>

                    <div class="form-group mt-3 d-flex gap-2">
                        <a href="<?php echo ROOT_URL . '/views/backend/boutique/list.php'; ?>" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
