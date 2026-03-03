<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/about.css'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<section class="about-page container py-5">
    <header class="about-hero">
        <p class="about-kicker">L'ÉMISSION</p>
        <h1>Une émission pensée comme une immersion.</h1>
        <p class="about-lead">
            <strong>Lights On</strong> explore la nuit comme un territoire vivant : ses cultures, ses rythmes,
            ses métiers, ses codes et ses récits humains. Chaque épisode embarque le public dans une expérience
            éditoriale fluide, de l'énergie du plateau à l'intimité des témoignages, pour raconter la vie nocturne
            avec exigence, curiosité et style.
        </p>
    </header>

    <section class="about-block" aria-labelledby="format-emission">
        <h2 id="format-emission">FORMAT DE L’ÉMISSION</h2>
        <ol>
            <li><strong>Ouverture immersive</strong> : lancement visuel et sonore pour installer l'ambiance de l'épisode.</li>
            <li><strong>Édito plateau</strong> : présentation du thème, du contexte et de la promesse narrative.</li>
            <li><strong>Sujet principal</strong> : enquête ou reportage au cœur d'un lieu, d'une pratique ou d'une communauté nocturne.</li>
            <li><strong>Chronique culture nuit</strong> : focus sur les tendances, références et signaux créatifs du moment.</li>
            <li><strong>Rencontre invité·e</strong> : interview d'une personnalité qui incarne la scène et ses enjeux.</li>
            <li><strong>Séquence terrain</strong> : immersion en extérieur pour capter l'authenticité du réel.</li>
            <li><strong>Décryptage</strong> : mise en perspective des observations avec un regard éditorial clair.</li>
            <li><strong>Clôture signature</strong> : conclusion incarnée, teaser du prochain épisode et dernière note d'émotion.</li>
        </ol>
    </section>

    <section class="about-block" aria-labelledby="direction-artistique">
        <h2 id="direction-artistique">DIRECTION ARTISTIQUE</h2>
        <p>
            L'identité visuelle de <strong>Lights On</strong> repose sur un contraste nocturne assumé : noirs profonds,
            lumières dirigées, textures brutes et accents néon chauds/froids. La mise en scène mêle élégance urbaine,
            rythme pop et cadrages cinématographiques pour produire une atmosphère à la fois premium et accessible.
            Le ton reste incisif, contemporain et incarné, avec une narration inspirée de l'énergie de références
            fortes comme <em>Arcane</em>, <em>Burger Quiz</em> et <em>Casino Royale</em>.
        </p>
    </section>
</section>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
