<!--
    /*
     * Vue d'administration (liste) pour le module statuts.
     * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
     * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
     * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
     * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
     * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
     */
-->
<!-- Bootstrap default layout to display all statuts in foreach -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
            </div>
            <h1>Statuts</h1>
            <?php
            $ba_bec_flash_messages = flash_get();
            $ba_bec_alert_map = ['success' => 'success', 'error' => 'danger', 'warning' => 'warning'];
            ?>
            <?php foreach ($ba_bec_flash_messages as $ba_bec_flash): ?>
                <div class="alert alert-<?php echo $ba_bec_alert_map[$ba_bec_flash['type']] ?? 'info'; ?>" role="alert">
                    <?php echo htmlspecialchars($ba_bec_flash['message']); ?>
                </div>
            <?php endforeach; ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom des statuts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ba_bec_statuts as $ba_bec_statut){ ?>
                        <tr>
                            <td><?php echo($ba_bec_statut['numStat']); ?></td>
                            <td><?php echo($ba_bec_statut['libStat']); ?></td>
                            <td>
                                <a href="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=edit&numStat=' . $ba_bec_statut['numStat']; ?>" class="btn btn-primary">Edit</a>
                                <a href="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=delete&numStat=' . $ba_bec_statut['numStat']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="<?php echo ROOT_URL . '/public/index.php?controller=statut&action=create'; ?>" class="btn btn-success">Create</a>
        </div>
    </div>
</div>
