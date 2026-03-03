<?php
// Commentaire : ce fichier centralise la configuration globale de l'application.
// Il est inclus en début de page (via require_once) afin d'initialiser l'environnement.

// Définition robuste du chemin racine du site.
// __DIR__ évite de dépendre de DOCUMENT_ROOT (peut varier selon l'hébergement).
define('ROOT', __DIR__);

// Construit une URL de base fiable pour limiter les risques de Host Header Injection.
$ba_bec_forwardedProto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
$ba_bec_httpsEnabled = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $ba_bec_forwardedProto === 'https';
$ba_bec_scheme = $ba_bec_httpsEnabled ? 'https' : 'http';
$ba_bec_hostHeader = $_SERVER['HTTP_HOST'] ?? 'localhost';
// On retire les caractères interdits dans un nom d'hôte et on limite la longueur.
$ba_bec_sanitizedHost = preg_replace('/[^a-zA-Z0-9\.\-:\[\]]/', '', $ba_bec_hostHeader);
if (!$ba_bec_sanitizedHost) {
    $ba_bec_sanitizedHost = 'localhost';
}
define('ROOT_URL', $ba_bec_scheme . '://' . substr($ba_bec_sanitizedHost, 0, 255));

// S'assure que la session PHP est démarrée pour toutes les pages de l'application.
// La session sert notamment à stocker l'ID de l'utilisateur connecté et d'autres états.
if (session_status() === PHP_SESSION_NONE) {
    $ba_bec_secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $ba_bec_secureCookie,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    // Démarre la session (création du cookie PHPSESSID si nécessaire).
    session_start();
}

// Compatibilité PHP < 8 : certaines pages utilisent les helpers str_* natifs.
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle === '' || strpos($haystack, $needle) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return true;
        }
        $needleLength = strlen($needle);
        return substr($haystack, -$needleLength) === $needle;
    }
}

// Récupère le chemin du script courant (ex : /api/security/login.php).
// L'opérateur ?? '' évite un Notice si SCRIPT_NAME n'est pas défini.
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
// Si la page appartient à l'API (préfixe /api/), on applique des règles d'accès.
if (strpos($scriptName, '/api/') === 0) {
    // Liste blanche des endpoints accessibles sans authentification.
    $publicApiEndpoints = [
        '/api/security/signup.php',
        '/api/security/login.php',
        '/api/security/disconnect.php',
        '/api/security/cookie-consent.php',
    ];

    // Vérifie si l'endpoint courant est public (comparaison stricte).
    $isPublicEndpoint = in_array($scriptName, $publicApiEndpoints, true);
    // Vérifie si l'utilisateur est authentifié (ID utilisateur en session).
    $isAuthenticated = !empty($_SESSION['user_id']);

    // Si l'endpoint n'est pas public ET que l'utilisateur n'est pas connecté :
    if (!$isPublicEndpoint && !$isAuthenticated) {
        // Retourne un code HTTP 403 (Forbidden).
        http_response_code(403);
        // Stoppe l'exécution en renvoyant un message d'erreur.
        exit('Accès interdit.');
    }
}

// Charge la classe DotEnv (bibliothèque interne) pour lire le fichier .env.
require_once ROOT . '/includes/libs/DotEnv.php';
// Instancie DotEnv avec le chemin du fichier .env et charge les variables d'environnement.
(new DotEnv(ROOT . '/.env'))->load();

// Charge les constantes de configuration de la base de données (hôte, user, etc.).
require_once ROOT . '/config/defines.php';

// Si l'environnement indique qu'on est en mode debug, on active l'affichage des erreurs.
if (getenv('APP_DEBUG') == 'true') {
    // Active les paramètres d'erreurs PHP (définis dans config/debug.php).
    require_once ROOT . '/config/debug.php';
}

// Charge les fonctions utilitaires globales de l'application.
require_once ROOT . '/functions/global.inc.php';

// Charge la configuration sécurité (ex : helpers/constantes de sécurité).
require_once ROOT . '/config/security.php';

?>
