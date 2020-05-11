<!-- Use code folding extensively if you don't want to have a bad time. -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Some Wedding Website</title>

    <!-- Importing Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Playfair+Display:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Main stylesheet for this page -->
    <link rel="stylesheet" type="text/css" href="main.css">

    <!-- Importing JS Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script type="text/javascript">
        /**
         * ASYNC FUNCTION
         * Function for JQuery UI widget creation
         */
        $(function() {

            // Load popular venues cards from PHP
            $("#venue-cards").load("ajax/popularvenues.php");


            /**
             * SEARCH BAR AUTO-COMPLETE
             */

            // Auto-complete hint tooltip
            $("#search").tooltip({
                track: true,        // Tracks mouse
                show: {
                    delay: 1000
                }
            });

            // array of auto-complete values
            var availableTags = [];

            //Fetching Venues from DB with AJAX
            $.getJSON("ajax/venuenames.php", function(res) {
                // On completion...

                $.each(res, function(i, field) {

                    // appends each venue's name to array
                    availableTags.push(field.name);
                });
                console.log("Venue names for auto complete");
                console.log(availableTags);

                // set text input to have auto-complete
                $("#search").autocomplete({
                    source: availableTags,
                    minLength: 1
                });

                // Get a random venue name and add it to placeholder for suggestion
                // A range cannot be specified for random so a bodge is needed
                var randVen = availableTags[Math.floor(Math.random() * (availableTags.length - 1))];
                $("#search").prop("placeholder", ("Search venues (e.g. " + randVen + ")"));
            });


            /**
             * SEARCH BUTTON
             */
            $("#search-button").on("click", function() {
                var name = $("#search").val();

                // Go to page for venue's description
                window.location.href = "viewvenue.php?name=" + name;
            });


            /**
             * CONTACT INFO DIALOGUE
             */
            $("#contact").dialog({
                autoOpen: false,
            });
            $("#contact-open").on("click", function() {
                $("#contact").dialog("open", "appendTo", "#contact-open");

                // Position to be under button
                $("#contact").parent().position({
                    my: "html",
                    at: "bottom-400%",
                    of: "#contact-open"
                });
            });


            /**
             * DATEPICKERS
             */
            var fromDate = $("#datepicker").datepicker({
                // Minimum date is today
                minDate: 0,
                firstDay: 1
            }).on("change", function() {
                // When selection changes...
                toDate.datepicker("option", {
                    // Change to date's minimum to selected from date
                    minDate: $(this).datepicker("getDate")
                });

                // Prevents date range selection when not intended
                if (!$("#checkbox-1").prop("checked")) {
                    toDate.datepicker("setDate", $(this).datepicker("getDate"));
                }
            });

            // Identical to above but in reverse
            // Second Date picker for range
            var toDate = $("#another-datepicker").datepicker({
                minDate: 0,
                firstDay: 1
            }).on("change", function() {
                fromDate.datepicker("option", {
                    maxDate: $(this).datepicker("getDate")
                });
            });

            // Set flexible dates to false
            $("#checkbox-1").prop("checked", false);
            $("#another-datepicker").hide(); // Hide 2nd date picker
            $("#dp-break").hide(); //spacing

            $("#checkbox-1").change(function() {
                // toggle 2nd datepicker on checkbox change
                $("#dp-break").toggle();
                $("#another-datepicker").toggle();
                window.location.replace("#focus-datepicker"); // focus on datepicker

                // Reset min/max dates
                $("#datepicker").datepicker("option", "maxDate", null);
                $("#another-datepicker").datepicker("option", "minDate", 0);

                // Set second datepicker value to the first selection
                $("#another-datepicker").datepicker("setDate", $("#datepicker").datepicker("getDate"));
            });


            /**
             * PARTY SLIDER CREATION
             */
            $("#party-slider").slider({
                create: function() {
                    // Show value on the handle
                    $("#party-value").text($(this).slider("value"));
                },
                range: "min",
                value: 50,
                min: 10,
                max: 1000,
                step: 10,
                slide: function(event, ui) {
                    // Update handle to current value
                    $("#party-value").text(ui.value);
                }
            });

            // Setting themes for checkboxes and radio buttons
            $(".checkbox").checkboxradio();
            $("input[name='Catering']").first().prop("checked", true);
            $(".radio").checkboxradio();

            $("#results").hide();


            /**
             * FORM SUBMISSION
             *
             * No validation needed. All inputs have defaults and are restricted.
             */
            $("#submit").on("click", function() {

                // Date objects
                var objFrom = $("#datepicker").datepicker("getDate");
                var objTo = $("#another-datepicker").datepicker("getDate");

                // ISO date Strings
                var dateFrom = $.datepicker.formatDate("yy-mm-dd", objFrom);
                var dateTo = $.datepicker.formatDate("yy-mm-dd", objTo);

                // Date is flexible?
                var flexDates = $("#checkbox-1").prop("checked");

                var partySize = $("#party-value").text();

                // Catering Grade, get value from radio's label
                var catering = $("input[name='Catering']:checked").val();

                $("#results").load("ajax/filtersearch.php", {
                    from: dateFrom,
                    to: dateTo,
                    flex: flexDates,
                    size: partySize,
                    cater: catering
                }, function() {
                    // on completion:
                    $("#results").show();
                    window.location.replace("#results"); // focus on results
                });

            });
        });
    </script>
</head>

<body id="home">
    <!-- Hero Banner, containing navbar, hero image, and venue search. -->
    <div class="header" ref="#home">
        <!-- Navigation Bar -->
        <nav class="navbar" id="navbar" href="#nav">
            <!-- Using JS for redirect with replace() prevents buildup of history -->
            <!-- Contact uses JQuery UI dialogue -->
            <ul>
                <li><a>Home</a></li>
                <li><a onclick="window.location.replace('#about')">About Us</a></li>
                <li><a onclick="window.location.replace('#filter')">Find Me a Venue</a></li>
                <li><a id="contact-open">Contact Us</a></li>
            </ul>
        </nav>

        <!-- Header content -->
        <div class="header-text">
            <h1 class="">Your Wedding. Planned to Perfection.</h1>

            <!-- Venue Search -->
            <div>
                <input type="text" name="Venue Search" id="search" placeholder="Search venues" title="Enter at least one letter for suggestions.">
                <button type="search" id="search-button">Search</button>
            </div>
        </div>
    </div>

    <!-- Contact dialogue, shown when "contact" (navbar) is clicked -->
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

    <main class="center">

        <!-- First Section -->
        <h1 class="" style="font-size : 32pt;">
            Discover your wedding style
        </h1>
        <hr>
        <p class="" style="font-size: 14pt; margin: 5vh 2vw;">
            Be certain that you will have the wedding of your dreams from our selection of handpicked wedding venues. Our selection of venues are guaranteed to have quality certified service or your money back.
        </p>

        <!-- Second Section, Venue Cards. -->
        <h1>
            All-time popular venues
        </h1>
        <hr>
        <!-- This gets loaded with AJAX from a PHP script ajax/popularvenues.php -->
        <div id="venue-cards"></div>

        <!-- Third Section, About. -->
        <div id="about" class="about p-format ">
            <h1>About Us</h1>
            <hr>
            <p>Wedding company has hosted thousands of beautiful wedding caring about the quality we bring for not just the venue but for all aspects of your wedding. We believe that your special day deserves the highest standard of perfection.</p>
            <h2>With over 30 years of experience and well established partners we are confident we can deliver the best day of your life.</h2>
            <p>Our venues are also open to all other related wedding events to fit your needs.</p>
        </div>
        <hr>

        <!-- Fourth Section, Search Form and Results. -->
        <!-- Heavy use of JQuery UI -->
        <!-- I'm so sorry about this mess. -->
        <div>
            <h1 class="">Need to be more specific?</h1>
            <h2 class="">
                Filter search our venues.
                Tell us your needs and we'll give you suggestions.
            </h2>
            <br>
            <div>
                <div class="filter" id="filter">
                    <!-- Form for filter-searching -->
                    <div class="dates">
                        <!-- Datepickers -->
                        <label for="datepicker">Date: </label>
                        <div id="datepicker" name="datepicker"></div>
                        <br id="dp-break">
                        <div id="another-datepicker"></div>
                        <div id="focus-datepicker"></div>
                        <br>
                    </div>
                    <div class="other-fields widget">
                        <div class="inputs">
                            <!-- Flexible dates, shows a second date picker for date range -->
                            <label for="checkbox-1" checked="false">My dates are flexible</label>
                            <input type="checkbox" name="flexdates" id="checkbox-1" class="checkbox">
                        </div>
                        <div class="inputs">
                            <!-- Party Size Slider -->
                            <label for="party">Party Size</label>
                            <div id="party-slider" name="party" style="margin: 1vh 0;">
                                <div id="party-value" class="ui-slider-handle"></div>
                            </div>
                        </div>
                        <div class="inputs">
                            <!-- Catering class radio group -->
                            <p>Select a catering class:</p>
                            <label for="radio-6">Any</label>
                            <input type="radio" name="Catering" id="radio-6" class="radio" value="Any">
                            <label for="radio-1">C1</label>
                            <input type="radio" name="Catering" id="radio-1" class="radio" value="1">
                            <label for="radio-2">C2</label>
                            <input type="radio" name="Catering" id="radio-2" class="radio" value="2">
                            <label for="radio-3">C3</label>
                            <input type="radio" name="Catering" id="radio-3" class="radio" value="3">
                            <label for="radio-4">C4</label>
                            <input type="radio" name="Catering" id="radio-4" class="radio" value="4">
                            <label for="radio-5">C5</label>
                            <input type="radio" name="Catering" id="radio-5" class="radio" value="5">
                        </div>
                        <button type="submit" id="submit">Find Venues</button>
                    </div>
                </div>
                <!-- Results table, loaded with AJAX from ajax/filersearch.php -->
                <div id="results"></div>
            </div>
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