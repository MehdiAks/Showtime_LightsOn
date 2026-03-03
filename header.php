
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once ROOT . '/includes/libs/cookie-consent.php';
$ba_bec_pseudoMemb = $_SESSION['pseudoMemb'] ?? null;
$ba_bec_numStat = $_SESSION['numStat'] ?? null;
$hasBackgroundVideo = $pageHasVideo ?? false;
$backgroundVideoSource = $pageBackgroundVideo ?? (ROOT_URL . '/src/video/Background_index.mp4');
$backgroundVideoPoster = $pageBackgroundPoster ?? (ROOT_URL . '/src/images/background/background-index-1.webp');
$current_page = $_SERVER['SCRIPT_NAME'];

$bodyClasses = [$hasBackgroundVideo ? 'has-site-video' : 'has-solid-bg'];
$isHomePage = $current_page === '/index.php';
if ($isHomePage) {
    $bodyClasses[] = 'home-page';
}

$club_pages = [
    '/Pages_supplementaires/notre-histoire.php',
    '/Pages_supplementaires/organigramme-benevoles.php',
    '/Pages_supplementaires/equipes.php',
    '/Pages_supplementaires/joueurs.php',
    '/Pages_supplementaires/nos-partenaires.php',
];

$ba_bec_cookieConsent = null;
if (function_exists('sql_connect')) {
    global $DB;
    if (!$DB) {
        sql_connect();
    }
    if (!empty($DB)) {
        $ba_bec_cookieConsent = getCookieConsent($DB);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bordeaux Etudiants Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ROOT_URL . '/src/css/css-propre/reset.css'; ?>" rel="stylesheet">
    <link href="<?php echo ROOT_URL . '/src/css/css-propre/style.css'; ?>" rel="stylesheet">
    <link href="<?php echo ROOT_URL . '/src/css/css-propre/fonts.css'; ?>" rel="stylesheet">
        <link href="<?php echo ROOT_URL . '/src/css/css-header-footer/header-et-footer.css'; ?>" rel="stylesheet">
    <link rel="icon" type="image/png" href="/src/images/logo/logo-bec/logo.svg" />
    <?php if (!empty($pageStyles) && is_array($pageStyles)) : ?>
        <?php foreach ($pageStyles as $stylePath) : ?>
            <link href="<?php echo htmlspecialchars($stylePath); ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

</head>

<body class="<?php echo implode(' ', $bodyClasses); ?>">
    
    <?php if ($hasBackgroundVideo): ?>
        <div class="site-background" aria-hidden="true">
            <video class="site-background-video" autoplay muted loop playsinline poster="<?php echo $backgroundVideoPoster; ?>">
                <source src="<?php echo $backgroundVideoSource; ?>" type="video/mp4">
            </video>
            <div class="site-background-overlay"></div>
        </div>
    <?php endif; ?>
    <header class="site-header">
        <div class="site-header-offset" aria-hidden="true"></div>
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo ROOT_URL . '/index.php'; ?>">
                <video class="site-logo" autoplay muted loop playsinline aria-label="BEC" poster="<?php echo ROOT_URL . '/src/images/logo/logo-bec/logo.png'; ?>">
                    <source src="<?php echo ROOT_URL . '/src/images/logo/logo-bec/logo-anime-transparent.mov'; ?>" type="video/quicktime">
                    <img src="<?php echo ROOT_URL . '/src/images/logo/logo-bec/logo.png'; ?>" alt="Logo BEC">
                </video>
                <span>Bordeaux Étudiant Club</span>
            </a>

            <!-- navigation principale -->
            <nav class="header-nav" aria-label="Navigation principale">
                <ul>
                    <li>
                        <a href="<?php echo ROOT_URL . '/index.php'; ?>" <?php if ($current_page == '/index.php') echo 'class="current"'; ?>>Accueil</a>
                    </li>
                    <li>
                        <div class="header-submenu">
                            <button type="button" class="submenu-toggle<?php if (in_array($current_page, $club_pages, true)) echo ' current'; ?>" aria-haspopup="true" aria-expanded="false" aria-controls="submenu-club">
                                Le club
                            </button>
                            <ul class="submenu-list" id="submenu-club" aria-label="Le club">
                                <li>
                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/notre-histoire.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/notre-histoire.php') echo 'class="current"'; ?>>Notre histoire</a>
                                </li>
                                <li>
                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/organigramme-benevoles.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/organigramme-benevoles.php') echo 'class="current"'; ?>>Bénévoles</a>
                                </li>
                                <li>
                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/joueurs.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/joueurs.php') echo 'class="current"'; ?>>Joueurs</a>
                                </li>
                                <li>
                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/equipes.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/equipes.php') echo 'class="current"'; ?>>Équipes</a>
                                </li>
                                <li>
                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/nos-partenaires.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/nos-partenaires.php') echo 'class="current"'; ?>>Nos partenaires</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="<?php echo ROOT_URL . '/actualites.php'; ?>" <?php if ($current_page == '/actualites.php') echo 'class="current"'; ?>>Actualités</a>
                    </li>
                    <!--<li>
                        <a href="<?php echo ROOT_URL . '/Pages_supplementaires/boutique.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/boutique.php') echo 'class="current"'; ?>>Boutique</a>
                    </li>-->
                    <li>
                        <a href="<?php echo ROOT_URL . '/Pages_supplementaires/calendrier.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/calendrier.php') echo 'class="current"'; ?>>Calendrier</a>
                    </li>
                    <li>
                        <a href="<?php echo ROOT_URL . '/anciens-et-amis.php'; ?>" <?php if ($current_page == '/anciens-et-amis.php') echo 'class="current"'; ?>>Anciens et amis</a>
                    </li>
                </ul>
            </nav>

                <!-- Menu burger pour le responsive -->
            <div class="header-burger-wrapper">
                <details class="header-burger-menu">
                    <summary class="header-burger-toggle" aria-label="Ouvrir le menu">
                        <span class="header-burger-icon" aria-hidden="true">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </summary>

                    <div class="header-burger-panel">
                        <button type="button" class="header-burger-close" aria-label="Fermer le menu" onclick="this.closest('details').removeAttribute('open')">
                            &times;
                        </button>

                            <nav class="header-burger-nav" aria-label="Navigation principale">
                                <ul>
                                    <li>
                                        <a href="<?php echo ROOT_URL . '/index.php'; ?>" <?php if ($current_page == '/index.php') echo 'class="current"'; ?>>Accueil</a>
                                    </li>
                                    <li>
                                        <div class="header-submenu">
                                            <button type="button" class="submenu-toggle<?php if (in_array($current_page, $club_pages, true)) echo ' current'; ?>" aria-haspopup="true" aria-expanded="false" aria-controls="submenu-club">
                                                Le club
                                            </button>
                                            <ul class="submenu-list header-burger-sublist" id="submenu-club" aria-label="Le club">
                                                <li>
                                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/notre-histoire.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/notre-histoire.php') echo 'class="current"'; ?>>Notre histoire</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/organigramme-benevoles.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/organigramme-benevoles.php') echo 'class="current"'; ?>>Bénévoles</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/joueurs.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/joueurs.php') echo 'class="current"'; ?>>Joueurs</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/equipes.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/equipes.php') echo 'class="current"'; ?>>Équipes</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/nos-partenaires.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/nos-partenaires.php') echo 'class="current"'; ?>>Nos partenaires</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="<?php echo ROOT_URL . '/actualites.php'; ?>" <?php if ($current_page == '/actualites.php') echo 'class="current"'; ?>>Actualités</a>
                                    </li>
                                    
                                    <li>
                                        <a href="<?php echo ROOT_URL . '/Pages_supplementaires/calendrier.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/calendrier.php') echo 'class="current"'; ?>>Calendrier</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo ROOT_URL . '/anciens-et-amis.php'; ?>" <?php if ($current_page == '/anciens-et-amis.php') echo 'class="current"'; ?>>Anciens et amis</a>
                                    </li>
                                    <li>
                                        <a class="header-burger-boutique-link<?php if ($current_page == '/Pages_supplementaires/boutique.php') echo ' current'; ?>" href="<?php echo ROOT_URL . '/Pages_supplementaires/boutique.php'; ?>">
                                            Boutique
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        
                        <div class="header-burger-actions">
                            <p class="header-burger-title">Espace membre</p>
                            <?php if ($ba_bec_pseudoMemb): ?>
                                <div class="header-burger-user">
                                    <span><?php echo htmlspecialchars($ba_bec_pseudoMemb); ?></span>
                                </div>
                                <div class="header-burger-links">
                                    <a href="<?php echo ROOT_URL . '/Pages_supplementaires/compte.php'; ?>">Mon compte</a>
                                    <?php if ($ba_bec_numStat === 1 || $ba_bec_numStat === 2): ?>
                                        <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>">Panneau admin</a>
                                    <?php endif; ?>
                                    <a class="header-burger-logout" href="<?php echo ROOT_URL . '/api/security/disconnect.php'; ?>">Déconnexion</a>
                                </div>
                            <?php else: ?>
                                <a class="btn btn-bec-primary w-100" href="<?php echo ROOT_URL . '/views/backend/security/login.php'; ?>">
                                    Connexion / Inscription
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </details>
            </div>
                <!-- menu compte -->

                <div class="header-compte-wrapper">
                    <a class="btn btn-boutique-header" href="<?php echo ROOT_URL . '/Pages_supplementaires/boutique.php'; ?>" <?php if ($current_page == '/Pages_supplementaires/boutique.php') echo 'aria-current="page"'; ?>>
                        Boutique
                    </a>
                    <details class="header-compte-menu">
                        <summary class="btn btn-bec-primary btn-compte" aria-label="Ouvrir le menu">
                            <?php echo $ba_bec_pseudoMemb ? htmlspecialchars($ba_bec_pseudoMemb) : 'Compte'; ?>
                        </summary>
                        
                        <div class="header-compte-panel">
                            <button type="button" class="header-compte-close" aria-label="Fermer le menu" onclick="this.closest('details').removeAttribute('open')">
                                &times;
                            </button>
                            <div class="header-compte-actions">
                                <p class="header-compte-title">Espace membre</p>
                                <?php if ($ba_bec_pseudoMemb): ?>
                                    <div class="header-compte-user">
                                        <span><?php echo htmlspecialchars($ba_bec_pseudoMemb); ?></span>
                                    </div>
                                    <div class="header-compte-links">
                                        <a href="<?php echo ROOT_URL . '/Pages_supplementaires/compte.php'; ?>">Mon compte</a>
                                        <?php if ($ba_bec_numStat === 1 || $ba_bec_numStat === 2): ?>
                                            <a href="<?php echo ROOT_URL . '/views/backend/dashboard.php'; ?>">Panneau admin</a>
                                        <?php endif; ?>
                                        <a class="header-compte-logout" href="<?php echo ROOT_URL . '/api/security/disconnect.php'; ?>">Déconnexion</a>
                                    </div>
                                <?php else: ?>
                                    <a class="btn btn-bec-primary w-100" href="<?php echo ROOT_URL . '/views/backend/security/login.php'; ?>">
                                        Connexion / Inscription
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </details>
                </div>
            </div>

        </div>


    </header>
    <?php if ($ba_bec_cookieConsent === null): ?>
        <div class="cookie-overlay" id="cookie-overlay" hidden></div>
        <div class="cookie-popup" id="cookie-popup" role="dialog" aria-modal="true" aria-labelledby="cookie-title" hidden>
            <div class="cookie-content">
                <h2 id="cookie-title">Gestion des cookies</h2>
                <p>Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez accepter ou refuser.</p>
                <div class="cookie-buttons">
                    <button type="button" class="btn btn-light" data-cookie-choice="1">Accepter</button>
                    <button type="button" class="btn btn-outline-light" data-cookie-choice="0">Refuser</button>
                </div>
            </div>
        </div>
        <script>
            (function () {
                var popup = document.getElementById('cookie-popup');
                var overlay = document.getElementById('cookie-overlay');
                if (!popup || !overlay) {
                    return;
                }
                popup.hidden = false;
                overlay.hidden = false;
                document.body.classList.add('cookie-choice-required');

                popup.querySelectorAll('[data-cookie-choice]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var choice = button.getAttribute('data-cookie-choice');
                        var formData = new FormData();
                        formData.append('consent', choice);
                        fetch('<?php echo ROOT_URL . '/api/security/cookie-consent.php'; ?>', {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: formData
                        }).finally(function () {
                            popup.hidden = true;
                            overlay.hidden = true;
                            document.body.classList.remove('cookie-choice-required');
                        });
                    });
                });
            })();
        </script>
    <?php endif; ?>
    <main class="site-main container py-5">
