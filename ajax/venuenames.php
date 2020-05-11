<?php
    // This fetches all names from database for autocomplete
    require "../dbconnection.php";

    $q = "SELECT `name` FROM `venue` ";
    $res = &$db->query($q);

    if (PEAR::isError($res)) {
        die($res->getMessage());
    }

    $list = $res->fetchAll();

    echo json_encode($list);
?>