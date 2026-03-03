<?php
/*
 * Vue d'administration (liste) pour le module comments.
 * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
 * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
 * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
 * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
 * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirecmodo.php';
include '../../../header.php'; // contains the header and call to config.php

//Load all statuts
$ba_bec_coms = sql_select("comment", "*");
$ba_bec_articles = sql_select("article", "*");
$ba_bec_membres = sql_select("membre", "*");
if(isset($_GET['numCom'])){
    $ba_bec_numCom = $_GET['numCom'];
    $ba_bec_dtCreaCom = sql_select("comment", "dtCreaCom", "numCom = $ba_bec_numCom")[0]['dtCreaCom'];
    $ba_bec_libCom = sql_select("comment", "libCom", "numCom = $ba_bec_numCom")[0]['libCom'];
    $ba_bec_dtModCom = sql_select("comment", "dtModCom", "numCom = $ba_bec_numCom")[0]['dtModCom'];
    $ba_bec_attModOK = sql_select("comment", "attModOK", "numCom = $ba_bec_numCom")[0]['attModOK'];
    $ba_bec_notifComKOAff = sql_select("comment", "notifComKOAff", "numCom = $ba_bec_numCom")[0]['notifComKOAff'];
    $ba_bec_dtDelLogCom = sql_select("comment", "dtDelLogCom", "numCom = $ba_bec_numCom")[0]['dtDelLogCom'];
    $ba_bec_delLogiq = sql_select("comment", "delLogiq", "numCom = $ba_bec_numCom")[0]['delLogiq'];
    $ba_bec_numArt = sql_select("comment", "numArt", "numCom = $ba_bec_numCom")[0]['numArt'];
    $ba_bec_numMemb = sql_select("comment", "numMemb", "numCom = $ba_bec_numCom")[0]['numMemb'];

    $ba_bec_pseudoMemb = sql_select("membre", "pseudoMemb", "numMemb = $ba_bec_numMemb")[0]['pseudoMemb'];
    $ba_bec_libTitrArt = sql_select("article", "libTitrArt", "numArt = $ba_bec_numArt")[0]['libTitrArt'];
    $ba_bec_parag1Art = sql_select("article", "parag1Art", "numArt = $ba_bec_numArt")[0]['parag1Art'];
}
$ba_bec_coms = sql_select("comment c INNER JOIN article a ON c.numArt = a.numArt", 
        "c.numCom, c.dtCreaCom, c.libCom, c.dtModCom, c.delLogiq, c.attModOK, c.notifComKOAff, c.numArt, c.numMemb, a.libTitrArt");
$ba_bec_coms = sql_select("comment c INNER JOIN article a ON c.numArt = a.numArt
                                   INNER JOIN membre m ON c.numMemb = m.numMemb", 
                        "c.numCom, c.dtCreaCom, c.libCom, c.dtModCom, c.delLogiq, c.attModOK, c.notifComKOAff, c.numArt, c.numMemb, a.libTitrArt, m.pseudoMemb");


                       
?>

<!-- Bootstrap default layout to display all statuts in foreach -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
            </div>
            <table class="table table-striped">
    <div class="row">
        <h1 class="titre text-start" style="margin: 2rem 10rem 2rem 10rem;">Commentaires en attente</h1>
                <thead>
                    <tr>
                        <th>Titre Article</th>
                        <th>Nom d'utilisateur</th>
                        <th>Date</th>
                        <th>Contenu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        <?php  foreach($ba_bec_coms as $ba_bec_com ){ 
                            if ($ba_bec_com['attModOK'] == 0 && $ba_bec_com['delLogiq'] == 0){?> 
                                <?php ?> 
                                    <tr>
                                        <td><?php echo($ba_bec_com['libTitrArt']); ?></td>
                                        <td><?php echo($ba_bec_com['pseudoMemb']); ?></td>
                                        <td><?php echo($ba_bec_com['dtCreaCom']); ?></td>
                                        <td><?php echo($ba_bec_com['libCom']); ?></td>
                                        <td>
                                            <a href="edit - ATTENTE MODIFICATION.php?numCom=<?php echo($ba_bec_com['numCom']); ?>" class="btn btn-warning">Edit</a>
                                        </td>
                                        <td>
                                            <a href="edit - CONTROLLER MODIFICATION.php?numCom=<?php echo($ba_bec_com['numCom']); ?>" class="btn btn-primary">Controller</a>
                                        </td>
                                        

                                    </tr>
                        <?php }} ?>
                </tbody>
                
            </table>
            
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
    <div class="row">
        <h1 class="titre text-start" style="margin: 2rem 10rem 2rem 10rem;">Commentaires contrôlés</h1>

                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Dernière modif</th>
                        <th>Contenu</th>
                        <th>Publication</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        <?php  foreach($ba_bec_coms as $ba_bec_com){ 
                            if ($ba_bec_com['attModOK'] == 1 && $ba_bec_com['delLogiq'] == 0){?> 
                                <?php ?> <tr>
                                    <td><?php echo($ba_bec_com['pseudoMemb']); ?></td>

                                    <td><?php echo($ba_bec_com['dtModCom']); ?></td>
                                    <td><?php echo($ba_bec_com['libCom']); ?></td>
                                    <td><?php echo($ba_bec_com['dtCreaCom']); ?></td>
                                    <td>
                                        <a href="edit - CONTROLLER MODIFICATION.php?numCom=<?php echo($ba_bec_com['numCom']); ?>" class="btn btn-warning">Edit</a>
                                    </td>
                                    

                                </tr>
                        <?php }} ?>
                </tbody>
            </table>
            
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
    <div class="row">
        <h1 class="titre text-start" style="margin: 2rem 10rem 2rem 10rem;">suppression logique</h1>

                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>date suppr logique</th>
                        <th>Contenu</th>
                        <th>Publication</th>
                        <th>Raison refus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                <?php  foreach($ba_bec_coms as $ba_bec_com){ 
                            if ($ba_bec_com['attModOK'] == 0 && $ba_bec_com['delLogiq'] == 1){?> 
                                <?php ?> <tr>
                                    <td><?php echo($ba_bec_com['pseudoMemb']); ?></td>
                                    <td><?php echo($ba_bec_com['dtModCom']); ?></td>
                                    <td><?php echo($ba_bec_com['libCom']); ?></td>
                                    <td><?php echo($ba_bec_com['dtCreaCom']); ?></td>
                                    <td><?php echo($ba_bec_com['notifComKOAff']); ?></td>
                                    <td>
                                        <a href="edit - SUPPRESION.php?numCom=<?php echo($ba_bec_com['numCom']); ?>" class="btn btn-warning">Edit</a>
                                    </td>
                                    

                                </tr>
                        <?php }} ?>
                </tbody>
            </table>
            
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
    <div class="row">
        <h1 class="titre text-start" style="margin: 2rem 10rem 2rem 10rem;">suppression physique</h1>

                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Date suppr logique</th>
                        <th>Contenu</th>
                        <th>Publication</th>
                        <th>Raison refus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                <?php  foreach($ba_bec_coms as $ba_bec_com){ 
                            if ($ba_bec_com['delLogiq'] == 1){?> 
                                <?php ?> <tr>
                                    <td><?php echo($ba_bec_com['pseudoMemb']); ?></td>
                                    <td><?php echo($ba_bec_com['dtModCom']); ?></td>
                                    <td><?php echo($ba_bec_com['libCom']); ?></td>
                                    <td><?php echo($ba_bec_com['dtCreaCom']); ?></td>
                                    <td><?php echo($ba_bec_com['notifComKOAff']); ?></td>
                                    <td>
                                        <a href="delete.php?numCom=<?php echo($ba_bec_com['numCom']); ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                    

                                </tr>
                        <?php }} ?>
                </tbody>
            </table>
            
        </div>
    </div>
</div>
<div class="col-md-2" style="margin: 0.5rem 1rem;">
    <a href="create.php" class="btn btn-success">Create</a>
</div>
