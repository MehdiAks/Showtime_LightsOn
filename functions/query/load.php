<?php
// Fonction utilitaire pour échapper les valeurs provenant des formulaires.
function sql_escape($value){
    if(is_array($value)){
        foreach($value as $key => $val){
            $value[$key] = sql_escape($val);
        }
    }else{
        $value = addslashes($value);
    }
    return $value;
}
// Charge les scripts CRUD et helpers SQL.
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/create_table.php';
require_once __DIR__ . '/error.php';
require_once __DIR__ . '/insert.php';
require_once __DIR__ . '/delete.php';
require_once __DIR__ . '/select.php';
require_once __DIR__ . '/update.php';

?>
