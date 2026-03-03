<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/boutique.css',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$ba_bec_articles = sql_select('boutique', '*', null, null, 'numArtBoutique');

$formatPrice = static function (?float $price): string {
    if ($price === null) {
        return '';
    }
    return number_format($price, 2, ',', ' ') . ' €';
};

$formatList = static function ($value): string {
    if ($value === null || $value === '') {
        return '';
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
    return '';
};

$extractImage = static function ($value): string {
    if (empty($value)) {
        return '';
    }
    if (is_array($value)) {
        return $value[0] ?? '';
    }
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded[0] ?? '';
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

$buildPlaceholderText = static function (?string $value, string $placeholder): string {
    $value = trim((string) $value);
    return $value !== '' ? $value : $placeholder;
};
?>

<section class="boutique-page">
    <div class="boutique-hero">
        <h1>La boutique du BEC</h1>
        <p>Découvrez nos articles officiels. Toutes les tenues sont disponibles du XS au XXL pour équiper petits et grands supporters.</p>
    </div>

    <?php if (empty($ba_bec_articles)): ?>
        <div class="boutique-note">
            Aucun article n'est disponible pour le moment.
        </div>
    <?php endif; ?>

    <div class="boutique-grid">
        <?php foreach ($ba_bec_articles as $article): ?>
            <?php
            $imageName = $extractImage($article['urlPhotoArtBoutique'] ?? '');
            $imageUrl = $imageName ? $resolve_boutique_image_url($imageName) : '';
            $adultPrice = $article['prixAdulteArtBoutique'] ?? null;
            $childPrice = $article['prixEnfantArtBoutique'] ?? null;
            $colors = $formatList($article['couleursArtBoutique'] ?? '');
            $sizes = $formatList($article['taillesArtBoutique'] ?? '');
            ?>
            <article class="boutique-card">
                <div class="boutique-card__media">
                    <?php if ($imageUrl): ?>
                        <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($article['libArtBoutique']); ?>">
                    <?php else: ?>
                        Image à venir
                    <?php endif; ?>
                </div>
                <div>
                    <div class="boutique-card__category">
                        <?php echo htmlspecialchars($buildPlaceholderText($article['categorieArtBoutique'] ?? '', 'Catégorie à définir')); ?>
                    </div>
                    <h2 class="boutique-card__title"><?php echo htmlspecialchars($buildPlaceholderText($article['libArtBoutique'] ?? '', 'Nom de l\'article à compléter')); ?></h2>
                    <p class="mb-2"><?php echo htmlspecialchars($buildPlaceholderText($article['descArtBoutique'] ?? '', 'Description à venir pour cet article.')); ?></p>
                    <div class="boutique-card__details">
                        <span><strong>Couleurs :</strong> <?php echo htmlspecialchars($colors !== '' ? $colors : 'À renseigner'); ?></span>
                        <span><strong>Tailles :</strong> <?php echo htmlspecialchars($sizes !== '' ? $sizes : 'À renseigner'); ?></span>
                    </div>
                </div>
                <div class="boutique-card__prices">
                    <?php if ($childPrice !== null && $childPrice !== ''): ?>
                        <span><span>Adulte</span><span><?php echo $formatPrice((float) $adultPrice); ?></span></span>
                        <span><span>Enfant</span><span><?php echo $formatPrice((float) $childPrice); ?></span></span>
                    <?php else: ?>
                        <span><span>Prix</span><span><?php echo $formatPrice((float) $adultPrice); ?></span></span>
                    <?php endif; ?>
                </div>
                <a class="boutique-card__link" href="<?php echo ROOT_URL . '/Pages_supplementaires/article-boutique.php?numArtBoutique=' . (int) $article['numArtBoutique']; ?>">Voir le détail</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
