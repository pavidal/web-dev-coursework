<?php
require "dbconnection.php";
include "common.php";
$name = "No venues found.";
if (!empty($_REQUEST["name"])) {
    $name = $_REQUEST["name"];
}

// Get details from DB
$q = "SELECT `capacity` AS `Capacity`, `weekend_price` AS `Weekend Price`, `weekday_price` AS `Weekday Price`, `licensed` AS `Licensed Venue`
    FROM venue WHERE name='$name'";

$q2 = "SELECT catering.grade, catering.cost FROM catering
    WHERE catering.venue_id = (SELECT venue.venue_id FROM venue
                            WHERE venue.name = '$name')";

$res = &$db->query($q);
$venue = $res->fetchRow();

$res2 = &$db->query($q2);
$catering = $res2->fetchAll();

if (PEAR::isError($res) || PEAR::isError($res2)) {
    die("<h1 class='center'>Error while getting results. Please try again later.</h1>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Importing Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Main stylesheet for this page -->
    <link rel="stylesheet" type="text/css" href="main.css">

    <!-- Importing JS Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Dynamic title -->
    <title><?= $name ?></title>

    <style>
        /**
            STYLE OVERRIDES
        */
        nav {
            background-image: linear-gradient(to bottom, white, transparent) !important;
        }

        nav * {
            color: black !important;
        }

        nav a:hover {
            border-color: black;
        }

        .text p {
            margin: 14pt 0;
        }

        main {
            margin: 5vh 15vw !important;
            padding: 0 0 !important;
        }

        .error {
            height: 20vh;
            padding: 30vh 0;
        }

        /**
            BACKGROUND
        */

        .image {
            width: 100%;
            height: 80vh;
            background-image: linear-gradient(to bottom, transparent, whitesmoke), url("img/<?= $_REQUEST["name"] ?>.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /**
            MAIN BODY
        */

        .desc {
            padding: 0 10vw;
            position: relative;
            top: -30vh;
        }

        .desc h1 {
            font-size: 36pt;
        }

        /**
            VENUE PROPERTIES
        */

        .properties ul {
            list-style-type: none;
            margin: 3.5vh 0;
            padding: 0;

            display: flex;
            justify-content: left;
        }

        .properties li {
            margin-right: 3vw;
        }
    </style>

    <Script>
        $(function() {
            /**
             * CONTACT INFO DIALOGUE
             */
            $("#contact").dialog({
                autoOpen: false,
            });

            // Assigning buttons to open dialogue
            $("#contact-open").on("click", function() {
                $("#contact").dialog("open", "appendTo", "#contact-open");
                $("#contact").parent().position({
                    my: "html",
                    at: "bottom-400%",
                    of: "#contact-open"
                });
            });
            $("#contact-open-2").on("click", function() {
                $("#contact").dialog("open", "appendTo", "#contact-open-2");
                $("#contact").parent().position({
                    my: "html",
                    at: "bottom-400%",
                    of: "#contact-open-2"
                });
            });
        });
    </Script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar" href="#nav">
        <ul>
            <li><a href="wedding.php">Home</a></li>
            <li><a href="wedding.php#about">About Us</a></li>
            <li><a href="wedding.php#filter">Find my Venue</a></li>
            <li><a id="contact-open">Contact Us</a></li>
        </ul>
    </nav>

    <?php

        // If no matching venue, print message and footer
        if (empty($venue)) {
            echo "<div class='center error'>",
                    "<h1>Oops! There's no venue with that name.</h1>",
                    "<button type='submit' onclick='history.back()'>Go Back</button>",
                "</div>",
                "</body>",
                '<footer>
                    <p>Copyright © LOLOLOL 2020</p>
                    <ul>
                        <li>
                            <p>Follow us on</p>
                        </li>
                        <li><a href="https://facebook.com">Facebook</a></li>
                        <li><a href="https://twitter.com">Twitter</a></li>
                        <li><a href="https://instagram.com">Instagram</a></li>
                    </ul>
                </footer>',
                "</html>";
            die();
        }
    ?>

    <!-- Contact Dialogue -->
    <div class="contact" id="contact" title="Contact Us">
        <p>Call us on:
            <span>
                <a href="tel:02003004278">0200 300 4278</a>
            </span>
        </p>
        <br>
        <p>Email us at:
            <span>
                <a href="mailto:hello@wedding.company">hello@wedding.company</a>
            </span>
        </p>
        <br>
        <p>Or alternatively, visit our branch at:</p>
        <p>123 Something St</p>
        <p>Leicester</p>
        <p>LE1 5EX</p>
    </div>

    <main>
        <!-- Background Image -->
        <div class="image"></div>
        <!-- Venue Description Container -->
        <div class="desc">
            <h1><?= $_REQUEST["name"] ?></h1>

            <!-- Venue Details -->
            <div class="properties">
                <ul>
                    <?php




                    // Locale for currency formatting
                    setlocale(LC_MONETARY, "en_GB");

                    // Printing each in ul
                    foreach ($venue as $key => $value) {
                        $ucKey = ucwords($key);

                        // Formatting output
                        if (strpos($key, "price")) {
                            $value = "£ " . number_format(intval($value));
                        }
                        if (strpos($key, "venue")) {
                            $value = intToYesNo($value);
                        }
                        echo "<li><h3>$ucKey:</h3><p>$value</p></li>";
                    }

                    echo "<li><h3>Catering:</h3>";

                    foreach ($catering as $key => $value) {
                        $grade = $value["grade"];
                        $cost = $value["cost"];
                        echo "<p>Class $grade: £ $cost ea.</p>";
                    }

                    echo "</li>";

                    ?>
                </ul>
            </div>

            <!-- Another contact button -->
            <button id="contact-open-2" type="submit" style="float:left">I want this venue!</button>

            <!-- Body, filler text. -->
            <div style="margin-top: 12vh;" class="text">
                <h2 class="center">About the venue</h2>
                <hr>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc justo magna, dignissim in ligula in, vestibulum mattis quam. Vestibulum neque metus, sagittis eget gravida quis, finibus ac justo. In tempus gravida ligula, imperdiet luctus turpis accumsan imperdiet. Curabitur eu quam id augue cursus gravida. Vivamus ac congue velit. Donec non est id risus efficitur tristique vitae sed justo. Aliquam nunc arcu, fringilla at metus eu, condimentum vulputate tortor. Nullam interdum placerat facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In iaculis ac sem volutpat interdum. Suspendisse volutpat ligula mauris, a aliquet enim vehicula ut. In ac feugiat sapien. Mauris sollicitudin ligula nulla, sed vestibulum metus pulvinar nec. Phasellus venenatis, urna ut ornare congue, neque metus imperdiet enim, eu tempus quam libero placerat sem. Aenean tincidunt odio id tincidunt consequat. </p>
                <h2 class="center">The surrounding area</h2>
                <hr>
                <p>Pellentesque euismod volutpat velit vitae hendrerit. Donec feugiat tempor mi sit amet aliquet. Duis sed faucibus ante. Aenean suscipit mauris ligula, vitae lobortis tellus pharetra et. Ut aliquet ligula non quam blandit, vel maximus mauris ornare. Duis viverra risus vitae lorem scelerisque iaculis et vitae urna. Nulla eget tincidunt enim. Nullam ut mollis lorem, varius elementum nibh. Aenean venenatis malesuada elementum. Aliquam erat volutpat. Pellentesque fermentum nibh in mollis ornare. </p>
                <h2 class="center">Included ameneties</h2>
                <hr>
                <p>Duis quis justo molestie, fringilla nisl non, maximus mi. Etiam id rutrum eros, id hendrerit orci. In id neque erat. Aenean nec dui urna. Aenean pretium ipsum a cursus ornare. Nullam non bibendum nibh, id finibus ligula. Donec cursus dignissim pharetra. Vestibulum at imperdiet ante, ac commodo augue. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Quisque tincidunt ultricies ipsum in facilisis. Curabitur nibh massa, rhoncus a ullamcorper ut, laoreet ut ante. Donec et nisi sed libero luctus hendrerit a vitae risus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla ultricies risus libero, et finibus nibh maximus id. </p>
                <p>Aliquam erat volutpat. Proin sit amet orci nunc. Maecenas libero magna, scelerisque sit amet ornare id, finibus nec lacus. Etiam fermentum ut quam viverra feugiat. Vivamus congue, odio id consequat condimentum, nisl erat mattis nunc, sit amet dictum nisl elit vitae leo. Phasellus justo arcu, porta sed lacinia a, iaculis id magna. Phasellus feugiat facilisis quam in euismod. Duis tempus ac elit sit amet tristique. Ut non metus eget neque fringilla tincidunt. Vestibulum ac venenatis ex. </p>
                <p>Praesent auctor risus sed lorem auctor, at lobortis nibh hendrerit. Donec mauris nisi, dapibus ac gravida a, rhoncus vitae sapien. In pellentesque arcu nec venenatis pretium. Aenean sed nisi quis felis mattis facilisis. Sed interdum lacus at massa interdum elementum. Aliquam cursus euismod imperdiet. Nulla id est at felis lacinia consectetur. Aenean at ante sed sapien molestie hendrerit a gravida elit. Morbi et nulla eleifend, viverra sem quis, feugiat arcu. Etiam suscipit ultricies turpis vel fringilla. Suspendisse et ullamcorper lorem. Praesent velit dolor, feugiat at pulvinar vel, mollis at tellus. Cras sit amet bibendum felis, non porta ligula. Duis in ullamcorper massa, ut ultrices mi. Sed congue quam erat, eu sodales ipsum rutrum eu. Nam aliquet mollis lectus eu faucibus. </p>
            </div>
            <br>
            <br>
        </div>
    </main>
    <footer>
        <p>Copyright © LOLOLOL 2020</p>
        <ul>
            <li>
                <p>Follow us on</p>
            </li>
            <li><a href="https://facebook.com">Facebook</a></li>
            <li><a href="https://twitter.com">Twitter</a></li>
            <li><a href="https://instagram.com">Instagram</a></li>
        </ul>
    </footer>
</body>

</html>