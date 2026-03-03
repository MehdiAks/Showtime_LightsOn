<?php
// Mise à jour d'enregistrements en base.
function sql_update($table, $attributs, $where) {
    global $DB;
    sql_clear_last_error();

    // Connexion à la base si nécessaire.
    if(!$DB){
        sql_connect();
    }

    try{
        // Transaction pour sécuriser la mise à jour.
        $DB->beginTransaction();

        // Préparation de la requête UPDATE.
        $query = "UPDATE $table SET $attributs WHERE $where;";
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
        sql_set_last_error($ba_bec_message);
        return ['success' => false, 'message' => $ba_bec_message];
    }

    $ba_bec_error = $DB->errorInfo();
    if($ba_bec_error[0] != 0){
        // Remonte l'erreur SQL si elle existe.
        $ba_bec_message = $ba_bec_error[2];
        sql_set_last_error($ba_bec_message);
        return ['success' => false, 'message' => $ba_bec_message];
    }
    // Retourne un statut explicite pour l'appelant.
    return ['success' => true, 'message' => 'Opération réalisée avec succès.'];
}

?>
