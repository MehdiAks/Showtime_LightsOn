<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$pageStyles = [
    ROOT_URL . '/src/css/style.css',
    ROOT_URL . '/src/css/actualites.css',
];

require_once 'header.php';

sql_connect();

$search = trim($_GET['search'] ?? '');
$ba_bec_keyword = trim($_GET['keyword'] ?? '');
$theme = isset($_GET['theme']) ? (int) $_GET['theme'] : 0;
$sort = $_GET['sort'] ?? 'recent';
$isPartial = isset($_GET['partial']) && $_GET['partial'] === '1';

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

function format_news_count(int $count): string
{
    $suffix = $count > 1 ? 's' : '';
    return $count . ' actualité' . $suffix . ' trouvée' . $suffix;
}

$themeStmt = $DB->prepare('SELECT numThem, libThem FROM THEMATIQUE ORDER BY libThem ASC');
$themeStmt->execute();
$ba_bec_thematiques = $themeStmt->fetchAll(PDO::FETCH_ASSOC);

$keywordStmt = $DB->prepare('SELECT libMotCle FROM MOTCLE ORDER BY libMotCle ASC');
$keywordStmt->execute();
$ba_bec_keywords = array_values(array_filter(array_map('trim', $keywordStmt->fetchAll(PDO::FETCH_COLUMN)), 'strlen'));

$conditions = [];
$params = [];

if ($theme > 0) {
    $conditions[] = 'a.numThem = :theme';
    $params[':theme'] = $theme;
}

if ($search !== '') {
    $conditions[] = 'a.libTitrArt LIKE :search';
    $params[':search'] = '%' . $search . '%';
}

if ($ba_bec_keyword !== '') {
    $conditions[] = '(a.libChapoArt LIKE :keyword OR a.libAccrochArt LIKE :keyword OR a.parag1Art LIKE :keyword OR a.parag2Art LIKE :keyword OR a.parag3Art LIKE :keyword)';
    $params[':keyword'] = '%' . $ba_bec_keyword . '%';
}

$orderMap = [
    'recent' => 'a.dtCreaArt DESC',
    'oldest' => 'a.dtCreaArt ASC',
    'liked' => 'likeCount DESC, a.dtCreaArt DESC',
];
$orderBy = $orderMap[$sort] ?? $orderMap['recent'];

$query = 'SELECT a.numArt, a.libTitrArt, a.libChapoArt, a.urlPhotArt, t.libThem, COALESCE(l.likeCount, 0) as likeCount FROM ARTICLE a INNER JOIN THEMATIQUE t ON a.numThem = t.numThem LEFT JOIN (SELECT numArt, COUNT(*) as likeCount FROM LIKEART WHERE likeA = 1 GROUP BY numArt) l ON a.numArt = l.numArt';
if (!empty($conditions)) {
    $query .= ' WHERE ' . implode(' AND ', $conditions);
}
$query .= ' ORDER BY ' . $orderBy;

$articleStmt = $DB->prepare($query);
$articleStmt->execute($params);
$ba_bec_articles = $articleStmt->fetchAll(PDO::FETCH_ASSOC);

function render_news_grid(array $ba_bec_articles): string
{
    $articleCount = count($ba_bec_articles);
    $countLabel = format_news_count($articleCount);
    ob_start();
    ?>
    <section class="news-grid" aria-live="polite" data-news-count="<?php echo $articleCount; ?>" data-news-count-label="<?php echo htmlspecialchars($countLabel, ENT_QUOTES); ?>">
        <div class="row g-4">
            <?php if (!empty($ba_bec_articles)): ?>
                <?php foreach ($ba_bec_articles as $ba_bec_article): ?>
                    <?php
                    $defaultImagePath = ROOT_URL . '/src/images/image-defaut.jpeg';
                    $ba_bec_imagePath = resolve_article_image_url($ba_bec_article['urlPhotArt'] ?? null, $defaultImagePath);
                    $chapo = $ba_bec_article['libChapoArt'] ?? '';
                    $maxLength = 140;
                    $excerptBase = function_exists('mb_substr') ? mb_substr($chapo, 0, $maxLength) : substr($chapo, 0, $maxLength);
                    $chapoLength = function_exists('mb_strlen') ? mb_strlen($chapo) : strlen($chapo);
                    $excerpt = $excerptBase . ($chapoLength > $maxLength ? '...' : '');
                    ?>
                    <div class="col-12 col-lg-6">
                        <div class="card news-card h-100">
                            <div class="ratio ratio-4x3 news-card__media">
                                <img src="<?php echo $ba_bec_imagePath; ?>" class="news-card__image" alt="<?php echo htmlspecialchars($ba_bec_article['libTitrArt']); ?>">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="news-card__meta">
                                    <span class="badge text-bg-light"><?php echo htmlspecialchars($ba_bec_article['libThem']); ?></span>
                                </div>
                                <h2 class="card-title news-card__title">
                                    <?php echo htmlspecialchars($ba_bec_article['libTitrArt']); ?>
                                </h2>
                                <p class="card-text news-card__excerpt">
                                    <?php echo htmlspecialchars($excerpt); ?>
                                </p>
                                <a href="<?php echo ROOT_URL . '/article.php?numArt=' . (int) $ba_bec_article['numArt']; ?>" class="btn btn-outline-primary mt-auto">Lire la suite</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light border news-empty" role="status">
                        Aucune actualité ne correspond à vos filtres pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

if ($isPartial) {
    echo render_news_grid($ba_bec_articles);
    exit;
}
?>

<main class="container py-5">
    <section class="news-summary">
        <p class="news-summary__eyebrow">Actualités</p>
        <h1 class="news-summary__title">Restez au plus près de la vie du club</h1>
        <p class="news-summary__text">
            Entre résultats, interviews, moments forts et coulisses, retrouvez ici l'ensemble des actualités du BEC.
            Ce fil éditorial met en avant les histoires qui font vibrer la communauté, avec des mises à jour régulières
            pour ne rien manquer des temps forts.
        </p>
    </section>

    <section class="news-filters" aria-label="Filtres des actualités">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-12 col-lg-3">
                <label for="theme" class="form-label">Thématique</label>
                <select id="theme" name="theme" class="form-select">
                    <option value="0">Toutes les thématiques</option>
                    <?php foreach ($ba_bec_thematiques as $ba_bec_thematique): ?>
                        <option value="<?php echo (int) $ba_bec_thematique['numThem']; ?>" <?php echo $theme === (int) $ba_bec_thematique['numThem'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($ba_bec_thematique['libThem']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-lg-3">
                <label for="search" class="form-label">Recherche par titre</label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    class="form-control"
                    placeholder="Ex: victoire, équipe, match"
                    value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>"
                />
            </div>
            <div class="col-12 col-lg-3">
                <label for="keyword" class="form-label">Mots-clés</label>
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    class="form-control"
                    placeholder="Ex: entraînement, événement"
                    list="keyword-options"
                    data-keywords="<?php echo htmlspecialchars(json_encode($ba_bec_keywords, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>"
                    value="<?php echo htmlspecialchars($ba_bec_keyword, ENT_QUOTES); ?>"
                />
                <datalist id="keyword-options">
                    <?php foreach ($ba_bec_keywords as $ba_bec_keyword_option): ?>
                        <option value="<?php echo htmlspecialchars($ba_bec_keyword_option, ENT_QUOTES); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="col-12 col-lg-3">
                <label for="sort" class="form-label">Trier</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Plus récent</option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Plus ancien</option>
                    <option value="liked" <?php echo $sort === 'liked' ? 'selected' : ''; ?>>Les plus likés</option>
                </select>
            </div>
            <div class="col-12 d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary" id="news-reset">Réinitialiser</button>
                <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                <p class="news-filters__count ms-lg-auto mb-0" id="news-count">
                    <?php echo format_news_count(count($ba_bec_articles)); ?>
                </p>
            </div>
        </form>
    </section>

    <?php echo render_news_grid($ba_bec_articles); ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.news-filters form');
    let grid = document.querySelector('.news-grid');
    const resetButton = document.getElementById('news-reset');
    const keywordInput = document.getElementById('keyword');
    const keywordOptions = document.getElementById('keyword-options');
    if (!form || !grid || typeof window.fetch !== 'function') {
        return;
    }

    const keywordList = keywordInput?.dataset.keywords
        ? JSON.parse(keywordInput.dataset.keywords)
        : [];

    const updateKeywordOptions = (value) => {
        if (!keywordOptions) {
            return;
        }
        const query = value.trim().toLowerCase();
        const matches = query === ''
            ? keywordList
            : keywordList.filter((item) => item.toLowerCase().includes(query));
        keywordOptions.innerHTML = matches
            .map((item) => `<option value="${item.replace(/"/g, '&quot;')}"></option>`)
            .join('');
    };

    let debounceTimer;
    const debounceFetch = () => {
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(() => {
            submitFilters();
        }, 300);
    };

    const buildUrl = (includePartial) => {
        const formData = new FormData(form);
        if (includePartial) {
            formData.append('partial', '1');
        }
        const params = new URLSearchParams(formData);
        const action = form.getAttribute('action') || window.location.pathname;
        return `${action}?${params.toString()}`;
    };

    const submitFilters = async () => {
        const url = buildUrl(true);
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const html = await response.text();
            const temp = document.createElement('div');
            temp.innerHTML = html;
            const newGrid = temp.querySelector('.news-grid');
            if (!newGrid) {
                throw new Error('No grid found in response');
            }
            grid.replaceWith(newGrid);
            grid = newGrid;
            const countElement = document.getElementById('news-count');
            if (countElement && newGrid.dataset.newsCountLabel) {
                countElement.textContent = newGrid.dataset.newsCountLabel;
            }
            const historyUrl = buildUrl(false);
            window.history.replaceState({}, '', historyUrl);
        } catch (error) {
            form.submit();
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        submitFilters();
    });

    form.addEventListener('change', (event) => {
        if (event.target.matches('select')) {
            submitFilters();
        }
    });

    form.addEventListener('input', (event) => {
        if (event.target.matches('input[type="search"], input[type="text"]')) {
            if (event.target === keywordInput) {
                updateKeywordOptions(event.target.value);
            }
            debounceFetch();
        }
    });

    if (resetButton) {
        resetButton.addEventListener('click', () => {
            form.reset();
            if (keywordInput) {
                updateKeywordOptions(keywordInput.value);
            }
            submitFilters();
        });
    }

    if (keywordInput) {
        updateKeywordOptions(keywordInput.value);
    }
});
</script>

<?php
require_once 'footer.php';
?>
