<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/about.css'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<section class="about-page container py-5">
    <h1 class="mb-3">À propos</h1>
    <p>
        Ce site a été conçu par <strong>Les égarés</strong>, une équipe d’étudiants passionnés
        qui a imaginé et développé cette vitrine autour du BEC Basket.
    </p>
    <p>Mehdi Afankous, Romain Bezombes, Alvin Bonaventure-Sanchez, Juliette Rieunau, Phuong-My Nguyen</p>
    <p>
        Merci à <strong>Martine Bornerie</strong>, l’enseignante qui a supervisé le cours
        et accompagné le projet.
    </p>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
