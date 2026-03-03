<!--
    /*
     * Vue d'administration (suppression) pour le module statuts.
     * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
     * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
     * - Le bouton principal déclenche la route de suppression côté backend.
     * - Un lien de retour évite la suppression et renvoie vers la liste.
     * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
     */
-->
<!-- Bootstrap form to create a new statut -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression Statut</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new statut -->
            <form action="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=destroy'; ?>" method="post">
                <div class="form-group">
                    <label for="libStat">Nom du statut</label>
                    <input id="numStat" name="numStat" class="form-control" style="display: none" type="text" value="<?php echo($ba_bec_numStat); ?>" readonly="readonly" />
                    <input id="libStat" name="libStat" class="form-control" type="text" value="<?php echo($ba_bec_libStat); ?>" readonly="readonly" disabled />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=list'; ?>" class="btn btn-primary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Confirmer delete ?</button>
                </div>
            </form>
        </div>
    </div>
</div>
