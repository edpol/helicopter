<?php
/*
 * all the calls to the api
 */
class Api {
    private $credentials;
    private $top;
    private $bot;


    public function __construct($AgentLogin, $AgentPassword, $ServiceId)
    {
        $this->top = [];
        $this->top[] = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tak="tflite.com/TakeFliteExternalService/" xmlns:ns="http://www.opentravel.org/OTA/2003/05">';
        $this->top[] = '    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">';
        $this->top[] = '        <wsa:Action>tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_PkgAvailRQ</wsa:Action>';
        $this->top[] = '        <wsa:To>https://apps8.tflite.com/PublicService/Ota.svc</wsa:To>';
        $this->top[] = '    </soap:Header>';
        $this->top[] = '    <soap:Body>';

        $this->credentials = [];
        $this->credentials[] = '            <tak:Credentials>';
        $this->credentials[] = '               <tak:AgentLogin>' . $AgentLogin . '</tak:AgentLogin>';
        $this->credentials[] = '               <tak:AgentPassword>' . $AgentPassword . '</tak:AgentPassword>';
        $this->credentials[] = '               <tak:ServiceId>' . $ServiceId . '</tak:ServiceId>';
        $this->credentials[] = '            </tak:Credentials>';

        $this->bot = [];
        $this->bot[] = '    </soap:Body>';
        $this->bot[] = '</soap:Envelope>';
        $this->bot[] = '';  // this is so there will be an /r/n on the last line
    }

    public function allPackages()
    {
        $fields = [];
        $fields[] = '        <tak:OTA_PkgAvailRQ>';
        $fields[] = '            <tak:PkgAvailRQ EchoToken="test">';
        $fields[] = '                <ns:PackageRequest TravelCode="*"/>';
        $fields[] = '            </tak:PkgAvailRQ>';
        $fields = array_merge($fields, $this->credentials);
        $fields[] = '        </tak:OTA_PkgAvailRQ>';
        return $this->callCurl($fields);
    }

    public function specificPackage($EchoToken, $ID, $Code, $dateRange=null, $Quantity=1)
    {
        if (is_null($dateRange)) {
            $dateRange = date("Y-m-d");
        }
        $fields = [];
        $fields[] = '        <tak:OTA_PkgAvailRQ>';
        $fields[] = '            <tak:PkgAvailRQ EchoToken="Test">';
        $fields[] = '                <ns:PackageRequest ID="' . $ID . '">';
        $fields[] = '                    <ns:DateRange Start="' .$dateRange . '"/>';
        $fields[] = '                 </ns:PackageRequest>';
        $fields[] = '                 <ns:CustomerCounts>';
        $fields[] = '                     <ns:CustomerCount Code="' . $Code . '" Quantity="' . $Quantity . '"/>';
        $fields[] = '                 </ns:CustomerCounts>';
        $fields[] = '             </tak:PkgAvailRQ>';
        $fields = array_merge($fields, $this->credentials);
        $fields[] = '        </tak:OTA_PkgAvailRQ>';
        return $this->callCurl($fields);
    }

    public function bookingRequest()
    {
        $fields = [];
        $fields[] = '        <tak:OTA_PkgBookRQ>';
        $fields[] = '            <tak:PkgBookRQ EchoToken = "BooktestLerry">';
        $fields[] = '                <ns:UniqueID ID = "reference" />';
        $fields[] = '                <!--Same as in PkgAvailRQ:-->';
        $fields[] = '                <ns:PackageRequest ID = "100119" TravelCode = "Icefield Excursion" xmlns = "http://www.opentravel.org/OTA/2003/05">';
        $fields[] = '                    <ns:DateRange Start = "2020-05-15T00:00:00" />';
        $fields[] = '                    <ns:ItineraryItems>';
        $fields[] = '                        <ns:ItineraryItem>';
        $fields[] = '                            <ns:Flight DepartureDateTime = "2020-05-15T15:15:00" ArrivalDateTime = "2020-05-15T16:10:00" TravelCode = "104086" Duration = "55" CheckInDate = "2020-05-15T14:45:00">';
        $fields[] = '                                <ns:DepartureAirport LocationCode = "Juneau Airport" />';
        $fields[] = '                                <ns:ArrivalAirport LocationCode = "Juneau Airport" />';
        $fields[] = '                                <ns:OperatingAirline FlightNumber = "IE1515" />';
        $fields[] = '                            </ns:Flight>';
        $fields[] = '                        </ns:ItineraryItem>';
        $fields[] = '                    </ns:ItineraryItems>';
        $fields[] = '               </ns:PackageRequest>';
        $fields[] = '                <ns:ContactDetail>';
        $fields[] = '                    <ns:Telephone PhoneNumber = "123456789" />';
        $fields[] = '                    <ns:Address />';
        $fields[] = '                    <ns:Email> person@example . com </ns:Email>';
        $fields[] = '                </ns:ContactDetail>';
        $fields[] = '                <ns:PassengerListItems>';
        $fields[] = '                    <ns:PassengerListItem RPH = "1" Gender = "Unknown" Code = "ADT" CodeContext = "AQT" Quantity = "1">';
        $fields[] = '                        <ns:Name>';
        $fields[] = '                            <ns:GivenName> John</ns:GivenName>';
        $fields[] = '                            <ns:MiddleName />';
        $fields[] = '                            <ns:Surname> Doe</ns:Surname>';
        $fields[] = '                            <ns:NameTitle />';
        $fields[] = '                            <ns:SpecialNeed Code = "Weight"> 98</ns:SpecialNeed>';
        $fields[] = '                        </ns:Name>';
        $fields[] = '                    </ns:PassengerListItem>';
        $fields[] = '                    <ns:PassengerListItem RPH = "2" Gender = "Unknown" Code = "ADT" CodeContext = "AQT" Quantity = "1">';
        $fields[] = '                        <ns:Name>';
        $fields[] = '                            <ns:GivenName> Jane</ns:GivenName>';
        $fields[] = '                            <ns:MiddleName />';
        $fields[] = '                            <ns:Surname> Doe</ns:Surname>';
        $fields[] = '                            <ns:NameTitle />';
        $fields[] = '                            <ns:SpecialNeed Code = "Weight"> 97</ns:SpecialNeed>';
        $fields[] = '                        </ns:Name>';
        $fields[] = '                   </ns:PassengerListItem>';
        $fields[] = '               </ns:PassengerListItems>';
        $fields[] = '               <ns:PaymentDetails>';
        $fields[] = '                   <ns:PaymentDetail PaymentType = "34" />';
        $fields[] = '               </ns:PaymentDetails>';
        $fields[] = '           </tak:PkgBookRQ>';
        $fields = array_merge($fields, $this->credentials);
        $fields[] = '        </tak:OTA_PkgBookRQ>';
        return $this->callCurl($fields);
    }

    public function trimString($fields){
        foreach($fields as $key => $line){
            $fields[$key] = trim($line);
        }
        return $fields;
    }

    public function getWsdl()
    {
        $uri = 'https://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';
        return $this->callCurl('',$uri);
    }

    private function callCurl($fields, $uri='https://apps8.tflite.com/PublicService/Ota.svc')
    {

        $options = [
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [ 'Content-Type: application/soap+xml' ],
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        // if fields is empty then we are using getWsdl()
        if(!empty($fields)) {
            $fields = array_merge($this->top, $fields, $this->bot);
            $fields = $this->trimString($fields);
            $postFields = implode("\r\n", $fields);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        $response = curl_exec($curl);
        curl_close($curl);

        if($response === false)
        {
            echo 'Curl error: ' . curl_error($curl);
            die();
        }
        return $response;
    }

}
