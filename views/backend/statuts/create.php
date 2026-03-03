<!--
    /*
     * Vue d'administration (création) pour le module statuts.
     * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
     * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
     * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
     * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
     * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
     */
-->
<!-- Bootstrap form to create a new statut -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouveau Statut</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new statut -->
            <form action="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=store'; ?>" method="post">
                <div class="form-group">
                    <label for="libStat">Nom du statut</label>
                    <input id="libStat" name="libStat" class="form-control" type="text" autofocus="autofocus"
                        placeholder="Nom du statut..." required />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=list'; ?>" class="btn btn-primary">Annuler</a>
                    <button type="submit" class="btn btn-success">Confirmer create ?</button>
                </div>
            </form>
        </div>
    </div>
</div>
