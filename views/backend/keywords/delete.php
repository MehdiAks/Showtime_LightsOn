<?php
/*
 * Vue d'administration (suppression) pour le module keywords.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

if (isset($_GET['numMotCle'])) {
    $ba_bec_numMotCle = $_GET['numMotCle'];
    $ba_bec_libMotCle = sql_select("MOTCLE", "libMotCle", "numMotCle = $ba_bec_numMotCle")[0]['libMotCle'];

    // Vérifie si le statut est utilisé par au moins un membre
    $ba_bec_countnumMotCle = sql_select("MOTCLEARTICLE", "COUNT(*) AS total", "numMotCle = $ba_bec_numMotCle")[0]['total'];
    $ba_bec_ifnumMotCleUsed = $ba_bec_countnumMotCle > 0; // true si au moins un membre a ce statut
}
?>
<!-- Bootstrap form to delete a statut -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression Mot-clé</h1>
            <?php if ($ba_bec_ifnumMotCleUsed) : ?>
                <div class="alert alert-danger">
                    <?php if ($ba_bec_countnumMotCle > 1) : ?>
                        ⚠ Impossible de supprimer ce Mot-clé car il est utilisés par <?php echo $ba_bec_countnumMotCle; ?> articles.
                    <?php else : ?>
                        ⚠ Impossible de supprimer ce Mot-clé car il est utilisé par <?php echo $ba_bec_countnumMotCle; ?> article.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/api/keywords/delete.php' ?>" method="post">
                <div class="form-group">
                    <label for="libMotCle">Nom du Mot-clé</label>
                    <input id="numMotCle" name="numMotCle" class="form-control" style="display: none" type="text" value="<?php echo($ba_bec_numMotCle); ?>" readonly />
                    <input id="libMotCle" name="libMotCle" class="form-control" type="text" value="<?php echo($ba_bec_libMotCle); ?>" readonly disabled />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Retour à la liste</a>
                    <button type="submit" class="btn btn-danger" <?php echo ($ba_bec_ifnumMotCleUsed ? 'disabled' : ''); ?>>
                        Confirmer delete ?
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
