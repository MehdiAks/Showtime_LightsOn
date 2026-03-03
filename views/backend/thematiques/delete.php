<?php
/*
 * Vue d'administration (suppression) pour le module thematiques.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

if (isset($_GET['numThem'])) {
    $ba_bec_numThem = $_GET['numThem'];
    $ba_bec_libThem = sql_select("THEMATIQUE", "libThem", "numThem = $ba_bec_numThem")[0]['libThem'];

    // Vérifie si le statut est utilisé par au moins un membre
    $ba_bec_countnumThem = sql_select("ARTICLE", "COUNT(*) AS total", "numThem = $ba_bec_numThem")[0]['total'];
    $ba_bec_numThemUsed = $ba_bec_countnumThem > 0; // true si au moins un membre a ce statut
}
?>

<!-- Bootstrap form to delete a statut -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression Thematique</h1>
            <?php if ($ba_bec_numThemUsed) : ?>
                <div class="alert alert-danger">
                    <?php if ($ba_bec_countnumThem > 01) : ?>
                        ⚠ Impossible de supprimer cette thematique car elle est utilisées par <?php echo $ba_bec_countnumThem; ?> articles.
                    <?php else : ?>
                        ⚠ Impossible de supprimer cette thematique car elle est utilisée par <?php echo $ba_bec_countnumThem; ?> article.
                    <?php endif; ?>
                    
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/thematiques/delete.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom de Thematique</label>
                    <input id="numThem" name="numThem" class="form-control" style="display: none" type="text" value="<?php echo($ba_bec_numThem); ?>" readonly />
                    <input id="libThem" name="libThem" class="form-control" type="text" value="<?php echo($ba_bec_libThem); ?>" readonly disabled />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Retour à la liste</a>
                    <button type="submit" class="btn btn-danger" <?php echo ($ba_bec_numThemUsed ? 'disabled' : ''); ?>>
                        Confirmer delete ?
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
