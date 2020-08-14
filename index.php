<?php
namespace Takeflite;

/*
 * XML calls that support gave us
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('initialize.php');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Helicopter</title>
        <link rel="icon" href="favicon.png">
        <link href="styles.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    </head>
    <body>

<?php echo "<p>" . date('Y-m-d H:i:s') . "</p>\r\n"; ?>

        <form action="list.php" method="post">

            <p>List All Available Tours</p>

            <p>
                <label for="datepicker" class="label1">Date</label>
                <input type="text"   name="search_date" id="datepicker" /> Try 05/10/2021
            </p>
            <p>
                <label for="quantity"   class="label1">Quantity</label>
                <input type="number" name="Quantity" min="1" id="quantity" value="1" />
            </p>
            <input type="hidden" name="EchoToken"  value="test" />
            <input type="hidden" name="ID"   value="128257" />
            <input type="hidden" name="Code" value="ADT" />
            <p>
                <input type="submit" name="submit" value="submit" class="blue_up" />
            </p>
        </form>
<script type="text/javascript" src="mysrc.js"></script>
    </body>
</html>
