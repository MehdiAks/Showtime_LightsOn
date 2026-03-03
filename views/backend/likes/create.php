<?php
/*
 * Vue d'administration (création) pour le module likes.
 * - Cette page expose un formulaire HTML complet permettant de saisir les données métier.
 * - L'action du formulaire pointe vers la route de création côté backend (controller/action).
 * - Les champs sont regroupés par sections pour guider l'utilisateur et faciliter la validation.
 * - Les boutons principaux déclenchent l'envoi et les liens secondaires ramènent au tableau de bord ou à la liste.
 * - Les classes Bootstrap structurent la mise en forme sans logique métier dans la vue.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirecmodo.php';
include '../../../header.php';

$ba_bec_articles = sql_select("ARTICLE", "numArt, libTitrArt", null, null, "numArt ASC");
$ba_bec_members = sql_select("MEMBRE", "numMemb, pseudoMemb", null, null, "numMemb ASC");

$ba_bec_articleIdToTitle = [];
$ba_bec_articleTitleToId = [];
foreach ($ba_bec_articles as $ba_bec_article) {
    $ba_bec_articleIdToTitle[$ba_bec_article['numArt']] = $ba_bec_article['libTitrArt'];
    $ba_bec_articleTitleToId[$ba_bec_article['libTitrArt']] = $ba_bec_article['numArt'];
}

$ba_bec_memberIdToPseudo = [];
$ba_bec_memberPseudoToId = [];
foreach ($ba_bec_members as $ba_bec_member) {
    $ba_bec_memberIdToPseudo[$ba_bec_member['numMemb']] = $ba_bec_member['pseudoMemb'];
    $ba_bec_memberPseudoToId[$ba_bec_member['pseudoMemb']] = $ba_bec_member['numMemb'];
}
?>

<!-- Bootstrap form to create a new like -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Création Nouveau Like</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to create a new like -->
            <form action="<?php echo ROOT_URL . '/api/likes/create.php' ?>" method="post">
                <div class="form-group">
                    <label for="numArt">Article</label>
                    <input id="numArt" name="numArt" class="form-control" type="text" placeholder="ID article (ex: 42)" required />
                </div>
                <div class="form-group">
                    <label for="numMemb">Numéro d'utilisateur</label>
                    <input id="numMemb" name="numMemb" class="form-control" type="text" placeholder="ID utilisateur (ex: 7)" required />
                </div>
                <div class="form-group">
                    <label for="likeA">Like / Dislike</label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="likeA" value="0" />
                        <input class="form-check-input" type="checkbox" id="likeA" name="likeA" value="1" checked />
                        <label class="form-check-label" for="likeA">Like (désactiver pour dislike)</label>
                    </div>
                </div>
                <br />
                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Annuler</a>
                    <button type="submit" class="btn btn-success">Confirmer Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const articleIdToTitle = <?php echo json_encode($ba_bec_articleIdToTitle); ?>;
    const articleTitleToId = <?php echo json_encode($ba_bec_articleTitleToId); ?>;
    const memberIdToPseudo = <?php echo json_encode($ba_bec_memberIdToPseudo); ?>;
    const memberPseudoToId = <?php echo json_encode($ba_bec_memberPseudoToId); ?>;

    const articleIdInput = document.getElementById('numArt');
    const articleTitleInput = document.getElementById('libTitrArt');
    const memberIdInput = document.getElementById('numMemb');
    const memberPseudoInput = document.getElementById('pseudoMemb');

    articleIdInput.addEventListener('input', () => {
        const title = articleIdToTitle[articleIdInput.value];
        if (title) {
            articleTitleInput.value = title;
        } else if (articleIdInput.value === '') {
            articleTitleInput.value = '';
        }
    });

    articleTitleInput.addEventListener('input', () => {
        const id = articleTitleToId[articleTitleInput.value];
        if (id) {
            articleIdInput.value = id;
        } else if (articleTitleInput.value === '') {
            articleIdInput.value = '';
        }
    });

    memberIdInput.addEventListener('input', () => {
        const pseudo = memberIdToPseudo[memberIdInput.value];
        if (pseudo) {
            memberPseudoInput.value = pseudo;
        } else if (memberIdInput.value === '') {
            memberPseudoInput.value = '';
        }
    });

    memberPseudoInput.addEventListener('input', () => {
        const id = memberPseudoToId[memberPseudoInput.value];
        if (id) {
            memberIdInput.value = id;
        } else if (memberPseudoInput.value === '') {
            memberIdInput.value = '';
        }
    });
</script>
