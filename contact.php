<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
$pageStyles = [ROOT_URL . '/src/css/contact.css'];
require_once 'header.php';
?>

<main class="container py-5">
    <div class="row align-items-start g-5">
        <div class="col-lg-6">
            <h1 class="mb-3">Contact & Réseaux</h1>
            <p>
                Une question, une proposition de collaboration, un lieu à nous recommander ?
                Écrivez-nous et rejoignez la communauté Lights On.
            </p>
            <div class="article-content mt-4">
                <h2 class="h5">Nous écrire</h2>
                <p>
                    <strong>Email :</strong> contact@lightson-show.fr<br>
                    <strong>Instagram :</strong> @lightson.show<br>
                    <strong>TikTok :</strong> @lightson.show
                </p>
            </div>
        </div>

        <div class="col-lg-6">
            <form>
                <div class="form-group champ">
                    <input type="text" placeholder="Votre email" required>
                </div>
                <br>
                <div class="form-group champ">
                    <input type="text" placeholder="Sujet (partenariat, média, invité...)" required>
                    <small>Ces informations ne seront pas communiquées à des tiers.</small>
                </div>
                <br>
                <div class="form-group champ">
                    <input type="text" class="msg" placeholder="Votre message" required>
                </div>
                <br>
                <button type="submit" class="btn_envoyer">Envoyer</button>
            </form>
        </div>
    </div>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
