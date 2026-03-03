<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/notre-histoire.css'];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<main id="notre-histoire">
    <section class="hero-section text-center">
        <video class="hero-logo mb-3" autoplay muted loop playsinline poster="<?php echo ROOT_URL . '/src/images/logo/logo-bec/logo.png'; ?>">
            <source src="<?php echo ROOT_URL . '/src/images/logo/logo-bec/logo-anime-transparent.mov'; ?>" type="video/quicktime">
        </video>
        <h1>Notre histoire</h1>
        <p class="lead mx-auto">
            Lights On est né d'une envie simple : raconter la nuit autrement, avec un regard culturel,
            esthétique et humain.
        </p>
    </section>

    <section class="timeline container">
        <div class="timeline-item">
            <div class="timeline-content">
                <h2>Pourquoi la vie nocturne ?</h2>
                <p>Parce qu'elle révèle des métiers, des talents, des lieux et des publics souvent invisibles.</p>
                <p>Notre ambition : montrer la créativité qui s'active quand la ville change de rythme.</p>
            </div>
            <div class="timeline-image">
                <img src="/src/images/notre-histoire/notre-histoire-4.webp" alt="Origine du projet">
            </div>
        </div>

        <div class="timeline-item reverse">
            <div class="timeline-content">
                <h2>Naissance du concept</h2>
                <p>Le nom <strong>Lights On</strong> traduit notre promesse : allumer les projecteurs sur la nuit.</p>
                <p>Le format mélange plateau, reportages terrain, chronique et invité·e fil rouge.</p>
            </div>
            <div class="timeline-image">
                <img src="/src/images/notre-histoire/notre-histoire-3.webp" alt="Concept Lights On">
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-content">
                <h2>Nos inspirations</h2>
                <p>Arcane pour la direction artistique, Burger Quiz pour le rythme, Casino Royale pour l'élégance.</p>
                <p>Un mélange pop, urbain et éditorial pour créer une identité reconnaissable.</p>
            </div>
        </div>

        <div class="timeline-item reverse">
            <div class="timeline-content">
                <h2>De l'idée à la diffusion</h2>
                <p>Timeline : idéation → pilote → tournage → diffusion multi-plateformes.</p>
                <p>Objectif 2026 : installer un rendez-vous régulier autour de la culture nocturne.</p>
            </div>
            <div class="timeline-image">
                <img src="/src/images/notre-histoire/notre-histoire-2.webp" alt="Diffusion de l'émission">
            </div>
        </div>
    </section>

    <section class="banner banner-center my-5">
        <img src="/src/images/notre-histoire/notre-histoire-1.webp" alt="Bannière Lights On">
        <div class="banner-text">
            <h2>Mettre la lumière sur la nuit</h2>
        </div>
    </section>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
