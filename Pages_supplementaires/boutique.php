<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/boutique.css',
];

require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$lights_on_products = [
    [
        'name' => 'T-shirt Lights On',
        'price' => '25€',
        'category' => 'Textile',
        'description' => 'Le maillot essentiel des soirées Lights On, coupe unisexe et tissu doux.',
        'image' => ROOT_URL . '/src/images/article-boutique/t-shirt-lights-on.svg',
    ],
    [
        'name' => 'Tote bag édition nuit',
        'price' => '15€',
        'category' => 'Accessoires',
        'description' => 'Sac robuste noir minuit, parfait pour vos sorties et trajets quotidiens.',
        'image' => ROOT_URL . '/src/images/article-boutique/tote-bag-edition-nuit.svg',
    ],
    [
        'name' => 'Poster officiel',
        'price' => '10€',
        'category' => 'Collection',
        'description' => 'Visuel officiel Lights On en format affiche pour décorer votre intérieur.',
        'image' => ROOT_URL . '/src/images/article-boutique/poster-officiel.svg',
    ],
    [
        'name' => 'Sticker pack',
        'price' => '5€',
        'category' => 'Goodies',
        'description' => 'Pack de stickers premium pour personnaliser ordinateur, gourde et carnet.',
        'image' => ROOT_URL . '/src/images/article-boutique/sticker-pack.svg',
    ],
];
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

    <div class="boutique-grid">
        <?php foreach ($lights_on_products as $product): ?>
            <article class="boutique-card">
                <div class="boutique-card__media">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div>
                    <div class="boutique-card__category">
                        <?php echo htmlspecialchars($product['category']); ?>
                    </div>
                    <h2 class="boutique-card__title"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p class="mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                </div>
                <div class="boutique-card__prices">
                    <span><span>Prix</span><span><?php echo $product['price']; ?></span></span>
                </div>
                <button type="button" class="boutique-card__cta" data-add-to-cart>Ajouter au panier</button>
            </article>
        <?php endforeach; ?>
    </div>
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
