<?php
    /**
     * Undocumented function
     *
     * @param [int] $num
     * @return void
     */
    function intToBool($num) {
        return ($num == 1);
    }

    /**
     * Converts integers into "Yes" or "No"
     * 1 = "Yes",
     * 0 = "No",
     * Other numbers = "No".
     *
     * @param [int] $num
     * @return void
     */
    function intToYesNo($num) {
        return (intToBool($num) ? "Yes" : "No");
    }

    /**
     * Converts d/m/Y to ISO date format
     *
     * @param [String] $date
     * @return String
     */
    function dateReformatter($date) {
        try {
            return date_format(date_create_from_format("d/m/Y", $date), "Y-m-d");
        } catch (Exception $e) {
            return date("Y-m-d");
        }
    }

    /**
     * Generates a centered tag with a given message
     *
     * @param [String] $tag
     * @param [String] $msg
     * @return void
     */
    function type($tag = "p", $msg = "Sample Text") {
        echo "<$tag style='text-align: center;'>$msg</$tag>";
    }

    /**
     * Echoes a JS tag to alert with a message from PHP
     *
     * @param [String] $msg
     * @return void
     */
    function alert($msg = "Alert!") {
        echo    "<script type='text/javascript'>
                    alert('$msg');
                </script>";
    }

    /**
     * Echoes a JS tag to go back one page
     *
     * @return void
     */
    function back() {
        echo    "<script type='text/javascript'>
                    history.back();
                </script>";
    }

    // common style for result tables
    echo    "<style>
                .pad td,th {
                    padding: 10px;
                }
            </style>";
?>
