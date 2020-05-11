<?php
require "common.php";
require "dbconnection.php";

// Change date format to ISO for DB
$date = dateReformatter($_GET["date"]);
$party = $_GET["partySize"];

if (!is_numeric($party)) {
    type("h1", "Min and max capacity must be numeric.");
    die();
}

$q =   "SELECT
            venue.name,
            venue.weekend_price,
            venue.weekday_price
        FROM
            venue
        WHERE
            venue.venue_id NOT IN(
            SELECT
                venue_booking.venue_id
            FROM
                venue_booking
            WHERE
                venue_booking.date_booked = '$date'
        ) AND venue.capacity >= '$party'
        ORDER BY
            venue.name ASC";

$res = &$db->query($q);

if (PEAR::isError($res)) {
    die($res->getMessage());
} else if ($res->numRows() == 0) {
    type("h1", "No matching results.");
    die();
}

echo    "<table border=1 class='pad'>
                <tr>
                    <th>Name</th>
                    <th>Weekend Price</th>
                    <th>Weekday Price</th>
                </tr>
                <tr>";
while ($row = $res->fetchRow()) {
    foreach ($row as $key => $field) {
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