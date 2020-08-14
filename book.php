<?php
namespace Takeflite;

// didn't press the submit button
//if(!isset($_POST['submit']) || $_POST['submit']!=='submit'){
//    die('How did you get here?');
//}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$api = $request = $output = '';
require_once('initialize.php');

$client = $api->instantiateSoapClient('OTA_PkgBookRQ');

?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Helicopter | Book</title>
    <link rel='icon'       href='favicon.png'>
    <link rel='stylesheet' href='styles.css' type='text/css'>
    <link rel='stylesheet' href='//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css'>
    <script src='//code.jquery.com/jquery-1.10.2.js'></script>
    <script src='//code.jquery.com/ui/1.11.4/jquery-ui.js'></script>
</head>
<body>

<?php

$parameters = $_POST;

try {
    $response = $request->OTA_PkgBookRQ($client, $parameters);
    if(isset($response->OTA_AirLowFareSearchRQ->Success)) {
        echo $output->printList($response);
    }else{
        echo $output->dumpErrors($response->OTA_PkgBookRQResponse);
    }

} catch (\Exception $e) {
    dumpCatch($e, $client, "Hey, Shithead " . __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
}
?>
<script type='text/javascript' src='mysrc.js'></script>
</body>
</html>