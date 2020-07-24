<?php
namespace Takeflite;
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

<?php
    // didn't press the submit button
    if(!isset($_POST['submit']) || $_POST['submit']!=='submit'){
        die('How did you get here?');
    }

    // Can't pick dates in the past. if input was not empty and the date supplied is less than today, error
    $target_seconds = strtotime($_POST['search_date']);
    $cutoff = strtotime(" today ");
    if($target_seconds<$cutoff && !empty($_POST['search_date']) ){
        echo 'Can\'t lookup days in the past. Cutoff date is today: ' .  strftime("%m/%d/%Y", $cutoff);
        echo "<a href='index.php'>Back</a>";
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $api = $request = null;
    require_once('initialize.php');

    $client = $api->instantiateSoapClient();

    try{
        // get all packages or just one
        if(empty($_POST['search_date']) ) {
            $response = $request->AllPackages($client);
            $output_format = "AllPackages";
        }else{
            $Start = date("Y-m-d H:i:s.u", strtotime($_POST['search_date']));  // change the local date to gmt ????
            $Code = $_POST['Code'];
            $Quantity = $_POST['Quantity'];
            $response = $request->PackagesSpecificDate($client, $_POST['EchoToken'], $_POST['ID'], $Start, $Code, $Quantity);
            $output_format = "PackagesSpecificDate";
        }

        // if the call worked display results, else display error message
        if(isset($response->OTA_PkgAvailRQResult->Success)) {
            if($output_format === "AllPackages") { echo printAllPkgs($response); }
            elseif($output_format === "PackagesSpecificDate") { echo printPkgList($response, $Start); }
            else {die("Problem selecting report");}
        }else{
            dumpErrors($response->OTA_PkgAvailRQResult);
        }

    } catch(\Exception $e) {
    //    trigger_error("SOAP Fault: (faultcode: {$e->faultcode}, faultstring: {$e->faultstring})", E_USER_ERROR);
        echo "<pre>test8: <br>";
        dumpCatch($e, $client);
        echo "</pre>";
    }
?>
    </body>
</html>
