<!--
    /*
     * Vue d'administration (édition) pour le module statuts.
     * - Le formulaire réutilise la structure de création mais avec des valeurs pré-remplies côté serveur.
     * - Les identifiants nécessaires (ID) sont passés via la query string ou des champs cachés.
     * - L'action du formulaire cible la route de mise à jour correspondante.
     * - Les sections HTML isolent les groupes d'attributs pour une édition guidée.
     * - Les actions secondaires permettent de revenir à la liste sans enregistrer.
     */
-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1> Statut</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new statut -->
            <form action="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=update'; ?>" method="post">
                <div class="form-group">
                    <label for="libStat">Nom du statut</label>
                    <input id="numStat" name="numStat" class="form-control" style="display: none" type="text"
                        value="<?php echo ($ba_bec_numStat); ?>" readonly="readonly" />
                    <input id="libStat" name="libStat" class="form-control" type="text"
                        value="<?php echo ($ba_bec_libStat); ?>" placeholder="Nom du statut..." />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=list'; ?>" class="btn btn-primary">Annuler</a>
                    <button type="submit" class="btn btn-danger">Confirmer Edit ?</button>
                </div>
            </form>
        </div>
    </div>
</div>
