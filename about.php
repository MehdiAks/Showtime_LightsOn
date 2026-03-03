<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/about.css'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<section class="about-page container py-5">
    <h1 class="mb-3">L'Émission</h1>
    <p>
        <strong>Lights On</strong> est une émission pensée pour mettre la lumière sur la nuit : ses visages,
        ses lieux, sa culture et son énergie.
    </p>
    <p>
        Chaque épisode suit une structure claire : ouverture plateau, sujet principal, chronique, puis rencontre
        avec un·e invité·e qui incarne la scène nocturne.
    </p>
    <p>
        Direction artistique : univers contrasté, néons chauds/froids, identité visuelle percutante et rythme éditorial
        inspiré de références pop comme Arcane, Burger Quiz et Casino Royale.
    </p>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
