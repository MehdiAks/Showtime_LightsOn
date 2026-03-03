<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
include '../../../header.php';

$ba_bec_articles = sql_select('boutique', '*', null, null, 'numArtBoutique ASC');
$ba_bec_is_missing_table = sql_is_missing_table('BOUTIQUE');

$ba_bec_format_json_list = static function ($value): string {
    if ($value === null || $value === '') {
        return '-';
    }

    if (is_array($value)) {
        return implode(', ', $value);
    }

    if (!is_string($value)) {
        return '-';
    }

    $decoded = json_decode($value, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return empty($decoded) ? '-' : implode(', ', $decoded);
    }

    return $value;
};

$ba_bec_extract_first_image = static function ($value): string {
    if ($value === null || $value === '') {
        return '';
    }

    if (is_array($value)) {
        return (string) ($value[0] ?? '');
    }

    if (is_string($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return (string) ($decoded[0] ?? '');
        }

        return trim($value);
    }

    return '';
};


$resolve_boutique_image_url = static function (string $value): string {
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (strpos($value, '/src/') === 0) {
        return ROOT_URL . $value;
    }

    return ROOT_URL . '/src/images/article-boutique/' . rawurlencode($value);
};
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3 d-flex gap-2 flex-wrap">
                <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>" class="btn btn-secondary">Retour au panneau admin</a>
                <a href="<?php echo ROOT_URL . '/views/backend/boutique/create.php'; ?>" class="btn btn-success">Ajouter un article</a>
            </div>

            <h1>Boutique</h1>

            <?php if ($ba_bec_is_missing_table): ?>
                <div class="alert alert-warning">
                    La table BOUTIQUE est manquante. Veuillez téléchargé la derniere base de donné fournis.
                </div>
            <?php endif; ?>

            <?php if (!$ba_bec_is_missing_table && empty($ba_bec_articles)): ?>
                <div class="alert alert-info">Aucun article boutique trouvé.</div>
            <?php endif; ?>

            <?php if (!$ba_bec_is_missing_table && !empty($ba_bec_articles)): ?>
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Couleurs</th>
                            <th>Tailles</th>
                            <th>Prix adulte</th>
                            <th>Prix enfant</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ba_bec_articles as $ba_bec_article): ?>
                            <?php $ba_bec_first_image = $ba_bec_extract_first_image($ba_bec_article['urlPhotoArtBoutique'] ?? ''); ?>
                            <tr>
                                <td><?php echo (int) $ba_bec_article['numArtBoutique']; ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_article['libArtBoutique'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_article['categorieArtBoutique'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_format_json_list($ba_bec_article['couleursArtBoutique'] ?? '')); ?></td>
                                <td><?php echo htmlspecialchars($ba_bec_format_json_list($ba_bec_article['taillesArtBoutique'] ?? '')); ?></td>
                                <td><?php echo number_format((float) ($ba_bec_article['prixAdulteArtBoutique'] ?? 0), 2, ',', ' ') . ' €'; ?></td>
                                <td>
                                    <?php if (($ba_bec_article['prixEnfantArtBoutique'] ?? null) !== null): ?>
                                        <?php echo number_format((float) $ba_bec_article['prixEnfantArtBoutique'], 2, ',', ' ') . ' €'; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($ba_bec_first_image !== ''): ?>
                                        <a href="<?php echo htmlspecialchars($resolve_boutique_image_url($ba_bec_first_image)); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($ba_bec_first_image); ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo ROOT_URL . '/views/backend/boutique/edit.php?numArtBoutique=' . (int) $ba_bec_article['numArtBoutique']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                                        <a href="<?php echo ROOT_URL . '/views/backend/boutique/delete.php?numArtBoutique=' . (int) $ba_bec_article['numArtBoutique']; ?>" class="btn btn-sm btn-danger">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
