<?php
/*
 * Vue d'administration (liste) pour le module members.
 * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
 * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
 * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
 * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
 * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';



// Charger tous les membres avec leur statut
$ba_bec_members = sql_select("MEMBRE INNER JOIN STATUT ON MEMBRE.numStat = STATUT.numStat", "*");
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
            </div>
            <h1>Membres</h1>
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
                        <th>ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Accord RGPD</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ba_bec_members)): ?>
                        <?php foreach ($ba_bec_members as $ba_bec_mem): ?>
                            <tr>
                                <td><?php echo ($ba_bec_mem['numMemb']); ?></td>
                                <td><?php echo ($ba_bec_mem['prenomMemb']); ?></td>
                                <td><?php echo ($ba_bec_mem['nomMemb']); ?></td>
                                <td><?php echo ($ba_bec_mem['eMailMemb']); ?></td>
                                <td><?= $ba_bec_mem['accordMemb'] ? '✅ Oui' : '❌ Non'; ?></td>
                                <td><?php echo ($ba_bec_mem['libStat']); ?></td>
                                <td>
                                    <a href="edit.php?numMemb=<?= htmlspecialchars($ba_bec_mem['numMemb']); ?>"
                                        class="btn btn-primary">Edit</a>
                                    <?php if ($ba_bec_mem['numStat'] == 1): ?>
                                        <button class="btn btn-danger disabled">Delete</button>
                                    <?php else: ?>
                                        <a href="delete.php?numMemb=<?= htmlspecialchars($ba_bec_mem['numMemb']); ?>"
                                            class="btn btn-danger">Delete</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Aucun membre trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="create.php" class="btn btn-success">Créer</a>
        </div>
    </div>
</div>
