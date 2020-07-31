<?php
function OTA_PkgBookRQ($parameters)
{
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
//        $Flight['DepartureDateTime'] = $DepartureDateTime[$i];
//        $Flight['ArrivalDateTime']   = $ArrivalDateTime[$i];
//        $Flight['TravelCode']        = $Flight_TravelCode[$i];
//        $Flight['Duration']          = $Duration[$i];
//        $Flight['CheckInDate']       = $CheckInDate[$i];

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
    if(isset($EchoToken))                 $PkgBookRQ['EchoToken'] = $EchoToken;
    if(isset($UniqueID_ID))               $PkgBookRQ['UniqueID']['ID'] = $UniqueID_ID;
    if(isset($PackageRequest))            $PkgBookRQ['PackageRequest'] = $PackageRequest;

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

    $manual = array(
        'EchoToken' => 'BooktestLerry',
        'UniqueID' => array('ID' => 'Reference'),
        'PackageRequest' => array(
            'ID' => '128256',
            'TravelCode' => 'Ice field Excursion',
            'DateRange' => array(
                'Start' => '2021-04-26T16:15:00'
            ),
            'ItineraryItems' => array(
                'ItineraryItem' => array(
                    0 => array(
                        'Flight' => array(
                            'DepartureDateTime' => '2021-04-26T16:15:00',
                            'ArrivalDateTime' => '2021-04-26T17:10:00',
                            'TravelCode' => '123925',
                            'Duration' => '55',
                            'CheckInDate' => '2021-04-26T15:45:00',
                            'DepartureAirport' => array('LocationCode' => 'Juneau Airport'),
                            'ArrivalAirport' => array('LocationCode' => 'Juneau Airport'),
                            'OperatingAirline' => array('FlightNumber' => 'IE1615'),
                        ),
                    ),
                )
            ),
        ),
        'ContactDetail' => array(
            'Telephone' => array('PhoneNumber' => '1234567890'),
            'Address' => array('_'=>''),
            'Email' => array('_' => 'person@example.com'),
        ),
        'PassengerListItems' => array(
            'PassengerListItem' => array(
                array(
                    'RPH' => '1', 'Gender' => 'Unknown', 'Code' => 'ADT', 'CodeContext' => 'AQT', 'Quantity' => '1',
                    'Name' => array(
                        'GivenName' => array('_' => 'John'),
                        'MiddleName' => array('_' => 'G'),
                        'Surname' => array('_' => 'Doe'),
                        'NameTitle' => array('_' => 'Mr'),
                        'SpecialNeed' => array( array('Code' => 'Weight', '_' => '98') )
                    )
                ),
                array(
                    'RPH' => '2', 'Gender' => 'Unknown', 'Code' => 'ADT', 'CodeContext' => 'AQT', 'Quantity' => '1',
                    'Name' => array(
                        'GivenName' => array('_' => 'Jane'),
                        'Surname' => array('_' => 'Doe'),
                        'NameTitle' => array('_' => 'Mrs'),
                        'SpecialNeed' => array( array('Code' => 'Weight', '_' => '97') )
                    )
                )
            )
        ),
        'PaymentDetails' => array(
            'PaymentDetail' => array(
                'PaymentType' => '34'
            )
        ),
    );

    echo 'Built from Parameters: '; print_r($PkgBookRQ);
    echo '<hr>';
    echo 'Manual: '; print_r($manual);

    return $PkgBookRQ;
}

//$parameters= array();
//$PkgBookRQ = OTA_PkgBookRQ($parameters);
//
echo '<pre>';
//print_r($PkgBookRQ);
//

/*
 *          (WSDL) the message tag says that the name of the input tag is OTA_PkgBookRQ
 *          (XSD0) says 'tag OTA_PkgBookRQ' has 2 tags:
 *              PkgAvailRQ of 'type OTA_PkgAvailRQ'
 *              Credentials of type CredentialsType - this is done
 *
 *          element PkgAvailRQ of 'type OTA_PkgAvailRQ' is made up of a lot of attributes,
 *          and 6 elements:
 *              POS of type ArrayOfSourceType
 *              MultimodalOffer of type MultiModalOfferType
 *              PackageRequest of type
 *
 *          PkgBookRQ of type
 *          This is the array that we are sending to the function to send to the SOAP call
 */



$parameters = array(
    'EchoToken'=>'BooktestLerry',
    'UniqueID_ID'=>'Reference', 'PackageRequest_ID'=>'128256', 'PackageRequest_TravelCode'=>'Ice field Excursion',
    'Start'=>'2021-04-26T16:15:00',

    'DepartureDateTime'=>array('2021-04-26T16:15:00'),
    'ArrivalDateTime'=>array('2021-04-26T17:10:00'),
    'Flight_TravelCode'=>array('123925'),
    'Duration'=>array('55'),
    'CheckInDate'=>array('2021-04-26T15:45:00'),

    'DepartureAirport_LocationCode'=>array('Juneau Airport'),
    'ArrivalAirport_LocationCode'=>array('Juneau Airport'),
    'FlightNumber'=>array('IE1615'),

    'PhoneNumber'=>'1234567890',
    'Address'=>'',
    'Email'=>'person@example.com',

    'RPH'=>array('1','2'), 'Gender'=>array('Unknown','Unknown'), 'PassengerListItem_Code'=>array('ADT','ADT'),
    'CodeContext'=>array('AQT','AQT'), 'Quantity'=>array('1','1'),

    'GivenName'=>array('John','Jane'),
    'MiddleName'=>array('G'),
    'Surname'=>array('Doe','Doe'),
    'NameTitle'=>array('Mr','Mrs'),
    'SpecialNeed_Code'=>array(array('Weight'=>'98'),array('Weight'=>'97')),

    'PaymentType'=>'34'
);

$x =  OTA_PkgBookRQ($parameters);
