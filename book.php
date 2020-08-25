<?php
namespace Takeflite;

// didn't press the submit button
use Exception;

if(!isset($_POST['info']) || $_POST['info']!=='submit'){
    die('How did you get here?');
}

$api = $request = $output = '';
require_once('initialize.php');
$_SESSION['time']['book_initialized'] = time();

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

//echo "<pre style='color:darkseagreen;'>"; print_r($parameters); echo "</pre>";
//echo "<hr>";
        $response = $request->OTA_PkgBookRQ($client, $parameters);
        $_SESSION['time']['book_OTA_PkgBookRQ'] = time();
/*
if(is_bool($response)) {
    echo "OTA_PkgBookRQ returned FALSE<br>";
} else {
    echo "<br><pre>Response: ";
    print_r($response);
    echo "</pre>";
    if (isset($response->Warnings)) {
        print_r($response->Warnings);
    }    // WarningsType
    if (isset($response->Success)) {
        print_r($response->Success);
    }    // SuccessType
    if (isset($response->Errors)) {
        print_r($response->Errors);
    }    // ErrorsType
    if (isset($response->TPA_Extensions)) {
        print_r($response->TPA_Extensions);
    }    // TPA_ExtensionsType
    if (isset($response->PackageReservation)) {
        print_r($response->PackageReservation);
    }    // PkgReservation
}
echo $output->printList($response, 'OTA_PkgBookRQResult', $parameters);
*/

        if(isset($response->OTA_PkgBookRQResult->Success)) {
            echo $output->printList($response, 'OTA_PkgBookRQResult', $parameters);
        }else{
            echo $output->dumpErrors($response->OTA_PkgBookRQResult);
        }
        $_SESSION['time']['book_printList'] = time();

    } catch (Exception $e) {
        dumpCatch($e, $client, "book.php " . __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
    }
    $_SESSION['time']['book_End'] = time();
    dumpTimeStamps($_SESSION['time']);
?>
<script type='text/javascript' src='mysrc.js'></script>
</body>
</html>