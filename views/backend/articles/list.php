<!--
    /*
     * Vue d'administration (liste) pour le module articles.
     * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
     * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
     * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
     * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
     * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
     */
-->
<!-- Bootstrap default layout to display all articles in foreach -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
            </div>
            <h1>Articles</h1>
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
                        <th>Date</th>
                        <th>Titre</th>
                        <th>Chapeau</th>
                        <th>Accroche</th>
                        <th>Mots-clés</th>
                        <th>Thématique</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ba_bec_articles as $ba_bec_article) {
                        ?>
                        <tr>
                            <td><?php echo $ba_bec_article['numArt']; ?></td>
                            <td><?php echo $ba_bec_article['dtCreaArt']; ?></td>
                            <td><?php echo $ba_bec_article['libTitrArt']; ?></td>
                            <td style="max-width: 400px; white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo substr($ba_bec_article['libChapoArt'], 0, 100) . (strlen($ba_bec_article['libChapoArt']) > 100 ? '...' : ''); ?>
                            </td>
                            <td style="max-width: 400px; white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo $ba_bec_article['libAccrochArt']; ?>
                            </td>
                            <td>
                                <?php
                                foreach ($ba_bec_keywordsart as $ba_bec_keywordart) {
                                    if ($ba_bec_keywordart['numArt'] == $ba_bec_article['numArt']) {
                                        foreach ($ba_bec_keywords as $ba_bec_keyword) {
                                            if ($ba_bec_keyword['numMotCle'] == $ba_bec_keywordart['numMotCle']) {
                                                echo $ba_bec_keyword['libMotCle'] . "<br>";
                                            }
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                foreach ($ba_bec_thematiques as $ba_bec_thematique) {
                                    if ($ba_bec_thematique['numThem'] == $ba_bec_article['numThem']) {
                                        echo $ba_bec_thematique['libThem'];
                                        break;
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo ROOT_URL . '/public/index.php?controller=article&action=edit&numArt=' . $ba_bec_article['numArt']; ?>"
                                    class="btn btn-primary">Edit</a>
                                <a href="<?php echo ROOT_URL . '/public/index.php?controller=article&action=delete&numArt=' . $ba_bec_article['numArt']; ?>"
                                    class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="<?php echo ROOT_URL . '/public/index.php?controller=article&action=create'; ?>" class="btn btn-success">Create</a>
        </div>
    </div>
</div>
