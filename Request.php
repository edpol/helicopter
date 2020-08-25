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
            'AgentLogin' => $AgentLogin,
            'AgentPassword' => $AgentPassword,
            'ServiceId' => $ServiceId
        );
    }

    //  [0] => OTA_AirLowFareSearchRQResponse OTA_AirLowFareSearchRQ(OTA_AirLowFareSearchRQ $parameters)
    public function OTA_AirLowFareSearchRQ($client, $parameters) {
        /*
            These were the parameters used in the example given by Takeflite
            $parameter = array('EchoToken'=>$EchoToken, 'Code'=>$Code, 'Quantity'=>$Quantity,
                'DepartureDateTime'=>$DepartureDateTime, // dd/mmm/yyyy
                'OriginLocation_LocationCode'=>$OriginLocation_LocationCode,
                'OriginLocation_CodeContext'=>$OriginLocation_CodeContext,
                'DestinationLocation_LocationCode'=>$DestinationLocation_LocationCode,
                'DestinationLocation_CodeContext'=>$DestinationLocation_CodeContext,
                'Code'=>$Code, 'Quantity'=>$Quantity  // these 2 are arrays
            );
        */

        $EchoToken = $Code = $Quantity = $DepartureDateTime = $OriginLocation_LocationCode = $OriginLocation_CodeContext = $DestinationLocation_LocationCode = $DestinationLocation_CodeContext = $Code = $Quantity = '';

        foreach($parameters as $key => $value){
            $$key = $value;
        }

        $PassengerTypeQuantity = array();
        $l = count($Quantity);
        for ($i = 0; $i < $l; $i++) {
            $PassengerTypeQuantity[] = array('Code' => $Code[$i], 'Quantity' => $Quantity[$i]);
        }

        $AirLowFareSearchRQ = array(
            'EchoToken' => $EchoToken,
            'OriginDestinationInformation' => array(
                'DepartureDateTime' => array('_' => $DepartureDateTime),
                'OriginLocation' => array(
                    'LocationCode' => $OriginLocation_LocationCode,
                    'CodeContext'  => $OriginLocation_CodeContext
                ),
                'DestinationLocation' => array(
                    'LocationCode' => $DestinationLocation_LocationCode,
                    'CodeContext'  => $DestinationLocation_CodeContext
                ),
            )
        );

        $AirLowFareSearchRQ['TravelerInfoSummary']['AirTravelerAvail']['PassengerTypeQuantity'] = $PassengerTypeQuantity;
        try {
            $response = $client->OTA_AirLowFareSearchRQ(array('AirLowFareSearchRQ' => $AirLowFareSearchRQ, 'Credentials' => $this->Credentials));
        } catch (\Exception $e) {
            dumpCatch($e, $client, "Hey, Shithead " . __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
            $response = false;
        }
        return $response;
    }

    //  [1] => OTA_AirBookRQResponse OTA_AirBookRQ(OTA_AirBookRQ $parameters)
    public function OTA_AirBookRQ($client, $parameters)
    {
        foreach($parameters as $key => $value){
            $$key = $value;
        }
    }

    //  [2] => OTA_AirScheduleRQResponse OTA_AirScheduleRQ(OTA_AirScheduleRQ $parameters)
    public function OTA_AirScheduleRQ($client, $EchoToken, $DepartureDateTime, $OriginLocation)
    {
        $AirScheduleRQ = array(
            'EchoToken' => $EchoToken,
            'OriginDestinationInformation' => array(
                'DepartureDateTime' => array( '_' => $DepartureDateTime),
                'OriginLocation' => array( '_' => $OriginLocation),
            )
        );

        $parameters = array('AirScheduleRQ' => $AirScheduleRQ, 'Credentials' => $this->Credentials);
        try {
            $response = $client->OTA_AirScheduleRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
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
        $parameters = array('PkgAvailRQ' => $PkgAvailRQ, 'Credentials' => $this->Credentials);
        try {
            $response = $client->OTA_PkgAvailRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
            return false;
        }
        return $response;
    }

    //  [7a]
    public function PackagesSpecificDate($client, $EchoToken, $ID='', $Start='', $Code='', $Quantity='')
    {
        $PkgAvailRQ = array(
            'EchoToken'      => $EchoToken,
            'PackageRequest' => array(
                'ID' => $ID,
                'DateRange' => array(
                    'Start' => $Start
                ),
            ),
            'CustomerCounts' => array(
                'CustomerCount' => array( 'Code'=>$Code, 'Quantity'=>$Quantity)
            )
        );
        return $this->OTA_PkgAvailRQ($client, $PkgAvailRQ);
    }

    //  [7b]
    public function AllPackages($client, $EchoToken='test', $TravelCode='*')
    {
        $PkgAvailRQ = array(
            'EchoToken'      => $EchoToken,
            'PackageRequest' => array(
                'TravelCode' => $TravelCode
            )
        );
        return $this->OTA_PkgAvailRQ($client, $PkgAvailRQ);
    }

    //  [8] => OTA_PkgBookRQResponse OTA_PkgBookRQ(OTA_PkgBookRQ $parameters)
    public function OTA_PkgBookRQ($client, $parameters)
    {
        $DepartureDateTime = $DepartureAirport_LocationCode = $ArrivalAirport_LocationCode = $FlightNumber = $RPH = $PassengerListItem_Quantity = $SpecialNeed = $PaymentType = array();
        // create the variables from the first dimension, so you get arrays assigned to some of the variables
        foreach($parameters as $key => $value){
            $$key = $value;
        }

        $PkgBookRQ = array();
        if(isset($EchoToken))                 $PkgBookRQ['EchoToken']       = $EchoToken;
        if(isset($UniqueID_ID))               $PkgBookRQ['UniqueID']['ID']  = $UniqueID_ID;
        if(isset($PackageRequest_ID))         $PackageRequest['ID']         = $PackageRequest_ID;
        if(isset($PackageRequest_TravelCode)) $PackageRequest['TravelCode'] = $PackageRequest_TravelCode;
        if(isset($Start))                     $PackageRequest['DateRange']['Start'] = $Start;

        $Flight_attributes = array('DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate');
        $l = count($DepartureDateTime); // if there is a Flight there must be a departure time?
        for($i=0; $i<$l; $i++) {
            $Flight = array();
            $prefix = 'Flight_';
            foreach($Flight_attributes as $value){
                if(($value === 'TravelCode') && isset(${$prefix . $value}[$i])) {
                    $Flight[$value] = ${$prefix . $value}[$i];
                }
                if(isset($$value)) {
                    $Flight[$value] = $$value[$i];
                }
            }
            $Flight['DepartureAirport']['LocationCode'] = $DepartureAirport_LocationCode[$i];
            $Flight['ArrivalAirport']['LocationCode']   = $ArrivalAirport_LocationCode[$i];
            $Flight['OperatingAirline']['FlightNumber'] = $FlightNumber[$i];

            $ItineraryItems[$i]['ItineraryItem']['Flight'] = $Flight;
        }
        if(isset($ItineraryItems))             $PackageRequest['ItineraryItems'] = $ItineraryItems;

        if(isset($PackageRequest))            $PkgBookRQ['PackageRequest'] = $PackageRequest;

        if(isset($PhoneNumber)){
            foreach($PhoneNumber as $key => $num){
                $PkgBookRQ['ContactDetail']['Telephone'][$key]['PhoneNumber'] = $PhoneNumber[$key];
            }
        }
        if(isset($Email)) {
            foreach($Email as $key => $num) {
                $PkgBookRQ['ContactDetail']['Email'][$key]['_'] = $Email[$key];
            }
        }
        if(isset($Address)) {
            foreach($Address as $key => $num) {
                $PkgBookRQ['ContactDetail']['Address'][$key]['_'] = $Address[$key];
            }
        }

        $k = 0;
        $l = count($RPH); // if there is a PassengerListItem tag there must be a passenger count (RPH)
        for($i=0; $i<$l; $i++) {
            $PassengerListItem = array();
            if(isset($RPH[$i]))                    $PassengerListItem['RPH']         = $RPH[$i];
            if(isset($Gender[$i]))                 $PassengerListItem['Gender']      = $Gender[$i];
            if(isset($PassengerListItem_Code[$i])) $PassengerListItem['Code']        = $PassengerListItem_Code[$i];
            if(isset($CodeContext[$i]))            $PassengerListItem['CodeContext'] = $CodeContext[$i];
            if(isset($CodeContext[$i]))            $PassengerListItem['Quantity']    = $PassengerListItem_Quantity[$i];

            if(isset($GivenName[$i]))              $PassengerListItem['Name']['GivenName']['_']  = $GivenName[$i];
            if(isset($MiddleName[$i]))             $PassengerListItem['Name']['MiddleName']['_'] = $MiddleName[$i];
            if(isset($Surname[$i]))                $PassengerListItem['Name']['Surname']['_']    = $Surname[$i];
            if(isset($NameTitle[$i]))              $PassengerListItem['Name']['NameTitle']['_']  = $NameTitle[$i];

            $PassengerListItem['Name']['SpecialNeed'] = $SpecialNeed[$i];

            $PkgBookRQ['PassengerListItems']['PassengerListItem'][$k++] = $PassengerListItem;
        }

        $PkgBookRQ['PaymentDetails']['PaymentDetail']['PaymentType'] = $PaymentType;
print "<pre>"; print_r($PkgBookRQ); print "</pre>";
die();
        $parameters = array('PkgBookRQ' => $PkgBookRQ, 'Credentials' => $this->Credentials);

        try {
            $response = $client->OTA_PkgBookRQ($parameters);
        } catch (\Exception $e) {
            dumpCatch($e, $client, __FUNCTION__ . ' in ' . __CLASS__ . ' at ' . __LINE__);
            return false;
        }
        return $response;
    }

}
