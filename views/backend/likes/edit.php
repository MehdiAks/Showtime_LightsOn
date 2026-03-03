<?php
/*
 * Vue d'administration (édition) pour le module likes.
 * - Le formulaire réutilise la structure de création mais avec des valeurs pré-remplies côté serveur.
 * - Les identifiants nécessaires (ID) sont passés via la query string ou des champs cachés.
 * - L'action du formulaire cible la route de mise à jour correspondante.
 * - Les sections HTML isolent les groupes d'attributs pour une édition guidée.
 * - Les actions secondaires permettent de revenir à la liste sans enregistrer.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/redirec.php';
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

$ba_bec_selectedArticleTitle = '';
$ba_bec_selectedMemberPseudo = '';

if (isset($_GET['numMemb']) && isset($_GET['numArt'])) {
    $ba_bec_numMemb = $_GET['numMemb'];
    $ba_bec_numArt = $_GET['numArt'];
    $ba_bec_likeA = sql_select("LIKEART", "likeA", "numMemb = $ba_bec_numMemb AND numArt = $ba_bec_numArt")[0]['likeA'];
    $ba_bec_selectedArticleTitle = $ba_bec_articleIdToTitle[$ba_bec_numArt] ?? '';
    $ba_bec_selectedMemberPseudo = $ba_bec_memberIdToPseudo[$ba_bec_numMemb] ?? '';
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Modification Like</h1>
        </div>
        <div class="col-md-12">
            <!-- Form to edit like -->
            <form action="<?php echo ROOT_URL . '/api/likes/update.php' ?>" method="post">
                <div class="form-group">
                    <label for="numArt">Article (ID)</label>
                    <input id="numArt" name="numArt" class="form-control" type="text"
                        value="<?php echo $ba_bec_numArt; ?>" />
                    <input id="numArt" name="numArt" class="form-control" type="text" value="<?php echo $ba_bec_numArt; ?>"
                        placeholder="ID article (ex: 42)" />
                </div>
                <br>

                <div class="form-group">
                    <label for="numMemb">Utilisateur (ID)</label>
                    <input id="numMemb" name="numMemb" class="form-control" type="text"
                        value="<?php echo $ba_bec_numMemb; ?>" placeholder="ID utilisateur (ex: 7)" />
                </div>
                <br>

                <div class="form-group">
                    <label for="likeA">Like / Dislike</label>
                    <div class="form-check form-switch">
                        <input type="hidden" name="likeA" value="0" />
                        <input class="form-check-input" type="checkbox" id="likeA" name="likeA" value="1"
                            <?php echo ($ba_bec_likeA == 1 ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="likeA">Like (désactiver pour dislike)</label>
                    </div>
                </div>
                <br>

                <div class="form-group mt-2">
                    <a href="list.php" class="btn btn-primary">Retour à la liste</a>
                    <button type="submit" class="btn btn-danger">Confirmer la modification ?</button>
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
