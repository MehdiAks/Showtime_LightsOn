<?php
// Charge la configuration globale de l'application (constantes, session, autoload, etc.).
// On utilise __DIR__ pour garantir un chemin absolu fiable, quel que soit le répertoire d'exécution.
require_once __DIR__ . '/../config.php';

// Récupère le statut de l'utilisateur depuis la session.
// Si la clé n'existe pas, on utilise null pour expliciter l'absence d'authentification.
$ba_bec_numStat = $_SESSION['numStat'] ?? null;
// Vérifie que l'utilisateur est bien connecté et possède le statut attendu (ici, 1).
// On cast en int pour éviter les comparaisons ambiguës liées aux chaînes.
if ($ba_bec_numStat === null || (int) $ba_bec_numStat !== 1) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas autorisé.
    // ROOT_URL vient de la configuration et sert à produire une URL absolue cohérente.
    header('Location: ' . ROOT_URL . '/views/backend/security/login.php');
    // Interrompt immédiatement l'exécution pour éviter toute action non autorisée.
    exit;
}

// Lit le contrôleur demandé dans l'URL (ex: ?controller=article).
// Si absent, on met une chaîne vide pour forcer la vérification plus bas.
$controllerKey = $_GET['controller'] ?? '';
// Lit l'action (méthode) à exécuter ; par défaut "list" si non précisée.
$action = $_GET['action'] ?? 'list';

// Table de correspondance entre les clés d'URL et les noms de classes contrôleurs.
// Cela évite d'autoriser des classes arbitraires et limite l'accès aux contrôleurs autorisés.
$controllerMap = [
    'statut' => 'StatutController',
    'article' => 'ArticleController',
];

// Si la clé de contrôleur ne figure pas dans la liste blanche, on renvoie une 404.
if (!isset($controllerMap[$controllerKey])) {
    http_response_code(404);
    echo 'Contrôleur introuvable.';
    exit;
}

// Résout le nom de la classe et le chemin du fichier du contrôleur.
$controllerClass = $controllerMap[$controllerKey];
$controllerPath = __DIR__ . '/../controllers/' . $controllerClass . '.php';

// Vérifie que le fichier de contrôleur existe avant de tenter le require.
// Cela évite une erreur fatale et permet de répondre proprement en 404.
if (!file_exists($controllerPath)) {
    http_response_code(404);
    echo 'Fichier contrôleur introuvable.';
    exit;
}

// Charge le fichier de la classe de contrôleur demandée.
require_once $controllerPath;

// Instancie le contrôleur pour pouvoir appeler l'action.
$controller = new $controllerClass();

// Vérifie que la méthode demandée existe bien dans le contrôleur.
// Si l'action n'est pas définie, on renvoie une 404.
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Action introuvable.';
    exit;
}

// Exécute l'action demandée (méthode du contrôleur).
$controller->$action();

?>
