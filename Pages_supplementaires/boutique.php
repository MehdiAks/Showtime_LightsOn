<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/boutique.css',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$ba_bec_products = sql_select('boutique', '*', null, null, 'numArtBoutique ASC');
$ba_bec_is_missing_table = sql_is_missing_table('BOUTIQUE');

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

$format_price = static function ($price): string {
    if ($price === null || $price === '') {
        return 'Prix à renseigner';
    }

    return number_format((float) $price, 2, ',', ' ') . ' €';
};
?>

<section class="boutique-page">
    <div class="boutique-hero">
        <h1>La boutique LIGHTS ON</h1>
        <p>Plongez dans l'univers officiel LIGHTS ON et découvrez une sélection exclusive d'articles à porter, afficher et collectionner.</p>
        <button type="button" class="boutique-cart" aria-live="polite">
            <span>Panier</span>
            <span class="boutique-cart__count" data-cart-count>0</span>
        </button>
    </div>

    <?php if ($ba_bec_is_missing_table): ?>
        <div class="alert alert-warning">La table BOUTIQUE est manquante. Veuillez importer la dernière base de données fournie.</div>
    <?php elseif (empty($ba_bec_products)): ?>
        <div class="alert alert-info">Aucun article boutique n'est disponible pour le moment.</div>
    <?php else: ?>
        <div class="boutique-grid">
            <?php foreach ($ba_bec_products as $ba_bec_product): ?>
                <?php
                $ba_bec_title = trim((string) ($ba_bec_product['libArtBoutique'] ?? ''));
                $ba_bec_category = trim((string) ($ba_bec_product['categorieArtBoutique'] ?? ''));
                $ba_bec_description = trim((string) ($ba_bec_product['descArtBoutique'] ?? ''));
                $ba_bec_image_name = $ba_bec_extract_first_image($ba_bec_product['urlPhotoArtBoutique'] ?? '');
                $ba_bec_image_url = $ba_bec_image_name !== '' ? $resolve_boutique_image_url($ba_bec_image_name) : '';
                ?>
                <article class="boutique-card">
                    <div class="boutique-card__media">
                        <?php if ($ba_bec_image_url !== ''): ?>
                            <img src="<?php echo htmlspecialchars($ba_bec_image_url); ?>" alt="<?php echo htmlspecialchars($ba_bec_title !== '' ? $ba_bec_title : 'Article boutique'); ?>">
                        <?php else: ?>
                            Image de l'article à venir
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="boutique-card__category">
                            <?php echo htmlspecialchars($ba_bec_category !== '' ? $ba_bec_category : 'Catégorie à définir'); ?>
                        </div>
                        <h2 class="boutique-card__title"><?php echo htmlspecialchars($ba_bec_title !== '' ? $ba_bec_title : 'Nom de l\'article à compléter'); ?></h2>
                        <p class="mb-2"><?php echo htmlspecialchars($ba_bec_description !== '' ? $ba_bec_description : 'Description à venir pour cet article.'); ?></p>
                    </div>
                    <div class="boutique-card__prices">
                        <span><span>Prix adulte</span><span><?php echo $format_price($ba_bec_product['prixAdulteArtBoutique'] ?? null); ?></span></span>
                        <span><span>Prix enfant</span><span><?php echo $format_price($ba_bec_product['prixEnfantArtBoutique'] ?? null); ?></span></span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?php echo ROOT_URL . '/Pages_supplementaires/article-boutique.php?numArtBoutique=' . (int) ($ba_bec_product['numArtBoutique'] ?? 0); ?>" class="btn btn-outline-light">Voir le détail</a>
                        <button type="button" class="boutique-card__cta" data-add-to-cart>Ajouter au panier</button>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
    (function() {
        var cartCountElement = document.querySelector('[data-cart-count]');
        var addButtons = document.querySelectorAll('[data-add-to-cart]');

        if (!cartCountElement || addButtons.length === 0) {
            return;
        }

        var cartCount = 0;

        addButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                if (button.dataset.added === 'true') {
                    return;
                }

                button.dataset.added = 'true';
                button.textContent = 'Ajouté';
                button.classList.add('is-added');

                cartCount += 1;
                cartCountElement.textContent = String(cartCount);
            });
        });
    })();
</script>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
