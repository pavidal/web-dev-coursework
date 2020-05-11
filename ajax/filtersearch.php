<?php

require "../dbconnection.php";

/**
 * Calculates total cost of the day
 *
 * @param [array()] $value
 * @return [String] $totalCost - Formatted number
 */
function totalCost($date, $value) {
    // Total cost for catering
    $caterCost = intval($value["cost"]) * intval($GLOBALS["size"]);
    $venueCost = 0;
    $totalCost = 0;

    // Check if the day is in a weekend
    // Date used here is Date, not DateTime
    // as it's easier to figure out the day
    if (date("N", strtotime($date)) >= 6) {
        $venueCost = $value["weekend"];
    } else {
        $venueCost = $value["weekday"];
    }

    $totalCost = intval($venueCost) + $caterCost;

    // Return pre-formatted number
    return number_format($totalCost);
}

$from = $_REQUEST["from"];
$to = $_REQUEST["to"];
$flex = $_REQUEST["flex"];
$size = $_REQUEST["size"];
$cater = $_REQUEST["cater"];

$caterQuery = "";

// Add this line to SQL query if a catering class is selected
if (strcmp("Any", $cater) != 0) {
    $caterQuery = "AND catering.grade = $cater";
}

// DateTime is used for dates as it's easier to iterate
$currentDate = new DateTime($from);
$finalDate = new DateTime($to);

echo '<table class="results-table center">',
            "<tr>",
                "<th>Date</th>",
                "<th>Venues <sup style='font-size: small; font-weight: 400;'>(Price ▲)</sup></th>",
            "</tr>";


/**
 * Iterating for each date in range
 * I know it's not the best solution, it would be much nicer to have it
 * done in SQL somehow but I can't figure it out.
 */
for ($currentDate; $currentDate <= $finalDate; $currentDate -> modify("+1 day")) {
    // Formatting dates to String
    $isoDate = $currentDate -> format("Y-m-d");         // for SQL
    $friendlyDate = $currentDate -> format("D, jS F");  // for display

    $q = "SELECT venue.name, venue.weekend_price AS 'Weekend', venue.weekday_price AS 'Weekday', catering.cost
        FROM venue, catering
        WHERE venue.venue_id = catering.venue_id
            $caterQuery
            AND venue.capacity >= $size
            AND venue.venue_id NOT IN (SELECT venue_booking.venue_id
                FROM venue_booking
                WHERE venue_booking.date_booked = CAST('$isoDate' AS DATE))
        GROUP BY venue.name
        ORDER BY (venue.weekend_price + venue.weekday_price)";


    $res = &$db->query($q);

    if (PEAR::isError($res)) {
        die("<h1 class='center'>Error while getting results. Please try again later.</h1>");
    }

    $list = $res->fetchAll();

    // Building table contents
    echo    "<tr>",
                "<td>$friendlyDate</td>",
                "<td><ul>";

    if (empty($list)) {
        echo        "<li><p>No matching venues available.</p></li>";
    }

    // Printing results as a link in ul
    // Each venue links to its own page for more info
    foreach ($list as $key => $fields) {

        // Total cost of the day, including venue (day dependent) and catering (grade cost * party)
        $totalCost = totalCost($currentDate -> format("r"), $fields);
        $venueName = $fields["name"];

        echo        "<li><a href='viewvenue.php?name=$venueName'>$venueName - £ $totalCost</a></li>";
    }

    echo        "</ul></td>",
            "</tr>";

}

echo "</table>";
?>
