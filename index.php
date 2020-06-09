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

$results =  $api->allPackages();

echo "<p>" . date('Y-m-d H:i:s') . "</p>\r\n";

$results = str_replace('<', "\r\n<", $results);

$TravelChoices = $consume->readAllPackages($results);

echo "<b>ALL PACKAGES</b><br />\r\n";
echo "Type Returned: <u>" . gettype($TravelChoices) . "</u><br />\r\n";

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
                foreach ($child->attributes() as $key2 => $value2) {
                    echo " $key2 = $value2 ";
                }
                echo "<br />\r\n";
            }
        }
        if (isset($TravelItem->Cautions)) {
            echo "Cautions: <br />\r\n";
            $Cautions = $TravelItem->Cautions;
            foreach ($Cautions->children() as $Caution) {
                foreach ($Caution->attributes() as $key4 => $value4) {
                    echo "&nbsp;&nbsp; $key4 = $value4 <br />\r\n";
                }
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
echo "<b>SPECIFIC PACKAGE</b><br />\r\n";
$list = [ "EchoToken"=>"Test", "ID"=>"100119", "Code"=>"ADT", "Start"=>"2020-04-24T00:00:00", "Quantity"=>"1"];
extract($list, EXTR_OVERWRITE);
$results =  $api->specificPackage($EchoToken, $ID, $Code, $Start, $Quantity);
$OTA_PkgAvailRQResult = $consume->readSpecificPackage($results);

echo "<p class='box'>\r\n";

if(isset($OTA_PkgAvailRQResult->Errors)){
    echo "Errors: <br />\r\n";
    $Errors = $OTA_PkgAvailRQResult->Errors;

    foreach($list as $key => $value){
        echo "{$key}: {$value} <br />\r\n";
    }

    $error_count = 1;
    foreach ($Errors->children() as $Error) {
        echo "Error #" . $error_count++ . ": <br />\r\n";
        foreach ($Error->attributes() as $key4 => $value4) {
            echo "&nbsp;&nbsp; $key4 = $value4 <br />\r\n";
        }
    }

}else{
    echo "No Error";
}
echo "</p>\r\n";
echo "<div style='clear:both;'></div>";
?>
</body>
</html>
