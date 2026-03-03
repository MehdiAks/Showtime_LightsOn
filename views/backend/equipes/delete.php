<?php
/*
 * Vue d'administration (suppression) pour le module equipes.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

sql_connect();

$ba_bec_equipe = null;
if (isset($_GET['numEquipe'])) {
    $ba_bec_numEquipe = (int) $_GET['numEquipe'];
    $ba_bec_equipe = sql_select('EQUIPE', '*', "numEquipe = '$ba_bec_numEquipe'");
    $ba_bec_equipe = $ba_bec_equipe[0] ?? null;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression équipe</h1>
        </div>
        <div class="col-md-12">
            <?php if ($ba_bec_equipe) : ?>
                <form action="<?php echo ROOT_URL . '/api/equipes/delete.php' ?>" method="post">
                    <div class="form-group">
                        <label for="numEquipe">ID équipe</label>
                        <input id="numEquipe" name="numEquipe" class="form-control" type="text" value="<?php echo $ba_bec_equipe['numEquipe']; ?>" readonly />
                    </div>
                    <div class="form-group mt-2">
                        <label for="summary">Nom</label>
                        <input id="summary" name="summary" class="form-control" type="text" value="<?php echo htmlspecialchars($ba_bec_equipe['nomEquipe']); ?>" readonly />
                    </div>
                    <br />
                    <div class="form-group mt-2">
                        <a href="list.php" class="btn btn-primary">Retour à la liste</a>
                        <button type="submit" class="btn btn-danger">Confirmer delete ?</button>
                    </div>
                </form>
            <?php else : ?>
                <div class="alert alert-danger">Équipe introuvable.</div>
                <a href="list.php" class="btn btn-primary">Retour</a>
            <?php endif; ?>
        </div>
    </div>
</div>
