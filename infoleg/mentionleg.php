<?php
// Commentaire: Fichier PHP pour mentionleg.
include '../header.php';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mentions légales | Bordeaux Étudiant Club</title>

        <!-- Link to Bootstrap -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

        <!-- Optional custom styles -->
        <link href="css/style_modif.css" rel="stylesheet"/>
        <link href="css/font.css" rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    </head>

    <body>


        <main class="container my-5">
            <h1 class="text-center mb-4">Mentions légales</h1>

            <h2>Éditeur</h2>

            <p>Le présent site internet est édité par l’entreprise fictive Bordeaux étudiant club, une organisation basée au 1 rue Jacques Ellul, 33000 
                Bordeaux. Cette entreprise aurait été fondée par un collectif d’étudiants issus de Bordeaux Montaigne, plus précisément des étudiants de MMI
                (Métiers du Multimédia et de l’Internet). 
                Courriel de contact : contact@bordeauxetudiantclub.com</p>

            <br></br>

            <h2>Hébergement du site</h2>


            <p>Le site Bordeaux étudiant club est hébergé par (nom hébergeur), situé en France.
            L’hébergeur assure le stockage et la mise à disposition du site sur Internet.</p>

            <br>

            <h2>La protection des données (RGPD)</h2>

            <p>Bordeaux étudiant club accorde une grande importance à la protection des données personnelles. 
            Les informations concernant la collecte et le traitement des données sont détaillées dans la (lien qui renvoie Politique de confidentialité) du site.</p>

            <br>

            <h2>Droits d'auteur</h2>

            <p>L’ensemble des contenus présents sur le site (textes, images, articles, logos, vidéos) est protégé par le droit d’auteur.
                Toute reproduction, modification ou diffusion, totale ou partielle, sans autorisation préalable est interdite.</p>

            <br></br>

            
        </main>

    </body>
</html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>