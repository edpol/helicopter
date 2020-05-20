<!doctype html>
<html lang="en">
<head>
<title>Helicopter</title>
<style>
    body {
        background-color:black;
        color:white;
        font-family: Arial, serif;
    }
</style>
</head>
<body>
<?php
require_once('initialize.php');

$results =  $api->allPackages();

$results = str_replace('<', "\r\n<", $results);

echo "<p>" . date('Y-m-d h:m:s') . "</p>\r\n";

$consume->read($results);

?>
</body>
</html>
