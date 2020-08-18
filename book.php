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
echo "<br><pre>";
print_r($response);

if(isset($response->Warnings))          { print_r($response->Warnings);          }    // WarningsType
if(isset($response->Success))           { print_r($response->Success);           }    // SuccessType
if(isset($response->Errors))            { print_r($response->Errors);            }    // ErrorsType
if(isset($response->TPA_Extensions))    { print_r($response->TPA_Extensions);    }    // TPA_ExtensionsType
if(isset($response->PackageReservation)){ print_r($response->PackageReservation);}    // PkgReservation

    if(isset($response->OTA_PkgBookRQResult->Success)) {
        echo $output->printList($response, 'OTA_PkgBookRQResult');
    }else{
        echo $output->dumpErrors($response->OTA_PkgBookRQResult);
    }

} catch (\Exception $e) {
    dumpCatch($e, $client, "Hey, Shithead " . __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
}
?>
<script type='text/javascript' src='mysrc.js'></script>
</body>
</html>