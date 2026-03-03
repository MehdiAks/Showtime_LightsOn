<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/style.css',
    ROOT_URL . '/src/css/actualites.css',
];

require_once 'header.php';

sql_connect();

$episodeStmt = $DB->prepare('SELECT numArt, libTitrArt, libChapoArt, urlPhotArt, dtCreaArt FROM ARTICLE ORDER BY dtCreaArt DESC');
$episodeStmt->execute();
$episodes = $episodeStmt->fetchAll(PDO::FETCH_ASSOC);

function resolve_episode_image_url(?string $path, string $defaultImage): string
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
?>

<main class="container py-5">
    <section class="news-summary text-center mb-4">
        <h1 class="news-summary__title">Épisodes</h1>
        <p class="news-summary__lead">
            Retrouvez tous les épisodes de Lights On : invités, sujets, coulisses et moments marquants de la nuit.
        </p>
    </section>

    <section class="news-grid" aria-label="Bibliothèque des épisodes">
        <div class="row g-4">
            <?php if (!empty($episodes)): ?>
                <?php foreach ($episodes as $episode): ?>
                    <?php
                    $defaultImagePath = ROOT_URL . '/src/images/image-defaut.jpeg';
                    $imagePath = resolve_episode_image_url($episode['urlPhotArt'] ?? null, $defaultImagePath);
                    ?>
                    <div class="col-12 col-lg-6">
                        <article class="card news-card h-100">
                            <div class="ratio ratio-4x3 news-card__media">
                                <img src="<?php echo $imagePath; ?>" class="news-card__image" alt="<?php echo htmlspecialchars($episode['libTitrArt']); ?>">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="news-card__meta">
                                    <span class="badge text-bg-light">Diffusé le <?php echo htmlspecialchars(date('d/m/Y', strtotime((string) $episode['dtCreaArt']))); ?></span>
                                </div>
                                <h2 class="card-title news-card__title"><?php echo htmlspecialchars($episode['libTitrArt']); ?></h2>
                                <p class="card-text news-card__excerpt"><?php echo htmlspecialchars((string) ($episode['libChapoArt'] ?? '')); ?></p>
                                <a href="<?php echo ROOT_URL . '/article.php?numArt=' . (int) $episode['numArt']; ?>" class="btn btn-outline-primary mt-auto">Voir l'épisode</a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light border news-empty" role="status">
                        Aucun épisode n'est disponible pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
