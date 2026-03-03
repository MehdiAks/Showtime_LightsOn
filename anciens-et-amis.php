<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Charger les styles spécifiques à cette page
$pageStyles = [
    ROOT_URL . '/src/css/anciens-et-amis.css',
];

require_once 'header.php';
?>

<main class="container py-5" id="Anciens-et-amis">
    <div class="mb-5">
        <h1 class="mb-3">Anciens et amis du BEC</h1>
        <p>
            Depuis plus de 125 ans, le BEC rayonne !
        </p>
        <p>
            Rejoignez l’association des Anciens et Amis du Bordeaux Etudiants Club. Près de 1000 membres sont déjà inscrits, alors pourquoi pas vous ?
        </p>
        <a class="btn btn-primary" href="https://anciensbec-bordeaux.fr" target="_blank" rel="noopener noreferrer">
            Accéder au site des Anciens du BEC
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="article-content h-100">
                <img
                    src="src/images/background/background-actualite.jpg"
                    class="article-image mb-3"
                    alt="Ancien joueur en entraînement"
                >
                <h2 class="h5">Annuaire</h2>
                <p>
                    Retrouvez les anciens du BEC sur notre annuaire ! Vous voulez nous rejoindre ? Retrouvez les tarifs et informations sur le lien suivant :
                </p>
                <a href="https://anciensbec-bordeaux.fr/lannuaire/">
                    Annuaire du BEC 
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="article-content h-100">
                <img
                    src="src/images/background/background-article.jpg"
                    class="article-image mb-3"
                    alt="Supporters réunis"
                >
                <h2 class="h5">Amis et événements</h2>
                <p>
                    Retrouvez nos amis et événements sur Facebook 
                </p>
                <a href="https://www.facebook.com/becofficiel/?locale=fr_FR">
                    Notre Facebook
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="article-content h-100">
                <img
                    src="src/images/background/background-index-1.webp"
                    class="article-image mb-3"
                    alt="Moment de convivialité"
                >
                <h2 class="h5">Retrouvailles et évenements</h2>
                <p>
                    Quand est-ce qu'on se retrouve ? 
                </p>
                <a href="erreur404.php">
                    Retrouvailles
                </a>
            </div>
        </div>
    </div>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
