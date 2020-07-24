<pre>
<?php

$EchoToken = "?";
$DepartureDateTime = "08/July/2020";
$LocationCodeOrigin = "WLG";
$CodeContextOrigin = "TF";
$LocationCodeDestination = "NSN";
$CodeContextDestination = "TF";
$Code = array();
$Code[0] = "ADT";
$Quantity[0] = "1";
$Code[1] = "8";
$Quantity[1] = "1";

$PassengerTypeQuantity = array();
$l = count($Code);
for ($i = 0; $i < $l; $i++) {
    $PassengerTypeQuantity[] = array("Code" => $Code[$i], "Quantity" => $Quantity[$i]);
}

echo "\$PassengerTypeQuantity: ";
print_r($PassengerTypeQuantity);

$AirLowFareSearchRQ = array(
    "EchoToken" => $EchoToken,
    "OriginDestinationInformation" => array(
        "DepartureDateTime" => array("_" => $DepartureDateTime),
        "OriginLocation" => array(
            "LocationCode" => $LocationCodeOrigin,
            "CodeContext" => $CodeContextOrigin
        ),
        "DestinationLocation" => array(
            "LocationCode" => $LocationCodeDestination,
            "CodeContext" => $CodeContextDestination
        ),
    ),

    "TravelerInfoSummary" => array(
        "AirTravelerAvail" => array(
            "PassengerTypeQuantity" =>  $PassengerTypeQuantity
        )
    )
);
echo "<hr>";
echo "AirLowFareSearchRQ: ";
print_r($AirLowFareSearchRQ);
