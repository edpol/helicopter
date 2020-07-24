<?php
namespace Takeflite;
/*
 * all the calls to the api
 * If all the strings in the array are single quote all the tags become lower case
 * with just one exception they keep their case
 */
class Request {
    private $Credentials;

    public function __construct($AgentLogin, $AgentPassword, $ServiceId)
    {
        $this->Credentials = array(
            "AgentLogin" => $AgentLogin,
            "AgentPassword" => $AgentPassword,
            "ServiceId" => $ServiceId
        );
    }

    //  [0] => OTA_AirLowFareSearchRQResponse OTA_AirLowFareSearchRQ(OTA_AirLowFareSearchRQ $parameters)
    public function OTA_AirLowFareSearchRQ($client, $EchoToken, $Code, $Quantity, $DepartureDateTime,
           $LocationCodeOrigin, $CodeContextOrigin, $LocationCodeDestination, $CodeContextDestination)
    {
        $PassengerTypeQuantity = array();
        $l = count($Code);
        for ($i = 0; $i < $l; $i++) {
            $PassengerTypeQuantity[] = array("Code" => $Code[$i], "Quantity" => $Quantity[$i]);
        }

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

        $parameters = array("AirLowFareSearchRQ" => $AirLowFareSearchRQ, "Credentials" => $this->Credentials);
        try {
            $response = $client->OTA_AirLowFareSearchRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . " in " . __CLASS__ . " at " . __LINE__);
            return false;
        }
        return $response;
    }

    //  [1] => OTA_AirBookRQResponse OTA_AirBookRQ(OTA_AirBookRQ $parameters)
    public function OTA_AirBookRQ($client, $parameters)
    {

    }

    //  [2] => OTA_AirScheduleRQResponse OTA_AirScheduleRQ(OTA_AirScheduleRQ $parameters)
    public function OTA_AirScheduleRQ($client, $EchoToken, $DepartureDateTime, $OriginLocation)
    {
        $AirScheduleRQ = array(
            "EchoToken"      => $EchoToken,
            "OriginDestinationInformation" => array(
                "DepartureDateTime" => array( "_" => $DepartureDateTime),
                "OriginLocation" => array( "_" => $OriginLocation),
            )
        );

        $parameters = array("AirScheduleRQ" => $AirScheduleRQ, "Credentials" => $this->Credentials);
        try {
            $response = $client->OTA_AirScheduleRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . " in " . __CLASS__ . " at " . __LINE__);
            return false;
        }
        return $response;
    }

    //  [3] => OTA_AirFlifoRQResponse OTA_AirFlifoRQ(OTA_AirFlifoRQ $parameters)
    public function OTA_AirFlifoRQ($client, $parameters)
    {

    }

    //  [4] => OTA_AirBookModifyRQResponse OTA_AirBookModifyRQ(OTA_AirBookModifyRQ $parameters)
    public function OTA_AirBookModifyRQ($client, $parameters)
    {

    }

    //  [5] => OTA_ReadRQResponse OTA_ReadRQ(OTA_ReadRQ $parameters)
    public function OTA_ReadRQ($client, $parameters)
    {

    }

    //  [6] => OTA_ReadProfileRQResponse OTA_ReadProfileRQ(OTA_ReadProfileRQ $parameters)
    public function OTA_ReadProfileRQ($client, $parameters)
    {

    }

    //  [7] => OTA_PkgAvailRQResponse OTA_PkgAvailRQ(OTA_PkgAvailRQ $parameters)
    public function OTA_PkgAvailRQ($client, $PkgAvailRQ)
    {
        $parameters = array("PkgAvailRQ" => $PkgAvailRQ, "Credentials" => $this->Credentials);
        try {
            $response = $client->OTA_PkgAvailRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . " in " . __CLASS__ . " at " . __LINE__);
            return false;
        }
        return $response;
    }

    //  [7a]
    public function PackagesSpecificDate($client, $EchoToken, $ID="", $Start="", $Code="", $Quantity="")
    {
        $PkgAvailRQ = array(
            "EchoToken"      => $EchoToken,
            "PackageRequest" => array(
                "ID" => $ID,
                "DateRange" => array(
                    "Start" => $Start
                ),
            ),
            "CustomerCounts" => array(
                "CustomerCount" => array( "Code"=>$Code, "Quantity"=>$Quantity)
            )
        );
        return $this->OTA_PkgAvailRQ($client, $PkgAvailRQ);
    }

    //  [7b]
    public function AllPackages($client, $EchoToken="test", $TravelCode="*")
    {
        $PkgAvailRQ = array(
            "EchoToken"      => $EchoToken,
            "PackageRequest" => array(
                "TravelCode" => $TravelCode
            )
        );
        return $this->OTA_PkgAvailRQ($client, $PkgAvailRQ);
    }

    //  [8] => OTA_PkgBookRQResponse OTA_PkgBookRQ(OTA_PkgBookRQ $parameters)
    public function OTA_PkgBookRQ($client, $parameters)
    {
        $PassengerListItem = array();
        $l = count($RPH);
        for($i=0; $i<$l; $i++) {
            $PassengerListItem[] = array(
                "RPH" => "1", "Gender" => "Unknown", "Code" => "ADT", "CodeContext" => "AQT", "Quantity" => "1",
                "Name" => array(
                    "GivenName"    => array("_" => "John"),
                    "MiddleName"  => array("_" => "G"),
                    "Surname"     => array("_" => "Doe"),
                    "NameTitle"   => array("_" => "Mr"),
                    "SpecialNeed" => array("Code" => "Weight", "_" => "98")
                )
            );
        }

        $PkgBookRQ = array(
            "EchoToken" => "BooktestLerry",
            "UniqueID" => array("ID" => "Reference"),
            "PackageRequest" => array(
                "ID" => "128256",
                "TravelCode" => "Icefield Excursion",
                "DateRange" => array(
                    "Start" => "2021-04-26T16:15:00"
                ),
                "ItineraryItems" => array(
                    "ItineraryItem" => array(
                        "Flight" => array(
                            "DepartureDateTime" => "2021-04-26T16:15:00",
                            "ArrivalDateTime" => "2021-04-26T17:10:00",
                            "TravelCode" => "123925",
                            "Duration" => "55",
                            "CheckInDate" => "2021-04-26T15:45:00",
                            "DepartureAirport" => array("LocationCode" => "Juneau Airport"),
                            "ArrivalAirport"   => array("LocationCode" => "Juneau Airport"),
                            "OperatingAirline" => array("FlightNumber" => "IE1615"),
                        )
                    )
                )
            ),
            "ContactDetail" => array(
                "Telephone" => array("PhoneNumber" => "123456789x"),
                "Email"     => array("_" => "person@example.com"),
            ),
            "PassengerListItems" => array(
                "PassengerListItem" => array(
                    "RPH" => "1", "Gender" => "Unknown", "Code" => "ADT", "CodeContext" => "AQT", "Quantity" => "1",
                    "Name" => array(
                        "GivenName"  => array("_" => "John"),
                        "MiddleName" => array("_" => "G"),
                        "Surname"    => array("_" => "Doe"),
                        "NameTitle"  => array("_" => "Mr"),
                        "SpecialNeed"=> array( "Code" => "Weight", "_" => "98")
                    )
                ),
                "PassengerListItem" => array(
                    "RPH" => "2", "Gender" => "Unknown", "Code" => "ADT", "CodeContext" => "AQT", "Quantity" => "1",
                    "Name" => array(
                        "GivenName"  => array("_" => "Jane"),
                        "MiddleName" => array("_" => "G"),
                        "Surname"    => array("_" => "Doe"),
                        "NameTitle"  => array("_" => "Mrs"),
                        "SpecialNeed"=> array( "Code" => "Weight", "_" => "97")
                    )
                )
	        ),
            "PaymentDetails" => array(
                "PaymentDetail" => array(
                    "PaymentType" => "34"
                )
            ),
        );
    }



}
