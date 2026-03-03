<?php
/*
 * Vue d'administration (création) pour le module thematiques.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';
?>

<!-- Bootstrap form to create a new statut -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouvelle Thematique</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new statut -->
            <form action="<?php echo ROOT_URL . '/api/thematiques/create.php' ?>" method="post">
                <div class="form-group">
                    <label for="libThem">Nom de thematique</label>
                    <input id="libThem" name="libThem" class="form-control" type="text" autofocus="autofocus"
                        placeholder="Nom de la thématique..." required />
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Annuler</a>
                    <button type="submit" class="btn btn-success">Confirmer create ?</button>
                </div>
            </form>
        </div>
    </div>
</div>
