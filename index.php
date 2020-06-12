<!DOCTYPE html>
<html lang="en">
<head>
<title>Helicopter</title>
<style>
    body {
        background-color: black;
        color: white;
        font-family: Consolas, serif;
    }
        .box {margin:6px; float:left; padding:5px; border: green solid 1px; border-radius:10px; background-color:#333333;}
</style>
</head>
<body>

<?php
/*
 * XML calls that support gave us
 */

require_once('initialize.php');

echo "<p>" . date('Y-m-d H:i:s') . "</p>\r\n";

/***********************************************************************************************************************
 * Setup to instantiate class SoapClient
 */
$opts = [ 'ssl'=> ['verify_peer'=>false, 'verify_peer_name'=>false ] ];
$context = stream_context_create($opts);
$soapClientOptions = [ 'stream_context' => $context, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE ];
$uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';
$client = new SoapClient( $uri, $soapClientOptions );

// this will list the functions available from Takeflite
echo "<p class='box'>";
echo "Here we are using the PHP class SoapClient to get the available functions from TakeFlite<br />\r\n";
echo "<b>__getFunctions</b><br />\r\n";
$classFunctions = $client->__getFunctions();
foreach($classFunctions as $functions){
    echo $functions . "<br />\r\n";
}
echo "</p>\r\n";
echo "<div style='clear:both;'></div>";

/***********************************************************************************************************************
 * Here I am building the XML file and using cURL to get a response
 */
$results =  $api->allPackages();
$results = str_replace('<', "\r\n<", $results);
$TravelChoices = $consume->readAllPackages($results);

echo "<hr />\r\n";
echo "<b>ALL PACKAGES</b>: Here we are using PHP to build XML query and sending it with cURL<br />\r\n";
echo "Type Returned: <u>" . gettype($TravelChoices) . "</u><br />\r\n";

$caution_list = [];

foreach ($TravelChoices as $TravelItem) {
//    $DepartureAirport = $TravelItem->TravelDetail->OutwardTravel->AirSegment->DepartureAirport->attributes();
//    $ArrivalAirport   = $TravelItem->TravelDetail->OutwardTravel->AirSegment->ArrivalAirport->attributes();
//    $Cautions         = $TravelItem->Cautions
    if (isset($TravelItem->TravelDetail)) {
        echo "<p class='box'>\r\n";
        $AirSegment = $TravelItem->TravelDetail->OutwardTravel->AirSegment;
        foreach ($AirSegment->attributes() as $key => $value) {
            echo "{$key}:: $value <br />\r\n";
            foreach ($AirSegment->children() as $key1 => $value1) {
                echo '     ' . $key1 . ': ';
                $child = $AirSegment->{$key1};
                dumpAttributes("", " = ", $child, false);
                echo "<br />\r\n";
            }
        }
        if (isset($TravelItem->Cautions)) {
            echo "Cautions: <br />\r\n";
            $Cautions = $TravelItem->Cautions;
            foreach ($Cautions->children() as $Caution) {
                $temp = [];
                foreach ($Caution->attributes() as $key4 => $value4) {
                    echo "&nbsp;&nbsp; $key4 = $value4 <br />\r\n";
                    $z = (string) $value4;
                    $temp = array_merge($temp,[$key4=>$z]);
                }
                $caution_list[] = $temp;
            }
        }
        echo "</p>\r\n";
    }
}
echo "<div style='clear:both;'></div>";

/***********************************************************************************************************************
 * Here I am building the XML file using the results from the previous results above and using cURL to get a response
 */
echo "<hr />\r\n";
echo "<b>SPECIFIC PACKAGE</b>: Here we are using PHP to build XML query and sending it with cURL<br />\r\n";

$EchoToken = "Test";
$Code = "ADT";
$Quantity = "1";

//$caution_list is from the results of above call
foreach($caution_list as $data) {
    $ID = $data['ID'];
    $Start = $data['Start'];

    $response = $api->specificPackage($EchoToken, $ID, $Code, $Start, $Quantity);
    $OTA_PkgAvailRQResult = $consume->readSpecificPackage($response);

    echo "<p class='box'>\r\n";
    if (gettype($OTA_PkgAvailRQResult)=="string"){
        echo $OTA_PkgAvailRQResult;
    } elseif (isset($OTA_PkgAvailRQResult->Package)){
        echo "Success: <br />\r\n";
        $Package = $OTA_PkgAvailRQResult->Package;
        dumpAttributes("", ": ", $Package,true);
        $PriceInfo = $Package->PriceInfo;
        dumpAttributes("", ": ", $PriceInfo,true);
        $i = 1;
        foreach($Package->ItineraryItems->children() as $key1 => $ItineraryItem){
            echo "<br />\r\n";
            echo "Itinerary Item #". $i++ . "<br />\r\n";
            $Flight = $ItineraryItem->Flight;
            dumpAttributes("&nbsp;&nbsp;", ": ", $Flight,true);

            foreach($Flight->children() as $key2 => $value2){
                dumpAttributes("&nbsp;&nbsp;", ": ", $value2,true);
            }
        }

    } elseif (isset($OTA_PkgAvailRQResult->Errors)) {
        echo "Errors: <br />\r\n";
        $Errors = $OTA_PkgAvailRQResult->Errors;

        $list = compact("EchoToken", "ID", "Code", "Start", "Quantity");
        foreach ($list as $key => $value) {
            echo "{$key}: {$value} <br />\r\n";
        }

        $error_count = 1;
        foreach ($Errors->children() as $Error) {
            echo "Error #" . $error_count++ . ": <br />\r\n";
            dumpAttributes("&nbsp;&nbsp;", " = ", $Error,true);
        }
    }

    echo "</p>\r\n";
}
echo "<div style='clear:both;'></div>";

/***********************************************************************************************************************
 * Here I am building the XML file and using cURL to get a response
 */
echo "<hr />\r\n";
echo "<b>BOOKING REQUEST</b>: Here we are using PHP to build XML query and sending it with cURL<br />\r\n";

$EchoToken = "BooktestLerry";
$UniqueID = "reference";
$ID = "128256";
$TravelCode = "Icefield Excursion";
$Start = "2021-04-26T16:15:00";
$DepartureDateTime = "2021-04-26T16:15:00";
$ArrivalDateTime = "2021-04-26T17:10:00";
$TravelCodeID = "123925";
$Duration = "55";
$CheckInDate = "2021-04-26T15:45:00";
$DepartureAirport = "Juneau Airport";
$ArrivalAirport = "Juneau Airport";
$FlightNumber = "IE1615";
$Telephone = "123456789x";

$RPH = array("1", "2");
$Gender = array("Male", "Female");
$Code = array("ADT", "ADT");
$CodeContext = array("AQT", "AQT");
$Quantity = array("1", "1");
$GivenName = array("John", "Jane");
$MiddleName = null;
$Surname = array("Doe", "Doe");
$NameTitle = null;
$SpecializedNeed = array( array("Weight"=>98, "Allergies"=>"Peanut"), array("Weight"=>97) );
$SpecializedNeed = array( array("Weight"=>98), array("Weight"=>97) );
$PaymentType = "34";
$Address = null;
$Email = "person@example.com";

$results = $api->bookingRequest($EchoToken, $UniqueID, $ID, $TravelCode, $Start, $DepartureDateTime, $ArrivalDateTime,
    $TravelCodeID, $Duration, $CheckInDate, $DepartureAirport, $ArrivalAirport, $FlightNumber, $Telephone,
    $RPH, $Gender, $Code, $CodeContext, $Quantity, $PaymentType, $Address,
    $Email, $GivenName, $MiddleName, $Surname, $NameTitle , $SpecializedNeed);

$OTA_PkgBookRQResult = $consume->readBookingRequest($results);

    echo "<p class='box'>\r\n";
//FAIL: xml format error
if(isset($OTA_PkgBookRQResult->Reason)){
    foreach($OTA_PkgBookRQResult->Reason as $Fault){
        echo "XML Format Fault: " . $Fault . ": <br />\r\n";
    }
}

// ERROR: didn't like the data sent
if(isset($OTA_PkgBookRQResult->Errors)){
    foreach($OTA_PkgBookRQResult->Errors as $Error){
        echo "Error #" . $error_count++ . ": <br />\r\n";
        dumpAttributes("&nbsp;&nbsp;", " = ", $Error,true);
    }
}

// SUCCESS
if(isset($OTA_PkgBookRQResult->Success)) {
    echo "Success: <br/>\r\n";
    dumpAttributes("&nbsp;&nbsp;", " = ", $OTA_PkgBookRQResult,true);
    dumpAttributes("&nbsp;&nbsp;", ' = ', $OTA_PkgBookRQResult->PackageReservation->UniqueID, true);
}
echo "</p>\r\n";
echo "<div style='clear:both;'></div>";

?>
</body>
</html>
