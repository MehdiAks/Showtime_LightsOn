<?php
/*
 * Vue d'administration (suppression) pour le module articles.
 * - Cette page sert de confirmation avant la suppression définitive d'un enregistrement.
 * - L'ID ciblé est transmis par la query string afin de récupérer les détails à afficher.
 * - Le bouton principal déclenche la route de suppression côté backend.
 * - Un lien de retour évite la suppression et renvoie vers la liste.
 * - Aucun traitement métier n'est exécuté ici : la vue décrit seulement l'interface.
 */
$ba_bec_articlePhoto = $ba_bec_article['urlPhotArt'] ?? '';
$ba_bec_defaultImage = ROOT_URL . '/src/images/article.png';
if (!empty($ba_bec_articlePhoto)) {
    $ba_bec_photoUrl = preg_match('/^(https?:\/\/|\/)/', $ba_bec_articlePhoto)
        ? $ba_bec_articlePhoto
        : ROOT_URL . '/src/uploads/' . $ba_bec_articlePhoto;
} else {
    $ba_bec_photoUrl = $ba_bec_defaultImage;
}
?>

<!-- Affichage des informations de l'article -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Suppression de l'article</h1>
            <p>Êtes-vous sûr de vouloir supprimer cet article ?</p>
        </div>
        <div class="col-md-12">
            <form action="<?php echo ROOT_URL . '/public/index.php?controller=article&action=destroy'; ?>" method="post">
                <!-- Champ caché pour l'ID de l'article -->
                <input type="hidden" name="numArt" value="<?php echo $ba_bec_article['numArt']; ?>">
                <?php
                $ba_bec_urlPhotArt = $ba_bec_article['urlPhotArt'] ?? '';
                $ba_bec_photoUrl = '';
                if (!empty($ba_bec_urlPhotArt)) {
                    $ba_bec_photoUrl = preg_match('/^(https?:\\/\\/|\\/)/', $ba_bec_urlPhotArt)
                        ? $ba_bec_urlPhotArt
                        : ROOT_URL . '/src/uploads/' . $ba_bec_urlPhotArt;
                }
                ?>

                <!-- Titre de l'article -->
                <div class="form-group">
                    <label for="libTitrArt">Titre</label>
                    <input id="libTitrArt" name="libTitrArt" class="form-control" type="text"
                        value="<?php echo $ba_bec_article['libTitrArt']; ?>" disabled>
                </div>

                <!-- Date de création -->
                <div class="form-group">
                    <label for="dtCreaArt">Date de création</label>
                    <input id="dtCreaArt" name="dtCreaArt" class="form-control" type="text"
                        value="<?php echo $ba_bec_article['dtCreaArt']; ?>" disabled>
                </div>

                <!-- Chapeau -->
                <div class="form-group">
                    <label for="libChapoArt">Chapeau</label>
                    <textarea id="libChapoArt" name="libChapoArt" class="form-control"
                        disabled><?php echo $ba_bec_article['libChapoArt']; ?></textarea>
                </div>

                <!-- Accroche -->
                <div class="form-group">
                    <label for="libAccrochArt">Accroche</label>
                    <input id="libAccrochArt" name="libAccrochArt" class="form-control" type="text"
                        value="<?php echo $ba_bec_article['libAccrochArt']; ?>" disabled>
                </div>

                <!-- Paragraphes -->
                <div class="form-group">
                    <label for="parag1Art">Paragraphe 1</label>
                    <textarea id="parag1Art" name="parag1Art" class="form-control"
                        disabled><?php echo $ba_bec_article['parag1Art']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="libSsTitr1Art">Sous-titre 1</label>
                    <input id="libSsTitr1Art" name="libSsTitr1Art" class="form-control" type="text"
                        value="<?php echo $ba_bec_article['libSsTitr1Art']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="parag2Art">Paragraphe 2</label>
                    <textarea id="parag2Art" name="parag2Art" class="form-control"
                        disabled><?php echo $ba_bec_article['parag2Art']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="libSsTitr2Art">Sous-titre 2</label>
                    <input id="libSsTitr2Art" name="libSsTitr2Art" class="form-control" type="text"
                        value="<?php echo $ba_bec_article['libSsTitr2Art']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="parag3Art">Paragraphe 3</label>
                    <textarea id="parag3Art" name="parag3Art" class="form-control"
                        disabled><?php echo $ba_bec_article['parag3Art']; ?></textarea>
                </div>

                <!-- Conclusion -->
                <div class="form-group">
                    <label for="libConclArt">Conclusion</label>
                    <textarea id="libConclArt" name="libConclArt" class="form-control"
                        disabled><?php echo $ba_bec_article['libConclArt']; ?></textarea>
                </div>

                <!-- Image -->
                <div class="form-group">
                    <label for="image">Image</label>
                    <img src="<?php echo htmlspecialchars($ba_bec_photoUrl); ?>"
                        alt="Image de l'article" style="max-width: 200px;">
                </div>

                <!-- Thématique -->
                <div class="form-group">
                    <label for="numThem">Thématique</label>
                    <select id="numThem" name="numThem" class="form-control" disabled>
                        <option value="<?php echo $ba_bec_thematique['numThem']; ?>"><?php echo $ba_bec_thematique['libThem']; ?>
                        </option>
                    </select>
                </div>

                <!-- Mots-clés -->
                <div class="form-group">
                    <label for="keywords">Mots-clés</label>
                    <input id="keywords" name="keywords" class="form-control" type="text"
                        value="<?php echo implode(', ', $ba_bec_keywordsList); ?>" disabled>
                </div>

                <!-- Boutons de confirmation -->
                <div class="form-group mt-2">
                    <a href="<?php echo ROOT_URL . '/public/index.php?controller=article&action=list'; ?>" class="btn btn-primary">Retour à la liste</a>
                    <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                </div>
            </form>
        </div>
    </div>
</div>
