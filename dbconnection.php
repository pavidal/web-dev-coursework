<?php
    include "dbcredentials.php";
    require_once "MDB2.php";

    $hostname = "localhost";

    $source = "mysql://$user:$pass@$hostname/$dbname";
    $db =& MDB2::connect($source);

    if (PEAR::isError($db)) {
        die($db -> getMessage());
    }

    $db -> setFetchMode(MDB2_FETCHMODE_ASSOC);
?>