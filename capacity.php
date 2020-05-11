<?php
    require "common.php";
    require "dbconnection.php";

    $min = $_GET["minCapacity"];
    $max = $_GET["maxCapacity"];

    // Validation
    if (!is_numeric($min) || !is_numeric($max)) {
        type("h1", "Min and max capacity must be numeric.");
        die();
    }

    $q = "SELECT `name`, `capacity`, `weekend_price`, `weekday_price` FROM `venue` WHERE `capacity` BETWEEN $min AND $max AND `licensed` = 1 ORDER BY `capacity` ASC";
    $res =& $db -> query($q);

    if (PEAR::isError($res)) {
        die($res -> getMessage());
    } else if ($res -> numRows() == 0) {
        type("h1", "No matching results.");
        die();
    }

    echo    "<table border=1 class='pad'>
                <tr>
                    <th>Venue</th>
                    <th>Capacity</th>
                    <th>Weekend Price</th>
                    <th>Weekday Price</th>
                </tr>
                <tr>";
    while ($row = $res -> fetchRow()) {
        foreach($row as $key => $field) {
            if (strpos($key, "price")) {
                // insert currency to price fields
                echo "<td>£ $field</td>";
            } else {
                echo "<td>$field</td>";
            }
        }
        echo    "</tr>";
    }
    echo    "</table>";
?>