<?php
require "common.php";
require "dbconnection.php";

$id = $_GET["venueId"];

// validation
if (!is_numeric($id)) {
    type("h1", "Venue ID must be numeric");
    die();
}

$q = "SELECT * FROM venue WHERE venue_id=$id";
$res = &$db->query($q);

if (PEAR::isError($res)) {
    die($res->getMessage());
}

$row = $res->fetchRow();

if (!empty($row)) {
    echo    "<table border=1 class='pad'>
                    <tr>
                        <th>Venue ID</th>
                        <th>Venue Name</th>
                        <th>Capacity</th>
                        <th>Weekend Price</th>
                        <th>Weekday Price</th>
                        <th>Licensed</th>
                    </tr>
                    <tr>";

    foreach ($row as $key => $value) {
        if ($key == "licensed") {
            // changes 1/0 in "licensed" field to yes/no
            // function found in common.php
            echo "<td>", intToYesNo($value), "</td>";
        } else if (strpos($key, "price")) {
            // insert currency to price fields
            echo "<td>£ $value</td>";
        } else {
            echo "<td>$value</td>";
        }
    }

    echo        "</tr>
                </table>";
} else {
    type("h1", "No matching venues found.");
    die();
}
?>