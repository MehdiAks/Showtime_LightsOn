<?php

class ArticleController
{
    /**
     * Dossier cible utilisé pour stocker les images d'articles.
     */
    private const ARTICLE_UPLOAD_FOLDER = 'photos-boutiques';

    /**
     * Vérifie que le dossier d'upload existe avant d'enregistrer des fichiers.
     * Crée l'arborescence avec les permissions adaptées si besoin.
     */
    private function ensureUploadDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    /**
     * Construit le chemin de stockage relatif de l'image d'un article.
     * Ce chemin est stocké en base et concaténé au dossier d'uploads.
     */
    private function buildArticleImagePath(int $numArt, string $extension): string
    {
        return self::ARTICLE_UPLOAD_FOLDER . '/article-' . $numArt . '.' . $extension;
    }

    /**
     * Normalise un chemin d'upload provenant de la base ou du système.
     * Retourne un chemin relatif à /src/uploads/ ou null si vide.
     */
    private function normalizeUploadPath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (strpos($path, '/src/uploads/') !== false) {
            $relative = substr($path, strpos($path, '/src/uploads/') + strlen('/src/uploads/'));
            return ltrim($relative, '/');
        }

        return ltrim($path, '/');
    }

    /**
     * Rend une vue back-end avec l'habillage (header/footer).
     * Les données sont extraites en variables pour la vue.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require_once __DIR__ . '/../config.php';
        include __DIR__ . '/../header.php';
        include __DIR__ . '/../' . $view;
        include __DIR__ . '/../footer.php';
    }

    public function list(): void
    {
        require_once __DIR__ . '/../config.php';

        // Récupère articles, mots-clés, relations et thématiques pour lister.
        $ba_bec_articles = sql_select('ARTICLE', '*');
        $ba_bec_keywords = sql_select('MOTCLE', '*');
        $ba_bec_keywordsart = sql_select('MOTCLEARTICLE', '*');
        $ba_bec_thematiques = sql_select('THEMATIQUE', '*');

        $this->render('views/backend/articles/list.php', [
            'ba_bec_articles' => $ba_bec_articles,
            'ba_bec_keywords' => $ba_bec_keywords,
            'ba_bec_keywordsart' => $ba_bec_keywordsart,
            'ba_bec_thematiques' => $ba_bec_thematiques,
        ]);
    }

    public function create(): void
    {
        require_once __DIR__ . '/../config.php';

        // Charge les styles de l'éditeur pour la création d'article.
        $pageStyles = [
            ROOT_URL . '/src/css/stylearticle.css',
            ROOT_URL . '/src/css/article-editor.css',
        ];

        // Charge les données du formulaire (thématiques et mots-clés).
        $ba_bec_thematiques = sql_select('THEMATIQUE', '*');
        $ba_bec_keywords = sql_select('MOTCLE', '*');

        $this->render('views/backend/articles/create.php', [
            'pageStyles' => $pageStyles,
            'ba_bec_thematiques' => $ba_bec_thematiques,
            'ba_bec_keywords' => $ba_bec_keywords,
        ]);
    }

    public function store(): void
    {
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../functions/ctrlSaisies.php';

        // Active un affichage d'erreurs détaillé pendant la création.
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $ba_bec_nom_image = null;
        $ba_bec_imagePayload = null;

        // Nettoie chaque champ issu du POST.
        $ba_bec_libTitrArt = ctrlSaisies($_POST['libTitrArt'] ?? '');
        $ba_bec_libChapoArt = ctrlSaisies($_POST['libChapoArt'] ?? '');
        $ba_bec_libAccrochArt = ctrlSaisies($_POST['libAccrochArt'] ?? '');
        $ba_bec_parag1Art = ctrlSaisies($_POST['parag1Art'] ?? '');
        $ba_bec_libSsTitr1Art = ctrlSaisies($_POST['libSsTitr1Art'] ?? '');
        $ba_bec_parag2Art = ctrlSaisies($_POST['parag2Art'] ?? '');
        $ba_bec_libSsTitr2Art = ctrlSaisies($_POST['libSsTitr2Art'] ?? '');
        $ba_bec_parag3Art = ctrlSaisies($_POST['parag3Art'] ?? '');
        $ba_bec_libConclArt = ctrlSaisies($_POST['libConclArt'] ?? '');
        $ba_bec_numThem = ctrlSaisies($_POST['numThem'] ?? '');

        // Limite l'accroche à 100 caractères avec support multibyte.
        if (function_exists('mb_substr')) {
            $ba_bec_libAccrochArt = mb_substr($ba_bec_libAccrochArt, 0, 100);
        } else {
            $ba_bec_libAccrochArt = substr($ba_bec_libAccrochArt, 0, 100);
        }

        // Récupère les mots-clés sélectionnés pour la table de relation.
        $ba_bec_numMotCle = isset($_POST['motCle']) ? (array) $_POST['motCle'] : [];

        // Valide l'image uploadée (si présente) et prépare les infos de stockage.
        if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] === 0) {
            $ba_bec_tmpName = $_FILES['urlPhotArt']['tmp_name'];
            $ba_bec_name = $_FILES['urlPhotArt']['name'];
            $ba_bec_size = $_FILES['urlPhotArt']['size'];
            $ba_bec_allowedExtensions = ['jpg', 'jpeg', 'png', 'avif', 'svg'];
            $ba_bec_allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/avif',
                'image/svg+xml',
                'image/svg',
                'text/xml',
                'application/xml',
            ];

            if ($ba_bec_size > 10000000) {
                die('Le fichier est trop volumineux.');
            }

            // Vérifie l'extension via une liste autorisée.
            $ba_bec_extension = strtolower(pathinfo($ba_bec_name, PATHINFO_EXTENSION));
            if (!in_array($ba_bec_extension, $ba_bec_allowedExtensions, true)) {
                die("Format d'image non autorisé.");
            }

            // Vérifie le type MIME avec finfo pour plus de sécurité.
            $ba_bec_mimeType = null;
            if (function_exists('finfo_open')) {
                $ba_bec_finfo = finfo_open(FILEINFO_MIME_TYPE);
                if ($ba_bec_finfo) {
                    $ba_bec_mimeType = finfo_file($ba_bec_finfo, $ba_bec_tmpName);
                    finfo_close($ba_bec_finfo);
                }
            }

            if ($ba_bec_mimeType && !in_array($ba_bec_mimeType, $ba_bec_allowedMimeTypes, true)) {
                die("Format d'image non autorisé.");
            }

            // Pour les formats raster, vérifie que c'est une image et ses dimensions.
            if (!in_array($ba_bec_extension, ['svg', 'avif'], true)) {
                $ba_bec_dimensions = getimagesize($ba_bec_tmpName);
                if ($ba_bec_dimensions === false) {
                    die("Le fichier n'est pas une image valide.");
                }
                [$ba_bec_width, $ba_bec_height] = $ba_bec_dimensions;
                if ($ba_bec_width > 5000 || $ba_bec_height > 5000) {
                    die("L'image est trop grande.");
                }
            }

            // Stocke les infos temporaires pour déplacer le fichier après insertion.
            $ba_bec_imagePayload = [
                'tmpName' => $ba_bec_tmpName,
                'extension' => $ba_bec_extension,
            ];
        }

        // Vérifie que la thématique est valide avant insertion.
        if ($ba_bec_numThem === '' || !is_numeric($ba_bec_numThem)) {
            http_response_code(400);
            echo 'Veuillez sélectionner une thématique valide.';
            exit;
        }

        // Insère l'article avec un chemin d'image provisoire.
        sql_insert(
            'ARTICLE',
            'libTitrArt, libChapoArt, libAccrochArt, parag1Art, libSsTitr1Art, parag2Art, libSsTitr2Art, parag3Art, libConclArt, urlPhotArt, numThem',
            "'$ba_bec_libTitrArt', '$ba_bec_libChapoArt', '$ba_bec_libAccrochArt', '$ba_bec_parag1Art', '$ba_bec_libSsTitr1Art', '$ba_bec_parag2Art', '$ba_bec_libSsTitr2Art', '$ba_bec_parag3Art', '$ba_bec_libConclArt', NULL, '$ba_bec_numThem'"
        );
        // Récupère l'id inséré pour nommer l'image et les relations.
        $ba_bec_lastArt = sql_select('ARTICLE', 'numArt', null, null, 'numArt DESC', '1')[0]['numArt'];

        if ($ba_bec_imagePayload) {
            // Crée le dossier d'uploads et déplace l'image à son emplacement final.
            $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . self::ARTICLE_UPLOAD_FOLDER . '/';
            $this->ensureUploadDirectory($ba_bec_uploadDir);
            $ba_bec_nom_image = $this->buildArticleImagePath((int) $ba_bec_lastArt, $ba_bec_imagePayload['extension']);
            $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;

            if (!move_uploaded_file($ba_bec_imagePayload['tmpName'], $ba_bec_destination)) {
                die("Erreur lors de l'upload de l'image.");
            }

            sql_update('ARTICLE', "urlPhotArt = '$ba_bec_nom_image'", "numArt = '$ba_bec_lastArt'");
        }

        // Insère les relations mot-clé pour le nouvel article.
        foreach ($ba_bec_numMotCle as $ba_bec_mot) {
            sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', "$ba_bec_lastArt, $ba_bec_mot");
        }

        // Redirige vers la liste après création.
        header('Location: ' . ROOT_URL . '/public/index.php?controller=article&action=list');
        exit;
    }

    public function edit(): void
    {
        require_once __DIR__ . '/../config.php';

        // Charge les styles et données nécessaires pour l'édition.
        $pageStyles = [
            ROOT_URL . '/src/css/stylearticle.css',
            ROOT_URL . '/src/css/article-editor.css',
        ];

        $ba_bec_numArt = isset($_GET['numArt']) ? (int) $_GET['numArt'] : 0;
        // Récupère l'article et ses données associées si l'id est valide.
        $ba_bec_article = $ba_bec_numArt ? (sql_select('ARTICLE', '*', "numArt = $ba_bec_numArt")[0] ?? []) : [];
        $ba_bec_thematiques = sql_select('THEMATIQUE', '*');
        $ba_bec_keywords = sql_select('MOTCLE', '*');
        $ba_bec_selectedKeywords = $ba_bec_numArt ? sql_select('MOTCLEARTICLE', '*', "numArt = $ba_bec_numArt") : [];
        $ba_bec_urlPhotArt = $ba_bec_article['urlPhotArt'] ?? '';

        $this->render('views/backend/articles/edit.php', [
            'pageStyles' => $pageStyles,
            'ba_bec_numArt' => $ba_bec_numArt,
            'ba_bec_article' => $ba_bec_article,
            'ba_bec_thematiques' => $ba_bec_thematiques,
            'ba_bec_keywords' => $ba_bec_keywords,
            'ba_bec_selectedKeywords' => $ba_bec_selectedKeywords,
            'ba_bec_urlPhotArt' => $ba_bec_urlPhotArt,
        ]);
    }

    public function update(): void
    {
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../functions/ctrlSaisies.php';

        // Active un affichage d'erreurs détaillé pendant la mise à jour.
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        // Définit la date de MAJ et nettoie tous les champs.
        $ba_bec_dtMajArt = date('Y-m-d H:i:s');
        $ba_bec_libTitrArt = ctrlSaisies($_POST['libTitrArt'] ?? '');
        $ba_bec_libChapoArt = ctrlSaisies($_POST['libChapoArt'] ?? '');
        $ba_bec_libAccrochArt = ctrlSaisies($_POST['libAccrochArt'] ?? '');
        $ba_bec_parag1Art = ctrlSaisies($_POST['parag1Art'] ?? '');
        $ba_bec_libSsTitr1Art = ctrlSaisies($_POST['libSsTitr1Art'] ?? '');
        $ba_bec_parag2Art = ctrlSaisies($_POST['parag2Art'] ?? '');
        $ba_bec_libSsTitr2Art = ctrlSaisies($_POST['libSsTitr2Art'] ?? '');
        $ba_bec_parag3Art = ctrlSaisies($_POST['parag3Art'] ?? '');
        $ba_bec_libConclArt = ctrlSaisies($_POST['libConclArt'] ?? '');
        $ba_bec_numThem = ctrlSaisies($_POST['numThem'] ?? '');
        $ba_bec_numArt = ctrlSaisies($_POST['numArt'] ?? '');
        $ba_bec_numMotCle = isset($_POST['motCle']) ? (array) $_POST['motCle'] : [];

        // Limite l'accroche avec support multibyte.
        if (function_exists('mb_substr')) {
            $ba_bec_libAccrochArt = mb_substr($ba_bec_libAccrochArt, 0, 100);
        } else {
            $ba_bec_libAccrochArt = substr($ba_bec_libAccrochArt, 0, 100);
        }

        // Charge le chemin d'image actuel pour le conserver ou le remplacer.
        $ba_bec_article = sql_select('ARTICLE', 'urlPhotArt', "numArt = '$ba_bec_numArt'")[0] ?? [];
        $ba_bec_ancienneImage = $this->normalizeUploadPath($ba_bec_article['urlPhotArt'] ?? null);

        // Gère le remplacement optionnel de l'image avec validations.
        if (isset($_FILES['urlPhotArt']) && $_FILES['urlPhotArt']['error'] === 0) {
            $ba_bec_tmpName = $_FILES['urlPhotArt']['tmp_name'];
            $ba_bec_name = $_FILES['urlPhotArt']['name'];
            $ba_bec_size = $_FILES['urlPhotArt']['size'];
            $ba_bec_allowedExtensions = ['jpg', 'jpeg', 'png', 'avif', 'svg'];
            $ba_bec_allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/avif',
                'image/svg+xml',
                'image/svg',
                'text/xml',
                'application/xml',
            ];

            if ($ba_bec_size > 10000000) {
                die('Le fichier est trop volumineux.');
            }

            // Vérifie l'extension du fichier.
            $ba_bec_extension = strtolower(pathinfo($ba_bec_name, PATHINFO_EXTENSION));
            if (!in_array($ba_bec_extension, $ba_bec_allowedExtensions, true)) {
                die("Format d'image non autorisé.");
            }

            // Vérifie le type MIME pour renforcer la sécurité.
            $ba_bec_mimeType = null;
            if (function_exists('finfo_open')) {
                $ba_bec_finfo = finfo_open(FILEINFO_MIME_TYPE);
                if ($ba_bec_finfo) {
                    $ba_bec_mimeType = finfo_file($ba_bec_finfo, $ba_bec_tmpName);
                    finfo_close($ba_bec_finfo);
                }
            }

            if ($ba_bec_mimeType && !in_array($ba_bec_mimeType, $ba_bec_allowedMimeTypes, true)) {
                die("Format d'image non autorisé.");
            }

            // Vérifie les dimensions des images raster.
            if (!in_array($ba_bec_extension, ['svg', 'avif'], true)) {
                $ba_bec_dimensions = getimagesize($ba_bec_tmpName);
                if ($ba_bec_dimensions === false) {
                    die("Le fichier n'est pas une image valide.");
                }
                [$ba_bec_width, $ba_bec_height] = $ba_bec_dimensions;
                if ($ba_bec_width > 5000 || $ba_bec_height > 5000) {
                    die("L'image est trop grande.");
                }
            }

            // Déplace la nouvelle image et nettoie l'ancienne si besoin.
            $ba_bec_nom_image = $this->buildArticleImagePath((int) $ba_bec_numArt, $ba_bec_extension);
            $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . self::ARTICLE_UPLOAD_FOLDER . '/';
            $this->ensureUploadDirectory($ba_bec_uploadDir);
            $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;

            if (!move_uploaded_file($ba_bec_tmpName, $ba_bec_destination)) {
                die("Erreur lors de l'upload de l'image.");
            }

            if ($ba_bec_ancienneImage) {
                $ba_bec_oldPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_ancienneImage;
                if (file_exists($ba_bec_oldPath)) {
                    unlink($ba_bec_oldPath);
                }
            }
        } else {
            // Conserve l'image existante. Si chemin legacy, on migre.
            $ba_bec_nom_image = $ba_bec_ancienneImage;
            if ($ba_bec_nom_image && strpos($ba_bec_nom_image, self::ARTICLE_UPLOAD_FOLDER . '/') !== 0) {
                $ba_bec_legacyPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;
                if (file_exists($ba_bec_legacyPath)) {
                    $ba_bec_extension = strtolower(pathinfo($ba_bec_nom_image, PATHINFO_EXTENSION));
                    $ba_bec_nom_image = $this->buildArticleImagePath((int) $ba_bec_numArt, $ba_bec_extension);
                    $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . self::ARTICLE_UPLOAD_FOLDER . '/';
                    $this->ensureUploadDirectory($ba_bec_uploadDir);
                    $ba_bec_destination = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_nom_image;
                    if (!rename($ba_bec_legacyPath, $ba_bec_destination)) {
                        $ba_bec_nom_image = $ba_bec_ancienneImage;
                    }
                }
            }
        }

        // Construit la clause SET pour la mise à jour.
        $ba_bec_set_art = "dtMajArt = '$ba_bec_dtMajArt',
libTitrArt = '$ba_bec_libTitrArt',
libChapoArt = '$ba_bec_libChapoArt',
libAccrochArt = '$ba_bec_libAccrochArt',
parag1Art = '$ba_bec_parag1Art',
libSsTitr1Art = '$ba_bec_libSsTitr1Art',
parag2Art = '$ba_bec_parag2Art',
libSsTitr2Art = '$ba_bec_libSsTitr2Art',
parag3Art = '$ba_bec_parag3Art',
libConclArt = '$ba_bec_libConclArt',
urlPhotArt = '$ba_bec_nom_image',
numThem = '$ba_bec_numThem'";

        $ba_bec_where_num = "numArt = '$ba_bec_numArt'";

        // Met à jour l'article et rafraîchit les relations mot-clé.
        sql_update('ARTICLE', $ba_bec_set_art, $ba_bec_where_num);

        sql_delete('MOTCLEARTICLE', $ba_bec_where_num);
        foreach ($ba_bec_numMotCle as $ba_bec_mot) {
            sql_insert('MOTCLEARTICLE', 'numArt, numMotCle', "$ba_bec_numArt, $ba_bec_mot");
        }

        // Redirige vers la liste après mise à jour.
        header('Location: ' . ROOT_URL . '/public/index.php?controller=article&action=list');
        exit;
    }

    public function delete(): void
    {
        require_once __DIR__ . '/../config.php';

        $ba_bec_numArt = isset($_GET['numArt']) ? (int) $_GET['numArt'] : 0;
        // Charge l'article, sa thématique et ses mots-clés pour la confirmation.
        $ba_bec_article = $ba_bec_numArt ? (sql_select('ARTICLE', '*', "numArt = $ba_bec_numArt")[0] ?? []) : [];

        $ba_bec_thematique = [];
        if (!empty($ba_bec_article['numThem'])) {
            $ba_bec_thematique = sql_select('THEMATIQUE', '*', 'numThem = ' . $ba_bec_article['numThem'])[0] ?? [];
        }

        // Transforme les ids de mots-clés en libellés pour l'affichage.
        $ba_bec_keywords = $ba_bec_numArt ? sql_select('MOTCLEARTICLE', '*', "numArt = $ba_bec_numArt") : [];
        $ba_bec_keywordsList = [];
        foreach ($ba_bec_keywords as $ba_bec_keyword) {
            $ba_bec_keywordInfo = sql_select('MOTCLE', '*', 'numMotCle = ' . $ba_bec_keyword['numMotCle'])[0] ?? [];
            if (!empty($ba_bec_keywordInfo['libMotCle'])) {
                $ba_bec_keywordsList[] = $ba_bec_keywordInfo['libMotCle'];
            }
        }

        $this->render('views/backend/articles/delete.php', [
            'ba_bec_article' => $ba_bec_article,
            'ba_bec_thematique' => $ba_bec_thematique,
            'ba_bec_keywordsList' => $ba_bec_keywordsList,
        ]);
    }

    public function destroy(): void
    {
        require_once __DIR__ . '/../config.php';
        require_once __DIR__ . '/../functions/ctrlSaisies.php';

        // Nettoie l'id d'article et charge le chemin d'image pour nettoyage.
        $ba_bec_numArt = ctrlSaisies($_POST['numArt'] ?? '');

        $ba_bec_article = sql_select('ARTICLE', 'urlPhotArt', "numArt = '$ba_bec_numArt'")[0] ?? [];
        $ba_bec_ancienneImage = $this->normalizeUploadPath($ba_bec_article['urlPhotArt'] ?? '');

        $ba_bec_uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/';

        // Supprime le fichier image associé s'il existe.
        if ($ba_bec_ancienneImage) {
            $ba_bec_oldPath = $_SERVER['DOCUMENT_ROOT'] . '/src/uploads/' . $ba_bec_ancienneImage;
            if (file_exists($ba_bec_oldPath)) {
                unlink($ba_bec_oldPath);
            }
        }

        // Supprime les relations mot-clé puis l'article.
        sql_delete('MOTCLEARTICLE', "numArt = '$ba_bec_numArt'");
        sql_delete('ARTICLE', "numArt = '$ba_bec_numArt'");

        // Redirige vers la liste après suppression.
        header('Location: ' . ROOT_URL . '/public/index.php?controller=article&action=list');
        exit;
    }
}
