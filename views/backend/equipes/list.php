<?php
/*
 * Vue d'administration (liste) pour le module equipes.
 * - Le gabarit est rendu côté serveur et s'appuie sur les inclusions globales (config/header) déjà chargées.
 * - Les filtres éventuels sont lus via la query string (GET) pour limiter l'affichage sans modifier l'URL de base.
 * - Les résultats sont présentés dans un tableau structuré, avec des actions de consultation/modification/suppression.
 * - Les liens d'action pointent vers les routes backend correspondantes afin d'enchaîner le workflow.
 * - Les classes utilitaires (Bootstrap) gèrent la mise en page et la hiérarchie visuelle des sections.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

sql_connect();

$ba_bec_is_missing_table = sql_is_missing_table('EQUIPE');
$ba_bec_equipes = [];

function ba_bec_equipe_photo_url(?string $path): string
{
    if (!$path) {
        return '';
    }

    if (preg_match('/^(https?:\/\/|\/)/', $path)) {
        return $path;
    }

    if (strpos($path, 'photos-equipes/') === 0) {
        return ROOT_URL . '/src/uploads/' . ltrim($path, '/');
    }

    return ROOT_URL . '/src/uploads/photos-equipes/' . ltrim($path, '/');
}

if (!$ba_bec_is_missing_table) {
    $teamsStmt = $DB->prepare(
        'SELECT numEquipe, codeEquipe, nomEquipe, club, categorie, section, niveau, photoDLequipe, photoStaff
         FROM EQUIPE
         ORDER BY nomEquipe ASC'
    );
    $teamsStmt->execute();
    $ba_bec_equipes = $teamsStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">
                    Retour au panneau admin
                </a>
                <a href="<?php echo ROOT_URL . '/views/backend/equipes/create.php'; ?>" class="btn btn-success">
                    Ajouter une équipe
                </a>
            </div>

            <?php if ($ba_bec_is_missing_table) : ?>
                <div class="alert alert-warning">
                    <div>La table EQUIPE est manquante. Veuillez téléchargé la derniere base de donné fournis.</div>
                </div>
            <?php endif; ?>

            <h1>Liste des équipes</h1>
            <?php
            $ba_bec_flash_messages = flash_get();
            $ba_bec_alert_map = ['success' => 'success', 'error' => 'danger', 'warning' => 'warning'];
            ?>
            <?php foreach ($ba_bec_flash_messages as $ba_bec_flash): ?>
                <div class="alert alert-<?php echo $ba_bec_alert_map[$ba_bec_flash['type']] ?? 'info'; ?>" role="alert">
                    <?php echo htmlspecialchars($ba_bec_flash['message']); ?>
                </div>
            <?php endforeach; ?>
            <?php if (empty($ba_bec_equipes)) : ?>
                <div class="alert alert-info">Aucune équipe trouvée.</div>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Club</th>
                            <th>Catégorie</th>
                            <th>Section</th>
                            <th>Niveau</th>
                            <th>Photo équipe</th>
                            <th>Photo staff</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ba_bec_equipes as $ba_bec_equipe): ?>
                            <?php
                            $ba_bec_photoEquipeUrl = ba_bec_equipe_photo_url($ba_bec_equipe['photoDLequipe'] ?? '');
                            $ba_bec_photoStaffUrl = ba_bec_equipe_photo_url($ba_bec_equipe['photoStaff'] ?? '');
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['numEquipe']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['codeEquipe']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['nomEquipe']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['club']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['categorie']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['section']); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_equipe['niveau']); ?></td>
                                <td>
                                    <?php if ($ba_bec_photoEquipeUrl): ?>
                                        <img src="<?php echo htmlspecialchars($ba_bec_photoEquipeUrl); ?>" alt="Photo équipe"
                                            style="max-width: 80px; height: auto;">
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($ba_bec_photoStaffUrl): ?>
                                        <img src="<?php echo htmlspecialchars($ba_bec_photoStaffUrl); ?>" alt="Photo staff"
                                            style="max-width: 80px; height: auto;">
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit.php?numEquipe=<?php echo $ba_bec_equipe['numEquipe']; ?>" class="btn btn-primary">Edit</a>
                                    <a href="delete.php?numEquipe=<?php echo $ba_bec_equipe['numEquipe']; ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
