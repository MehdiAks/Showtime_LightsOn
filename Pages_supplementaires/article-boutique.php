<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/boutique.css',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$ba_bec_numArtBoutique = (int) ($_GET['numArtBoutique'] ?? 0);
$ba_bec_rows = $ba_bec_numArtBoutique > 0 ? sql_select('boutique', '*', "numArtBoutique = '$ba_bec_numArtBoutique'") : [];
$ba_bec_article = $ba_bec_rows[0] ?? null;

$formatPrice = static function (?float $price): string {
    if ($price === null) {
        return 'Prix à renseigner';
    }
    return number_format($price, 2, ',', ' ') . ' €';
};

$formatList = static function ($value, string $placeholder): string {
    if ($value === null || $value === '') {
        return $placeholder;
    }
    if (is_array($value)) {
        return implode(', ', $value);
    }
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return implode(', ', $decoded);
        }
        return $value;
    }
    return $placeholder;
};

$extractImage = static function ($value): string {
    if (empty($value)) {
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
        return $value;
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

<section class="boutique-page">
    <?php if (!$ba_bec_article): ?>
        <div class="boutique-note">Article introuvable.</div>
    <?php else: ?>
        <?php
        $imageName = $extractImage($ba_bec_article['urlPhotoArtBoutique'] ?? '');
        $imageUrl = $imageName ? $resolve_boutique_image_url($imageName) : '';
        $title = trim((string) ($ba_bec_article['libArtBoutique'] ?? ''));
        $category = trim((string) ($ba_bec_article['categorieArtBoutique'] ?? ''));
        $description = trim((string) ($ba_bec_article['descArtBoutique'] ?? ''));
        ?>
        <article class="boutique-detail">
            <div class="boutique-detail__media">
                <?php if ($imageUrl): ?>
                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($title !== '' ? $title : 'Article boutique'); ?>">
                <?php else: ?>
                    Image de l'article à venir
                <?php endif; ?>
            </div>
            <div class="boutique-detail__content">
                <p class="boutique-card__category"><?php echo htmlspecialchars($category !== '' ? $category : 'Catégorie à définir'); ?></p>
                <h1><?php echo htmlspecialchars($title !== '' ? $title : 'Nom de l\'article à compléter'); ?></h1>
                <p><?php echo htmlspecialchars($description !== '' ? $description : 'Description à venir pour cet article.'); ?></p>
                <div class="boutique-card__details">
                    <span><strong>Couleurs :</strong> <?php echo htmlspecialchars($formatList($ba_bec_article['couleursArtBoutique'] ?? '', 'À renseigner')); ?></span>
                    <span><strong>Tailles :</strong> <?php echo htmlspecialchars($formatList($ba_bec_article['taillesArtBoutique'] ?? '', 'À renseigner')); ?></span>
                </div>
                <div class="boutique-card__prices boutique-detail__prices">
                    <span><span>Prix adulte</span><span><?php echo $formatPrice(isset($ba_bec_article['prixAdulteArtBoutique']) ? (float) $ba_bec_article['prixAdulteArtBoutique'] : null); ?></span></span>
                    <span><span>Prix enfant</span><span><?php echo $formatPrice(isset($ba_bec_article['prixEnfantArtBoutique']) && $ba_bec_article['prixEnfantArtBoutique'] !== '' ? (float) $ba_bec_article['prixEnfantArtBoutique'] : null); ?></span></span>
                </div>
                <a class="btn btn-secondary mt-3" href="<?php echo ROOT_URL . '/Pages_supplementaires/boutique.php'; ?>">Retour boutique</a>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
