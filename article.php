<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/ctrlSaisies.php';

function resolve_article_image_url(?string $path, string $defaultImage): string
{
    if (!$path) {
        return $defaultImage;
    }

    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }

    if (strpos($path, '/src/uploads/') !== false) {
        $relative = substr($path, strpos($path, '/src/uploads/') + strlen('/src/uploads/'));
    } else {
        $relative = ltrim($path, '/');
    }

    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $relative;
    if (file_exists($filePath)) {
        return ROOT_URL . '/src/uploads/' . $relative;
    }

    return $defaultImage;
}

// Vérification de la présence de numArt
if (!isset($_GET['numArt']) || empty($_GET['numArt'])) {
    die("Aucun article sélectionné.");
}

$ba_bec_numArt = (int)$_GET['numArt'];
$articleData = sql_select("ARTICLE", "*", "numArt = $ba_bec_numArt");

// Vérification si l'article existe
if (empty($articleData)) {
    die("Article non trouvé.");
}

$ba_bec_article = $articleData[0];
$defaultImagePath = ROOT_URL . '/src/images/image-defaut.jpeg';
$ba_bec_articleImageUrl = resolve_article_image_url($ba_bec_article['urlPhotArt'] ?? null, $defaultImagePath);
$ba_bec_thematiques = sql_select("THEMATIQUE", "*");
$ba_bec_keywords = sql_select("MOTCLE", "*");
$ba_bec_selectedKeywords = sql_select("MOTCLEARTICLE", "*", "numArt = $ba_bec_numArt");

// Liste des mots-clés liés à l'article
$ba_bec_listMot = sql_select(
    'ARTICLE
    INNER JOIN MOTCLEARTICLE ON ARTICLE.numArt = MOTCLEARTICLE.numArt
    INNER JOIN MOTCLE ON MOTCLEARTICLE.numMotCle = MOTCLE.numMotCle',
    'ARTICLE.numArt, libMotCle',
    "ARTICLE.numArt = '$ba_bec_numArt'"
);

$ba_bec_article = $articleData[0];
$ba_bec_thematique = [];
if (!empty($ba_bec_article['numThem'])) {
    $ba_bec_thematique = sql_select('THEMATIQUE', '*', 'numThem = ' . $ba_bec_article['numThem'])[0] ?? [];
}

// Récupération des statistiques likes/dislikes
$likeCount = sql_select("LIKEART", "COUNT(*) as count", "numArt = $ba_bec_numArt AND likeA = 1")[0]['count'] ?? 0;
$dislikeCount = sql_select("LIKEART", "COUNT(*) as count", "numArt = $ba_bec_numArt AND likeA = 0")[0]['count'] ?? 0;

// Vérification du vote de l'utilisateur
$userVote = null;
$ba_bec_libCom = isset($_POST['libCom']) ? ctrlSaisies($_POST['libCom']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Requête invalide.');
    }

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Vous devez être connecté pour ajouter un commentaire ou un like.";
        header("Location: " . ROOT_URL . "/views/backend/security/login.php");
        exit();
    }

    // Vérifie si c'est un like/dislike ou un commentaire
    if (isset($_POST['libCom'])) {
        // Récupérer l'ID du membre connecté et les autres données
        $ba_bec_numMemb = $_SESSION['user_id'];
        $ba_bec_libCom = ctrlSaisies($_POST['libCom']);
        $ba_bec_numArt = (int)$_POST['numArt'];
        if (!empty($ba_bec_libCom) && !empty($ba_bec_numArt) && !empty($ba_bec_numMemb)) {
            sql_insert('comment', 'libCom, numArt, numMemb', "'$ba_bec_libCom', '$ba_bec_numArt', '$ba_bec_numMemb'");
            echo "<p style='color: green;'>Commentaire ajouté avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Erreur : tous les champs doivent être remplis correctement.</p>";
        }
    } else {

    
        // Le reste du code existant pour le traitement du like...
        $ba_bec_numMemb = $_SESSION['user_id'];
        $ba_bec_likeA = (int)$_POST['likeA'];

        // Vérifier si l'utilisateur a déjà voté
        $existingVote = sql_select("LIKEART", "*", "numArt = $ba_bec_numArt AND numMemb = $ba_bec_numMemb");

        if (!empty($existingVote)) {
            // Mettre à jour le vote
            sql_update("LIKEART", "likeA = $ba_bec_likeA", "numArt = $ba_bec_numArt AND numMemb = $ba_bec_numMemb");
        } else {
            // Insérer un nouveau vote
            sql_insert("LIKEART", "numArt, numMemb, likeA", "'$ba_bec_numArt', '$ba_bec_numMemb', '$ba_bec_likeA'");
        }

        // Recharger la page pour mettre à jour le nombre de likes/dislikes
        header("Location: article.php?numArt=$ba_bec_numArt");
        exit();
    }
}

// Récupérer l'article actuel avec ses commentaires
$ba_bec_numArt = (int) $_GET['numArt']; // Assure-toi d'avoir l'ID de l'article dans l'URL
$comments = sql_select(
    "comment c 
    INNER JOIN membre m ON c.numMemb = m.numMemb 
    WHERE c.numArt = $ba_bec_numArt 
    AND c.delLogiq = 0
    AND c.attModOK = 1",
    "c.libCom, c.dtCreaCom, m.pseudoMemb"
);
// Afficher l'article et ses commentaires
$ba_bec_article = sql_select("article", "*", "numArt = $ba_bec_numArt")[0];





// Vérification du vote de l'utilisateur
$userVote = null;
if (isset($_SESSION['user_id'])) {
    $ba_bec_numMemb = $_SESSION['user_id'];
    $userVoteData = sql_select("LIKEART", "likeA", "numArt = $ba_bec_numArt AND numMemb = $ba_bec_numMemb");
    $userVote = !empty($userVoteData) ? $userVoteData[0]['likeA'] : null;
}

$pageStyles = [
    ROOT_URL . '/src/css/css-propre/fonts.css',
    ROOT_URL . '/src/css/stylearticle.css',
];
require_once $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>
    <div class="article-page">
        <header class="article-hero" style="--hero-image: url('<?php echo $ba_bec_articleImageUrl; ?>')">
            <div class="article-hero__overlay">
                <p class="article-kicker">Actualités</p>
                <h1 class="article-title">
                    <?php echo renderBbcode($ba_bec_article['libTitrArt']); ?>
                </h1>
                <div class="article-meta">
                    <span><?php echo ($ba_bec_article['dtCreaArt']); ?></span>
                    <span class="article-meta__dot">•</span>
                    <span>Lecture 2 min</span>
                </div>
            </div>
        </header>

        <section class="article-body">
            <div class="container">
                <div class="article-lead">
                    <?php echo renderBbcode($ba_bec_article['libChapoArt']); ?> 
                </div>
                <div class="article-taxonomy mb-4">
                    <p class="article-theme mb-1">
                        <strong>Thématique :</strong>
                        <?php echo !empty($ba_bec_thematique['libThem']) ? htmlspecialchars($ba_bec_thematique['libThem']) : 'Non renseignée'; ?>
                    </p>
                    <p class="article-keywords mb-0">
                        <strong>Mots-clés :</strong>
                        <?php if (!empty($ba_bec_listMot)): ?>
                            <?php
                            $ba_bec_keywordLabels = array_map(
                                static fn($ba_bec_mot) => htmlspecialchars($ba_bec_mot['libMotCle']),
                                $ba_bec_listMot
                            );
                            echo implode(', ', $ba_bec_keywordLabels);
                            ?>
                        <?php else: ?>
                            Aucun mot-clé.
                        <?php endif; ?>
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-lg-8">
                        <article class="bg-white">
                        <h2 class="phraseaccroche">
                            <?php echo renderBbcode($ba_bec_article['libAccrochArt']); ?> 
                        </h2>
                        <p class="paragraphe">
                            <?php echo renderBbcode($ba_bec_article['parag1Art']); ?> 
                        </p>
                        <figure class="article-figure">
                            <img class="image2 img-fluid w-100" src="<?php echo $ba_bec_articleImageUrl; ?>" alt="Image article">
                            <figcaption class="article-caption">
                                © Groupe 1 Bordeaux étudiant club + Description de l’image
                            </figcaption>
                        </figure>

                        <div class="text-with-line">
                            <?php echo renderBbcode($ba_bec_article['libSsTitr1Art']); ?> 
                        </div>

                        <p class="paragraphe2">
                            <?php echo renderBbcode($ba_bec_article['parag2Art']); ?>
                        </p>

                        <div class="text-with-line">
                            <?php echo renderBbcode($ba_bec_article['libSsTitr2Art']); ?>
                        </div>

                        <p class="paragraphe3">
                            <?php echo renderBbcode($ba_bec_article['parag3Art']); ?>
                        </p>

                        <p class="conclusion">
                            <?php echo renderBbcode($ba_bec_article['libConclArt']); ?>
                        </p>
                    </article>

                    <div class="likes-section">
                        <h2>Évaluer cet article</h2>
                        <p class="likes-count">Nombre de likes : <?php echo $likeCount; ?> · Dislikes : <?php echo $dislikeCount; ?></p>
                        <div class="vote-buttons d-flex gap-3">
                            <form action="article.php?numArt=<?php echo $ba_bec_numArt; ?>" method="post">
                                <input type="hidden" name="numArt" value="<?php echo $ba_bec_numArt; ?>">
                                <input type="hidden" name="likeA" value="1">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
                                <button type="submit" class="btn btn-light d-flex align-items-center gap-2 btn-vote <?php echo $userVote === 1 ? 'active-like' : ''; ?>">
                                    <img src="<?php echo ROOT_URL . '/src/images/icon/pnglike.png'; ?>" alt="Like">
                                    <span><?php echo $likeCount; ?></span>
                                </button>
                            </form>

                            <form action="article.php?numArt=<?php echo $ba_bec_numArt; ?>" method="post">
                                <input type="hidden" name="numArt" value="<?php echo $ba_bec_numArt; ?>">
                                <input type="hidden" name="likeA" value="0">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
                                <button type="submit" class="btn btn-light d-flex align-items-center gap-2 btn-vote <?php echo $userVote === 0 ? 'active-dislike' : ''; ?>">
                                    <img src="<?php echo ROOT_URL . '/src/images/icon/pngdislike.png'; ?>"  alt="Dislike">
                                    <span><?php echo $dislikeCount; ?></span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="comments-block">
                        <h2>Ajouter un commentaire</h2>
                        <form action="article.php?numArt=<?php echo $ba_bec_numArt; ?>" method="post" class="comment-form">
                            <div class="champ">
                                <textarea id="libCom" name="libCom" class="form-control" type="text" required></textarea>
                            </div>
                            <input type="hidden" name="numArt" value="<?php echo $ba_bec_numArt; ?>" />
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>" />
                            <div class="btn-se-connecter">
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </div>  
                        </form>
                    </div>

                    <div class="comments-block">
                        <h2>Commentaires</h2>
                        <?php if (!empty($comments)): ?>
                            <ul class="comments-list">
                                <?php foreach ($comments as $ba_bec_comment): ?>
                                    <li class="commentairesaf">
                                        <div class="comment-meta">
                                            <span class="username"><?php echo htmlspecialchars($ba_bec_comment['pseudoMemb']); ?></span> 
                                            <span class="date"><?php echo htmlspecialchars($ba_bec_comment['dtCreaCom']); ?></span>
                                        </div>
                                        <p class="commentaire"><?php echo renderBbcode($ba_bec_comment['libCom']); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Il n'y a pas encore de commentaires pour cet article.</p>
                        <?php endif; ?>
                    </div>
                    </div>

                    <aside class="article-sidebar col-12 col-lg-4">
                        <h2>Autres articles</h2>
                        <?php
                        $randomArticles = sql_select("ARTICLE", "*", "1=1 ORDER BY RAND() LIMIT 3");

                        if (!empty($randomArticles)):
                            foreach ($randomArticles as $randomArticle): ?>
                                <?php
                                $randomImageUrl = resolve_article_image_url(
                                    $randomArticle['urlPhotArt'] ?? null,
                                    $defaultImagePath
                                );
                                ?>
                                <div class="random-article">
                                    <img class="imagedroite img-fluid w-100" src="<?php echo $randomImageUrl; ?>" alt="Image article">
                                    <h3 class="titredroite">
                                        <?php echo renderBbcode($randomArticle['libTitrArt']); ?>
                                    </h3>
                                    <p class="txtdroite">
                                        <?php echo renderBbcode($randomArticle['libChapoArt']); ?>
                                    </p>
                                    <a href="article.php?numArt=<?php echo $randomArticle['numArt']; ?>" class="btn btn-outline-primary btn-sm">Lire l'article →</a>
                                </div>
                            <?php endforeach;
                        else: ?>
                            <p>Aucun article disponible.</p>
                        <?php endif; ?>
                    </aside>
                </div>
            </div>
        </section>
    </div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
