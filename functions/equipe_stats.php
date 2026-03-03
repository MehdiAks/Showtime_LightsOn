<?php

// Résout l'identifiant d'équipe (numEquipe) à partir d'un code d'équipe.
function ba_bec_resolve_equipe_id_from_code(?string $codeEquipe): ?int
{
    // Utilise la connexion PDO globale initialisée ailleurs (ex : sql_connect()).
    global $DB;

    // Si le code est null, on ne peut pas faire de recherche.
    if ($codeEquipe === null) {
        return null;
    }

    // Nettoie le code d'équipe : supprime espaces et met en majuscules.
    $codeEquipe = strtoupper(trim($codeEquipe));

    // Prépare la requête SQL pour récupérer l'ID de l'équipe depuis la table EQUIPE.
    $stmt = $DB->prepare(
        'SELECT numEquipe FROM EQUIPE WHERE codeEquipe = :codeEquipe LIMIT 1'
    );
    // Exécute la requête avec le code d'équipe comme paramètre lié.
    $stmt->execute([
        ':codeEquipe' => $codeEquipe,
    ]);

    // Récupère la première colonne (numEquipe) de la première ligne.
    $numEquipe = $stmt->fetchColumn();

    // Retourne l'ID d'équipe en int si trouvé, sinon null.
    return $numEquipe !== false ? (int) $numEquipe : null;
}
