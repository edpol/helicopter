<?php
Namespace Takeflite;
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
        $reply = $this->OTA_PkgAvailRQ($client, $PkgAvailRQ);
        $reply->Start = $Start;
        return $reply;
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
        $DepartureDateTime = "";
        // create the variables from the first dimension, so you get arrays assigned to some of the variables
        foreach($parameters as $key => $value){
            $$key = $value;
        }

        $Flight_attributes = array('DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate');
        $l = count($DepartureDateTime); // if there is a Flight there must be a departure time?
        for($i=0; $i<$l; $i++) {
            $Flight = array();
            $prefix = 'Flight_';
            foreach($Flight_attributes as $value){
                if(($value === 'TravelCode') && isset(${$prefix . $value}[$i])) {
                    $Flight[$value] = ${'Flight_' . $value}[$i];
                }
                if(isset($$value)) {
                    $Flight[$value] = $$value[$i];
                }
            }

            $Flight['DepartureAirport']['LocationCode'] = $DepartureAirport_LocationCode[$i];
            $Flight['ArrivalAirport']['LocationCode']   = $ArrivalAirport_LocationCode[$i];
            $Flight['OperatingAirline']['FlightNumber'] = $FlightNumber[$i];

            $ItineraryItem[$i]['Flight'] = $Flight;
        }

        if(isset($PackageRequest_ID))         $PackageRequest['ID'] = $PackageRequest_ID;
        if(isset($PackageRequest_TravelCode)) $PackageRequest['TravelCode'] = $PackageRequest_TravelCode;
        if(isset($Start))                     $PackageRequest['DateRange']['Start'] = $Start;
        if(isset($ItineraryItem))             $PackageRequest['ItineraryItems']['ItineraryItem'] = $ItineraryItem;

        $PkgBookRQ = array();
        if(isset($PackageRequest))            $PkgBookRQ['PackageRequest'] = $PackageRequest;
        if(isset($EchoToken))                 $PkgBookRQ['EchoToken'] = $EchoToken;
        if(isset($UniqueID_ID))               $PkgBookRQ['UniqueID']['ID'] = $UniqueID_ID;

        if(isset($PhoneNumber))               $PkgBookRQ['ContactDetail']['Telephone']['PhoneNumber'] = $PhoneNumber;
        if(isset($Address))                   $PkgBookRQ['ContactDetail']['Address']['_'] = $Address;
        if(isset($Email))                     $PkgBookRQ['ContactDetail']['Email']['_']   = $Email;

        $k = 0;
        $l = count($RPH); // if there is a PassengerListItem tag there must be a passenger count (RPH)
        for($i=0; $i<$l; $i++) {
            $PassengerListItem = array();
            if(isset($RPH[$i]))                    $PassengerListItem['RPH']         = $RPH[$i];
            if(isset($Gender[$i]))                 $PassengerListItem['Gender']      = $Gender[$i];
            if(isset($PassengerListItem_Code[$i])) $PassengerListItem['Code']        = $PassengerListItem_Code[$i];
            if(isset($CodeContext[$i]))            $PassengerListItem['CodeContext'] = $CodeContext[$i];
            if(isset($CodeContext[$i]))            $PassengerListItem['Quantity']    = $Quantity[$i];

            if(isset($GivenName[$i]))              $PassengerListItem['Name']['GivenName']['_']  = $GivenName[$i];
            if(isset($MiddleName[$i]))             $PassengerListItem['Name']['MiddleName']['_'] = $MiddleName[$i];
            if(isset($Surname[$i]))                $PassengerListItem['Name']['Surname']['_']    = $Surname[$i];
            if(isset($NameTitle[$i]))              $PassengerListItem['Name']['NameTitle']['_']  = $NameTitle[$i];

            if(isset($SpecialNeed_Code[$i] )) {
                $PassengerListItem['Name']['SpecialNeed'] = array();
                $j = 0;
                $SpecialNeed = array();
                foreach ($SpecialNeed_Code[$i] as $key => $value) {
                    $SpecialNeed[$j]['Code'] = $key;    // Weight, Allergy
                    $SpecialNeed[$j++]['_'] = $value;   // 98,     Shell Fish
                }
                $PassengerListItem['Name']['SpecialNeed'] = $SpecialNeed;
            }

            $PkgBookRQ['PassengerListItems']['PassengerListItem'][$k++] = $PassengerListItem;
        }

        $PkgBookRQ['PaymentDetails']['PaymentDetail']['PaymentType'] = $PaymentType;

        $parameters = array("PkgBookRQ" => $PkgBookRQ, "Credentials" => $this->Credentials);
        try {
            $response = $client->OTA_PkgBookRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . " in " . __CLASS__ . " at " . __LINE__);
            return false;
        }
        return $response;
    }

}
