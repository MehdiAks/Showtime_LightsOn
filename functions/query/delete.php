<?php
// Détecte si une erreur SQL correspond à une contrainte de clé étrangère.
function sql_is_foreign_key_error(?string $message, ?string $code = null): bool {
    if (!$message && !$code) {
        return false;
    }

    $normalizedMessage = strtolower((string) $message);
    $normalizedCode = strtolower((string) $code);

    if ($normalizedCode === '23000') {
        return true;
    }

    return str_contains($normalizedMessage, 'foreign key constraint')
        || str_contains($normalizedMessage, 'cannot delete or update a parent row')
        || str_contains($normalizedMessage, 'a foreign key constraint fails');
}

// Suppression d'enregistrements en base.
function sql_delete($table, $where){
    global $DB;
    sql_clear_last_error();

    // Connexion à la base si nécessaire.
    if(!$DB){
        sql_connect();
    }

    try{
        // Transaction pour sécuriser la suppression.
        $DB->beginTransaction();

        // Préparation de la requête DELETE.
        $query = "DELETE FROM $table WHERE $where;";
        $request = $DB->prepare($query);
        $request->execute();
        $DB->commit();
        $request->closeCursor();
    }
    catch(PDOException $ba_bec_e){
        $DB->rollBack();
        if (isset($request)) {
            $request->closeCursor();
        }
        $ba_bec_message = $ba_bec_e->getMessage();
        $ba_bec_code = (string) $ba_bec_e->getCode();
        sql_set_last_error($ba_bec_message);
        return [
            'success' => false,
            'message' => $ba_bec_message,
            'code' => $ba_bec_code,
            'constraint' => sql_is_foreign_key_error($ba_bec_message, $ba_bec_code),
        ];
    }

    $ba_bec_error = $DB->errorInfo();
    if($ba_bec_error[0] != 0){
        // Remonte l'erreur SQL si elle existe.
        $ba_bec_message = $ba_bec_error[2];
        $ba_bec_code = (string) $ba_bec_error[0];
        sql_set_last_error($ba_bec_message);
        return [
            'success' => false,
            'message' => $ba_bec_message,
            'code' => $ba_bec_code,
            'constraint' => sql_is_foreign_key_error($ba_bec_message, $ba_bec_code),
        ];
    }
    // Retourne un statut explicite pour l'appelant.
    return [
        'success' => true,
        'message' => 'Opération réalisée avec succès.',
        'code' => null,
        'constraint' => false,
    ];
}

?>
