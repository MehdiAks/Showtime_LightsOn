<?php
/*
 * Vue d'administration (suppression) pour le module likes.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirecmodo.php';
include '../../../header.php';

if (isset($_GET['numMemb']) && isset($_GET['numArt'])) {
    $ba_bec_numMemb = $_GET['numMemb'];
    $ba_bec_numArt = $_GET['numArt'];
    $ba_bec_likeA = sql_select("LIKEART", "likeA", "numMemb = $ba_bec_numMemb AND numArt = $ba_bec_numArt")[0]['likeA'];
}
?>

<!-- Bootstrap form to delete a like -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="titre text-center">Modération Like : Suppression</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to delete a like -->
            <form action="<?php echo ROOT_URL . '/api/likes/delete.php' ?>" method="post">

                <div class="form-group">
                    <label for="numArt">Numéro d'article</label>
                    <input id="numArt" name="numArt" class="form-control" style="display: none" type="text"
                        value="<?php echo $ba_bec_numArt; ?>" readonly="readonly" />
                    <input id="numArt" name="numArt" class="form-control" type="text" value="<?php echo $ba_bec_numArt; ?>"
                        disabled />
                </div>
                <br>

                <div class="form-group">
                    <label for="numMemb">Numéro Membre</label>
                    <input id="numMemb" name="numMemb" class="form-control" style="display: none" type="text"
                        value="<?php echo $ba_bec_numMemb; ?>" readonly="readonly" />
                    <input id="numMemb" name="numMemb" class="form-control" type="text" value="<?php echo $ba_bec_numMemb; ?>"
                        disabled />
                </div>
                <br>

                <div class="form-group">
                    <label for="likeA">Like/Dislike</label>
                    <input id="likeA" name="likeA" class="form-control" style="display: none" type="text"
                        value="<?php echo $ba_bec_likeA; ?>" />
                    <input id="likeA" name="likeA" class="form-control" type="text"
                        value="<?php echo ($ba_bec_likeA == 1 ? 'Like' : 'Dislike'); ?>" disabled />
                </div>
                <br>

                <div class="form-group d-flex gap-2">
                    <button type="submit" class="btn btn-danger">Confirmer la suppression ?</button>
                    <a href="list.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
