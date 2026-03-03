<?php

// Génère (ou récupère) un token CSRF de session.
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Vérifie le token CSRF soumis.
function csrf_verify(?string $token): bool {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

// Vérifie si l'utilisateur a accès à une ressource selon un niveau requis.
function check_access($level) {
    // Si l'utilisateur est connecté, son ID est stocké en session.
    if(isset($_SESSION['id_user'])){
        // Récupère le niveau (numStat) de l'utilisateur depuis la table MEMBRE.
        $user_level = sql_select("MEMBRE", 'numStat', "numMemb = " . $_SESSION['id_user'])[0]['numStat'];
        // Si le niveau est inférieur ou égal au niveau requis, accès autorisé.
        if($user_level <= $level){
            return true;
        }else{
            // Sinon, accès refusé.
            return false;
        }
    }else{
        // Aucun utilisateur en session : accès refusé.
        return false;
    }
}

// Vérifie la validité d'un reCAPTCHA v3 pour une action donnée.
function verifyRecaptcha($token, $action, $threshold = null) {
    // Récupère la clé secrète reCAPTCHA depuis les variables d'environnement.
    $secretKey = getenv('RECAPTCHA_SECRET_KEY');
    // Récupère la clé publique (site key) depuis les variables d'environnement.
    $siteKey = getenv('RECAPTCHA_SITE_KEY');
    // Seuil minimum : paramètre fourni ou valeur de RECAPTCHA_THRESHOLD (0.5 par défaut).
    $resolvedThreshold = $threshold ?? (float) (getenv('RECAPTCHA_THRESHOLD') ?: 0.5);
    // Initialise l'état d'activation de reCAPTCHA.
    $recaptchaEnabled = null;

    // Si la variable d'environnement explicite RECAPTCHA_ENABLED existe, on l'utilise.
    if (array_key_exists('RECAPTCHA_ENABLED', $_ENV)) {
        $recaptchaEnabled = (bool) $_ENV['RECAPTCHA_ENABLED'];
    }

    // Sinon, active reCAPTCHA si les deux clés sont présentes.
    if ($recaptchaEnabled === null) {
        $recaptchaEnabled = !empty($secretKey) && !empty($siteKey);
    }

    // Si reCAPTCHA est désactivé, on retourne un résultat valide.
    if (!$recaptchaEnabled) {
        // En mode debug, on loggue la désactivation.
        if (getenv('APP_DEBUG') === 'true') {
            error_log('reCAPTCHA disabled: skipping verification.');
        }

        // Renvoie une validation "true" avec score neutre.
        return [
            'valid' => true,
            'score' => 0,
            'message' => ''
        ];
    }

    // Si la clé secrète est absente, on ne peut pas valider.
    if (empty($secretKey)) {
        return [
            'valid' => false,
            'score' => 0,
            'message' => 'Configuration reCAPTCHA manquante.'
        ];
    }

    // Si le token est vide, l'utilisateur n'a pas validé le reCAPTCHA.
    if (empty($token)) {
        return [
            'valid' => false,
            'score' => 0,
            'message' => 'Veuillez valider le reCAPTCHA.'
        ];
    }

    // Prépare le payload pour l'appel HTTP vers l'API Google.
    $payload = http_build_query([
        'secret' => $secretKey,
        'response' => $token
    ]);

    $response = false;
    $curlError = '';

    // Utilise cURL si l'extension est disponible (cas le plus courant).
    if (function_exists('curl_init')) {
        // Initialise la requête cURL vers l'endpoint de vérification reCAPTCHA.
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        // Demande à cURL de retourner la réponse sous forme de chaîne.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Force la méthode POST.
        curl_setopt($ch, CURLOPT_POST, true);
        // Ajoute le payload POST.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // Exécute la requête et récupère la réponse.
        $response = curl_exec($ch);
        // Capture une éventuelle erreur cURL.
        $curlError = curl_error($ch);
        // Ferme la ressource cURL pour libérer la mémoire.
        curl_close($ch);
    } else {
        // Fallback si cURL n'est pas disponible (certains hébergements mutualisés).
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 10,
            ],
        ]);
        $response = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($response === false) {
            $curlError = 'HTTP fallback failed';
        }
    }

    // Si la requête a échoué (response false), retour erreur.
    if ($response === false) {
        return [
            'valid' => false,
            'score' => 0,
            'message' => 'Vérification reCAPTCHA impossible.'
        ];
    }

    // Décode la réponse JSON en tableau associatif.
    $data = json_decode($response, true);
    // Si le JSON n'est pas valide, on retourne une erreur.
    if (!is_array($data)) {
        return [
            'valid' => false,
            'score' => 0,
            'message' => 'Réponse reCAPTCHA invalide.'
        ];
    }

    // Récupère le statut de succès renvoyé par Google.
    $ba_bec_success = $data['success'] ?? false;
    // Vérifie si l'API a renvoyé un score.
    $hasScore = array_key_exists('score', $data);
    // Cast le score en float (0 par défaut si absent).
    $score = $hasScore ? (float) $data['score'] : 0.0;
    // Récupère l'action retournée par l'API.
    $responseAction = $data['action'] ?? '';

    // En mode debug, on loggue des informations utiles.
    if (getenv('APP_DEBUG') === 'true') {
        error_log(sprintf(
            'reCAPTCHA action=%s success=%s score=%.2f error=%s',
            $action,
            $ba_bec_success ? 'true' : 'false',
            $score,
            $curlError
        ));
    }

    // Si Google renvoie success=false, on considère la validation échouée.
    if (!$ba_bec_success) {
        return [
            'valid' => false,
            'score' => $score,
            'message' => 'La vérification reCAPTCHA a échoué.'
        ];
    }

    // Si un score est présent et l'action ne correspond pas, on refuse.
    if ($hasScore && $responseAction !== $action) {
        return [
            'valid' => false,
            'score' => $score,
            'message' => 'Action reCAPTCHA invalide.'
        ];
    }

    // Si un score est présent et inférieur au seuil, on refuse.
    if ($hasScore && $score < $resolvedThreshold) {
        return [
            'valid' => false,
            'score' => $score,
            'message' => 'Score reCAPTCHA insuffisant.'
        ];
    }

    // Sinon, tout est valide : on renvoie un succès.
    return [
        'valid' => true,
        'score' => $score,
        'message' => ''
    ];
}

?>
