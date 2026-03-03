<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// Charger les styles spécifiques à cette page
$pageStyles = [
    ROOT_URL . '/src/css/anciens-et-amis.css',
];

require_once 'header.php';
?>


<style>
.bouton{
    background-color: #67081d;
    color: #ddd;
    padding: 10px;
    border-radius: 25px;
}
.logo-anime-wrapper{
    display: flex;
    justify-content: center;
    margin: 20px 0;
}
.logo-anime{
    max-width: 320px;
    width: 100%;
    height: auto;
}
.error404-image{
    display: block;
    max-width: 180px;
    width: 100%;
    height: auto;
    z-index: 1;
}
.error404-image-wrapper{
    display: flex;
    justify-content: flex-end;
    margin-top: 24px;
    margin-bottom: 24px;
}
</style>

<h2>Erreur 404</h2>
<br>
<h3>Une erreur s'est produite. La developpeuse a sans doute du se faire écraser par un bus</h3>
<br>
<h4>Cette page est indisponible pour le moment ou est actuellement en cours de développement.</h4>
<br>
<a href="<?php echo ROOT_URL . '/index.php'; ?>"><button class="bouton">Revenir à l'accueil</button></a>
<br>
<div class="error404-image-wrapper">
    <img class="error404-image" src="<?php echo ROOT_URL . '/src/images/error404.png'; ?>" alt="Illustration erreur 404">
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
