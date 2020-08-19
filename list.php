<?php
/*
 *  Here we are going to submit the SOAP call OTA_PkgAvailRQ to get all available packages, or a specific date
 *  Then we call printList.  There the user can choose a specific tour.
 */
namespace Takeflite;

// didn't press the submit button
if(!isset($_POST['submit']) || $_POST['submit']!=='submit'){
    die('How did you get here?');
}

include('error_reporting.php');

$api = $request = $output = "";
require_once('initialize.php');
$_SESSION['time']['list_initialized'] = time();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Helicopter | List</title>
        <link rel="icon" href="favicon.png">
        <link href="styles.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    </head>
    <body>

<?php
    // Can't pick dates in the past. if input was not empty and the date supplied is less than today, error
    $target_seconds = strtotime($_POST['search_date']);
    $cutoff = strtotime(" today ");
    if($target_seconds<$cutoff && !empty($_POST['search_date']) ){
        echo 'Can\'t lookup days in the past. Cutoff date is today: ' .  strftime("%m/%d/%Y", $cutoff) . "<br>\r\n";
        echo "<a href='index.php'>Back</a>";
        die();
    }

    $client = $api->instantiateSoapClient('OTA_PkgAvailRQ');
    try{
        // get all packages or just packages for one date
        if(empty($_POST['search_date']) ) {
            $response = $request->AllPackages($client);
            $_SESSION['time']['list_All_Packages'] = time();
            $output_format = "AllPackages";
        }else{
            $Start = date("Y-m-d H:i:s.u", strtotime($_POST['search_date']));  // change the local date to gmt ????
            $Code = $_POST['Code'];
            $Quantity = $_POST['Quantity'];
            $response = $request->PackagesSpecificDate($client, $_POST['EchoToken'], $_POST['ID'], $Start, $Code, $Quantity);
            $_SESSION['time']['list_Specific_date'] = time();
//print "<pre>";
//echo "PackageRequest_ID: "; print_r($response->OTA_PkgAvailRQResult->Package->ID); echo "<br>";
//echo "PackageRequest_TravelCode: "; print_r($response->OTA_PkgAvailRQResult->Package->TravelCode); echo "<br>";
//die();
            $output_format = "PackagesSpecificDate";
            $response->OTA_PkgAvailRQResult->Quantity = $Quantity;
            $response->OTA_PkgAvailRQResult->Start = $Start;
        }

        // if the call worked display results, else display error message
        if(isset($response->OTA_PkgAvailRQResult->Success)) {
            echo $output->printList($response, $output_format);
            $_SESSION['time']['list_print'] = time();
        }else{
            echo $output->dumpErrors($response->OTA_PkgAvailRQResult);
        }

    } catch(\Exception $e) {
    //    trigger_error("SOAP Fault: (faultcode: {$e->faultcode}, faultstring: {$e->faultstring})", E_USER_ERROR);
        echo "<pre>test8: <br>";
        dumpCatch($e, $client);
        echo "</pre>";
    }
?>
<script type="text/javascript" src="mysrc.js"></script>
    </body>
</html>
