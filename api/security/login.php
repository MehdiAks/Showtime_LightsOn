<?php
/*
 * Endpoint API: api/security/login.php
 * Rôle: authentifier un membre et initialiser sa session.
 *
 * Déroulé détaillé:
 * 1) Charge la configuration et les helpers de sanitisation.
 * 2) Démarre la session PHP pour stocker l'identité de l'utilisateur.
 * 3) Nettoie les champs pseudo/mot de passe soumis via POST.
 * 4) Récupère le membre en base puis vérifie le mot de passe via password_verify.
 * 5) Stocke l'identité en session et redirige vers la page d'accueil (ou renvoie une erreur).
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Étape 1: nettoyer les entrées utilisateur.
    $ba_bec_pseudo = ctrlSaisies($_POST['pseudo']);
    $ba_bec_password = ctrlSaisies($_POST['password']);

    // Vérifier si l'utilisateur existe avec ce nom d'utilisateur
    $ba_bec_user = sql_select("MEMBRE", "*", "pseudoMemb = '$ba_bec_pseudo'");
    
    if ($ba_bec_user) {
        // Utiliser password_verify pour comparer le mot de passe saisi avec celui haché
        if (password_verify($ba_bec_password, $ba_bec_user[0]['passMemb'])) {
            // Étape 2: ouvrir la session applicative et conserver l'identifiant membre.
            $_SESSION['user_id'] = $ba_bec_user[0]['numMemb'];
            $_SESSION['pseudoMemb'] = $ba_bec_user[0]['pseudoMemb'];

            // Étape 3: rediriger après authentification réussie.
            header("Location: " . ROOT_URL . "/index.php");
            $_SESSION['pseudoMemb'] = $ba_bec_pseudo; // Stocke le nom d'utilisateur en session
            exit();
        } else {
            // Mot de passe invalide: renvoyer une erreur explicite.
            header("Location: " . ROOT_URL . "/views/security/login.php?error=Mot de passe incorrect");
            exit();
        }
    } else {
        // Aucun utilisateur trouvé avec ce pseudo: renvoyer l'erreur générique.
        header("Location: " . ROOT_URL . "/views/security/login.php?error=Nom d'utilisateur ou mot de passe incorrect");
        exit();
    }
}
?>
