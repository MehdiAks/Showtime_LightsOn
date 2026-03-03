<?php
// À la création du login, le nom d'utilisateur ne doit pas déjà exister.
// Cette fonction vérifie l'absence du pseudo en base de données (table MEMBRE).
function get_ExistPseudo($ba_bec_pseudoMemb){
	// Utilise la connexion PDO globale définie ailleurs.
	global $DB;

    // Si la connexion n'est pas encore initialisée, on ouvre la connexion SQL.
    if(!$DB){
        sql_connect();
    }

    // Prépare une requête SQL pour chercher un membre avec ce pseudo.
	$query = 'SELECT * FROM MEMBRE WHERE pseudoMemb = ?;';
	// Prépare la requête via PDO pour éviter l'injection SQL.
	$ba_bec_result = $DB->prepare($query);
	// Exécute la requête avec le pseudo en paramètre.
	$ba_bec_result->execute(array($ba_bec_pseudoMemb));
	// Retourne le nombre de lignes trouvées (0 = pseudo libre).
	return($ba_bec_result->rowCount());
}
?>
