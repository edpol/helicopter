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
/*
                <TravelChoices xmlns="http://www.opentravel.org/OTA/2003/05">
                    <TravelItem>
                        <TravelDetail>
                            <OutwardTravel>
                                <AirSegment TravelCode="Dog Sled Tour">
                                    <DepartureAirport LocationCode="JNU" CodeContext="IATA"/>
                                    <ArrivalAirport LocationCode="JNU" CodeContext="IATA"/>
                                    <OperatingAirline/>
                                </AirSegment>
                            </OutwardTravel>
                        </TravelDetail>
                        <Cautions>
                            <Caution Start="2021-05-10T00:00:00" End="2021-08-15T00:00:00" ID="128257"/>
                        </Cautions>
/*
OTA_AirLowFareSearchRQ {
    OTA_AirLowFareSearchRQ AirLowFareSearchRQ;
    CredentialsType Credentials;
}
 */
echo "<hr />\r\n";
echo "<b>SPECIFIC PACKAGE</b>: Here we are using PHP to build XML query and sending it with cURL<br />\r\n";

$EchoToken = "Test";
$Code = "ADT";
$Quantity = "1";

//$ID = "100119";
//$Start = "2020-04-24T00:00:00";
foreach($caution_list as $data) {
    $ID = $data['ID'];
    $Start = $data['Start'];

    $results = $api->specificPackage($EchoToken, $ID, $Code, $Start, $Quantity);
    $OTA_PkgAvailRQResult = $consume->readSpecificPackage($results);

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
?>
</body>
</html>
