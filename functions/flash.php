<?php
// Message par défaut pour une action réussie.
const FLASH_MESSAGE_SUCCESS = 'Opération réalisée avec succès.';
// Message par défaut pour une erreur générique.
const FLASH_MESSAGE_ERROR = 'Une erreur est survenue. Merci de réessayer.';
// Message par défaut si suppression impossible (lié à des dépendances BDD).
const FLASH_MESSAGE_DELETE_IMPOSSIBLE = 'Suppression impossible : cet élément est utilisé.';

// Ajoute un message flash en session (stocké temporairement pour l'affichage).
function flash_add(string $type, string $message): void {
    // Si le tableau de messages n'existe pas en session, on l'initialise.
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    // Ajoute un message avec son type (success, error, warning...).
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

// Ajoute un message de succès (avec message personnalisé ou message par défaut).
function flash_success(?string $message = null): void {
    flash_add('success', $message ?? FLASH_MESSAGE_SUCCESS);
}

// Ajoute un message d'erreur (avec message personnalisé ou message par défaut).
function flash_error(?string $message = null): void {
    flash_add('error', $message ?? FLASH_MESSAGE_ERROR);
}

// Ajoute un message de warning pour suppression impossible.
function flash_delete_impossible(?string $message = null): void {
    flash_add('warning', $message ?? FLASH_MESSAGE_DELETE_IMPOSSIBLE);
}

// Récupère les messages flash puis les supprime de la session.
function flash_get(): array {
    // Lit les messages (ou tableau vide si aucun).
    $messages = $_SESSION['flash'] ?? [];
    // Supprime les messages pour éviter un nouvel affichage.
    unset($_SESSION['flash']);
    // Retourne le tableau des messages.
    return $messages;
}
?>
