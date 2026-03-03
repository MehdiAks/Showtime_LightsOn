<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once '../../functions/ctrlSaisies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ba_bec_num = (int) ($_POST['numArtBoutique'] ?? 0);

    if ($ba_bec_num > 0) {
        sql_delete('boutique', "numArtBoutique = '$ba_bec_num'");
    }

    header('Location: ../../views/backend/boutique/list.php');
    exit();
}
