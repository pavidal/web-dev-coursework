<!-- This is a PHP Script to search the four most booked venues and make venue cards for each -->

<?php

    require "../dbconnection.php";

    $q = "SELECT `venue`.`name`
        FROM `venue_booking`, `venue`
        WHERE venue.venue_id = venue_booking.venue_id
        GROUP BY `venue`.`name`
        ORDER BY COUNT(`venue_booking`.`venue_id`) DESC";

    $res = &$db->query($q);

    if (PEAR::isError($res)) {
        die($res->getMessage());
    }

    $table = $res->fetchAll();

    echo '<div class="card-row">';

    for ($i=0; $i < 4; $i++) {
        $name = $table[$i]["name"];
        echo '<div class="card-container center">',         // Container, has 2 cards each
                '<img src="img/'.$name.'.jpg" alt="'.$name.'">',    //Venue Image
                '<div class="card">',
                    '<p>'.$name.'</p>',     // venue name
                    '<a href="viewvenue.php?name='.$name.'">',
                        '<button type="discover">Discover</button>',    // Button with link to venue's page
                    '</a>',
                '</div>',
            '</div>';

        // For splitting row, 2 cards per row.
        if ($i == 1) {
            echo '</div>';
            echo '<div class="card-row">';
        }
    }

    echo '</div>';

?>