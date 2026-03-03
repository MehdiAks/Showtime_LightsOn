<?php
// Définit l'hôte de la base de données (variable d'environnement DB_HOST).
define('SQL_HOST', getenv('DB_HOST'));
// Définit l'utilisateur de connexion à la base (variable d'environnement DB_USER).
define('SQL_USER', getenv('DB_USER'));
// Définit le mot de passe de connexion (variable d'environnement DB_PASSWORD).
define('SQL_PWD', getenv('DB_PASSWORD'));
// Définit le nom de la base de données (variable d'environnement DB_DATABASE).
define('SQL_DB', getenv('DB_DATABASE'));
// Définit le port SQL (optionnel selon l'hébergeur).
define('SQL_PORT', getenv('DB_PORT') ?: '3306');
?>
