<?php
/*
 * Vue d'administration (création) pour le module comments.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirecmodo.php';
include '../../../header.php';

?>

<!-- Bootstrap form to create a new motcle -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création nouveau de Commentaires</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new motcle -->
            <form action="<?php echo ROOT_URL . '/api/comments/create.php' ?>" method="post">
                <div class="form-group">
                    <label for="libCom">Commentaires</label>
                    <input id="libCom" name="libCom" class="form-control" type="text" autofocus="autofocus"
                        placeholder="Votre commentaire..." />
                </div>
                <div class="form-group">
                    <label for="numArt">Article</label>
                    <input id="numArt" name="numArt" class="form-control" type="text" autofocus="autofocus"
                        placeholder="ID article (ex: 42)" />
                </div>
                <div class="form-group">
                    <label for="numMemb">numéro d'utilisateur</label>
                    <input id="numMemb" name="numMemb" class="form-control" type="text" autofocus="autofocus"
                        placeholder="ID utilisateur (ex: 7)" />
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
