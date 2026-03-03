<?php
/******************************************************
*
* Fonction utilitaire de conversion de format de date.
* En entrée :
*  - $dateIn : date en chaîne de caractères.
*  - $from : format d'origine (ex : 'Y-m-d' ou 'Y-m-d H:i:s').
*  - $to : format de sortie désiré (ex : 'd/m/Y').
* En sortie :
*  - Retourne la date reformattée ou une chaîne vide si entrée invalide.
*
* Exemples :
*  $today = date("j, n, Y");       // 10, 3, 2001
*  $today = date("Y-m-d");         // 2001-03-10
*  $today = date("Y-m-d H:i:s");   // 2001-03-10 17:16:18 (format DATETIME MySQL)
*
*******************************************************/

// Convertit une date d'un format vers un autre.
function dateChangeFormat($dateIn, $from, $to){
    // Si la date en entrée n'est pas vide :
    if($dateIn != ''){
        // Parse la date selon le format d'origine.
        $dateOut = DateTime::createFromFormat($from, $dateIn);
        // Retourne la date formatée selon le format de sortie demandé.
        return $dateOut->format($to);
    }else{
        // Si aucune date en entrée, retourne une chaîne vide.
        return "";
    }
}
?>
