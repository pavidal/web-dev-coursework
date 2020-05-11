<?php

    require "common.php";

    /**
     * validate() function
     * Function used to validate form inputs before processing.
     *
     * @return boolean
     */
    function validate()
    {
        foreach ($_GET as $key => $field) {
            // Checks if all inputs are numeric
            if (!is_numeric($field) && $field != "Submit") {
                type("h1", "All inputs must be numeric.");
                return false;
            }
        }

        if ($_REQUEST["min"] < 5) {
            // alert("Minimum party size must be at least 5.");
            type("h1", "Minimum party size must be at least 5.");
            return false;
        }

        for ($n = 2; $n <= 5; $n++) {
            // Compares costs n-1 and n
            if ($_REQUEST["c" . ($n - 1)] >= $_REQUEST["c" . $n]) {
                // alert("Cost at grade C". ($n - 1) . " is greater than C" . $n);
                type("h1", "Cost at grade C" . ($n - 1) . " is greater than C" . $n);
                return false;
            }
        }

        if ($_REQUEST["min"] > $_REQUEST["max"]) {
            // alert("Party size minimum is greater than maximum.");
            type("h1", "Party size minimum is greater than maximum.");
            return false;
        }

        return true;
    }

    if (!empty($_REQUEST) && validate()) {
        $max = $_REQUEST["max"];
        $min = $_REQUEST["min"];

        // Create a back button
        // echo "<input type='button' onclick='history.back()' value='Back' class='larger'></input>";

        // Populating the first row (header)
        echo    "<table border=1 class='pad'>",
            "<tr class=\"header\">
                        <th>Cost per person →<hr>Party Size ↓</th>";

        for ($i = 1; $i <= 5; $i++) {
            echo    "<th>C", $i, "</br></br>£", $_REQUEST[("c" . $i)], "</th>";
        }
        echo    "</tr>";

        // Populating the table's body
        while ($max >= $min) {
            echo "<tr>";
            echo "<th>", $min, "</th>";     // The first column of each row (party size)

            // Cost of each cell
            for ($col = 1; $col <= 5; $col++) {
                echo "<td>£", ($min * $_REQUEST["c" . $col]), "</td>";
            }

            echo "</tr>";
            $min += 5;  // assume party size increments by 5
        }

        echo "</table>";
    } else {
        die();
    }
?>