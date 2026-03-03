<?php
/*
 * Endpoint API: api/security/cookie-consent.php
 * Rôle: enregistrer le consentement cookies côté serveur.
 *
 * Déroulé détaillé:
 * 1) Charge la config et la librairie de consentement.
 * 2) Accepte uniquement les requêtes POST.
 * 3) Normalise la valeur du consentement en 0/1.
 * 4) Ouvre une connexion DB si nécessaire, puis enregistre le consentement.
 * 5) Retourne un 204 (pas de contenu) pour signifier le succès silencieux.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once ROOT . '/includes/libs/cookie-consent.php';

// Étape 1: bloquer les méthodes non POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// Étape 2: normaliser la valeur envoyée (1/0).
$consentRaw = $_POST['consent'] ?? null;
$consent = ($consentRaw === '1' || $consentRaw === 1) ? 1 : 0;

// Étape 3: s'assurer que la connexion SQL est disponible.
if (function_exists('sql_connect')) {
    global $DB;
    if (!$DB) {
        sql_connect();
    }
}

// Étape 4: persister le choix utilisateur en base si possible.
if (!empty($DB)) {
    saveCookieConsent($DB, $consent);
}

// Étape 5: statut 204 pour un retour sans payload.
http_response_code(204);
exit();
