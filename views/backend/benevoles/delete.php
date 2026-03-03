<?php
/*
 * Vue d'administration (suppression) pour le module benevoles.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

if (!isset($_GET['numPersonnel'])) {
    header('Location: ' . ROOT_URL . '/views/backend/benevoles/list.php');
    exit();
}

$ba_bec_numPersonnel = $_GET['numPersonnel'];
$ba_bec_benevole = sql_select('PERSONNEL', '*', "numPersonnel = '$ba_bec_numPersonnel'");
$ba_bec_benevole = $ba_bec_benevole[0] ?? null;

if (!$ba_bec_benevole) {
    header('Location: ' . ROOT_URL . '/views/backend/benevoles/list.php');
    exit();
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/benevoles/list.php'; ?>" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
            <h1>Supprimer un bénévole</h1>
            <p>Confirmez la suppression de <?php echo htmlspecialchars($ba_bec_benevole['prenomPersonnel'] . ' ' . $ba_bec_benevole['nomPersonnel']); ?>.</p>
            <form action="<?php echo ROOT_URL . '/api/benevoles/delete.php'; ?>" method="post">
                <input type="hidden" name="numPersonnel" value="<?php echo htmlspecialchars($ba_bec_benevole['numPersonnel']); ?>" />
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>
