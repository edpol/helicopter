<?php
/*
 * all the calls to the api
 * If all the strings in the array are single quote all the tags become lower case
 * with just one exception they keep their case
 */
class Api {
    private $credentials;
    private $top_avail;
    private $top_book;
    private $top_fare;
    private $bot;


    public function __construct($AgentLogin, $AgentPassword, $ServiceId)
    {
        $this->top_avail=array();
        $this->top_avail[]='<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tak="tflite.com/TakeFliteExternalService/" xmlns:ns="http://www.opentravel.org/OTA/2003/05">';
        $this->top_avail[]='    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">';
        $this->top_avail[]='        <wsa:Action>tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_PkgAvailRQ</wsa:Action>';
        $this->top_avail[]='        <wsa:To>https://apps8.tflite.com/PublicService/Ota.svc</wsa:To>';
        $this->top_avail[]='    </soap:Header>';
        $this->top_avail[]='    <soap:Body>';

        $this->top_book = $this->top_avail;
        $this->top_book[2] ='        <wsa:Action>tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_PkgBookRQ</wsa:Action>';

        $this->top_fare = $this->top_avail;
        $this->top_fare[2] ='        <wsa:Action>tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_AirLowFareSearchRQ</wsa:Action>';

        $this->credentials=array();
        $this->credentials[]='            <tak:Credentials>';
        $this->credentials[]='               <tak:AgentLogin>' . $AgentLogin . '</tak:AgentLogin>';
        $this->credentials[]='               <tak:AgentPassword>' . $AgentPassword . '</tak:AgentPassword>';
        $this->credentials[]='               <tak:ServiceId>' . $ServiceId . '</tak:ServiceId>';
        $this->credentials[]='            </tak:Credentials>';

        $this->bot=array();
        $this->bot[]='    </soap:Body>';
        $this->bot[]='</soap:Envelope>';
        $this->bot[]='';  // this is so there will be an /r/n on the last line
    }

    public function allPackages()
    {
        $fields=array();
        $fields[]='        <tak:OTA_PkgAvailRQ>';
        $fields[]='            <tak:PkgAvailRQ EchoToken="test">';
        $fields[]='                <ns:PackageRequest TravelCode="*"/>';
        $fields[]='            </tak:PkgAvailRQ>';
        $fields=array_merge($fields, $this->credentials);
        $fields[]='        </tak:OTA_PkgAvailRQ>';
        return $this->callCurl($fields, $this->top_avail);
    }

    public function specificPackage($EchoToken, $ID, $Code, $Start=null, $Quantity=1)
    {
        if (is_null($Start)) {
            $Start=date("Y-m-d");
        }
        $fields=array();
        $fields[]='        <tak:OTA_PkgAvailRQ>';
        $fields[]='            <tak:PkgAvailRQ EchoToken="' . $EchoToken .'">';
        $fields[]='                <ns:PackageRequest ID="' . $ID . '">';
        $fields[]='                    <ns:DateRange Start="' .$Start . '"/>';
        $fields[]='                 </ns:PackageRequest>';
        $fields[]='                 <ns:CustomerCounts>';
        $fields[]='                     <ns:CustomerCount Code="' . $Code . '" Quantity="' . $Quantity . '"/>';
        $fields[]='                 </ns:CustomerCounts>';
        $fields[]='             </tak:PkgAvailRQ>';
        $fields=array_merge($fields, $this->credentials);
        $fields[]='        </tak:OTA_PkgAvailRQ>';
        return $this->callCurl($fields, $this->top_avail);
    }

    public function bookingRequest($EchoToken, $UniqueID, $ID, $TravelCode, $Start, $DepartureDateTime, $ArrivalDateTime, $TravelCodeID, $Duration, $CheckInDate,
                                   $DepartureAirport, $ArrivalAirport, $FlightNumber, $Telephone, $RPH, $Gender, $Code, $CodeContext, $Quantity, $PaymentType,
                                   $Address=null, $Email=null, $GivenName=null, $MiddleName=null, $Surname=null, $NameTitle=null, $SpecializedNeed=null)
    {
        $fields=array();
        $fields[]="        <tak:OTA_PkgBookRQ>";
        $fields[]='            <tak:PkgBookRQ EchoToken="' . $EchoToken . '">';
        $fields[]='                <ns:UniqueID ID="' . $UniqueID . '"/>';
        $fields[]='                <!--Same as in PkgAvailRQ:-->';
        $fields[]='                <ns:PackageRequest ID="' . $ID . '" TravelCode="' . $TravelCode . '" xmlns="http://www.opentravel.org/OTA/2003/05">';
        $fields[]='                    <ns:DateRange Start="' . $Start . '"/>';
        $fields[]='                    <ns:ItineraryItems>';
        $fields[]='                        <ns:ItineraryItem>';
        $fields[]='                            <ns:Flight DepartureDateTime="' . $DepartureDateTime . '" ArrivalDateTime="' . $ArrivalDateTime . '" TravelCode="' . $TravelCodeID . '" Duration="' . $Duration . '" CheckInDate="' . $CheckInDate . '">';
        $fields[]='                                <ns:DepartureAirport LocationCode="' . $DepartureAirport . '"/>';
        $fields[]='                                <ns:ArrivalAirport LocationCode="' . $ArrivalAirport . '"/>';
        $fields[]='                                <ns:OperatingAirline FlightNumber="' . $FlightNumber .'"/>';
        $fields[]='                            </ns:Flight>';
        $fields[]='                        </ns:ItineraryItem>';
        $fields[]='                    </ns:ItineraryItems>';
        $fields[]='                </ns:PackageRequest>';
        $fields[]='                <ns:ContactDetail>';
        $fields[]='                    <ns:Telephone PhoneNumber="' . $Telephone . '"/>';
        $fields[]=(is_null($Address)) ? "                    <ns:Address/>" : '                    <ns:Address>' . $Address . '</ns:Address>';
        $fields[]=(is_null($Email))   ? "                    <ns:Email/>"   : '                    <ns:Email>'   . $Email   . '</ns:Email>';
        $fields[]='                </ns:ContactDetail>';

        $fields=array_merge($fields, $this->passengerListItems($RPH, $Gender, $Code, $CodeContext, $Quantity, $GivenName, $MiddleName, $Surname, $NameTitle, $SpecializedNeed));

        $fields[]='               <ns:PaymentDetails>';
        $fields[]='                   <ns:PaymentDetail PaymentType="' . $PaymentType . '"/>';
        $fields[]='               </ns:PaymentDetails>';
        $fields[]='           </tak:PkgBookRQ>';
        $fields=array_merge($fields, $this->credentials);
        $fields[]='        </tak:OTA_PkgBookRQ>';

        return $this->callCurl($fields, $this->top_book);
    }

    private function passengerListItems($RPH, $Gender, $Code, $CodeContext, $Quantity,
                                        $GivenName=null, $MiddleName=null, $Surname=null, $NameTitle=null, $SpecializedNeed=null)
    {
        $cnt=count($RPH);  // some variable that cannot be skipped
        $list=array("GivenName", "MiddleName", "Surname", "NameTitle");
        $fields=array();
        $fields[]='                <ns:PassengerListItems>';
        for($i=0; $i<$cnt; $i++){
            $fields[]='                    <ns:PassengerListItem RPH="' . $RPH[$i] . '" Gender="' . $Gender[$i] . '" Code="' . $Code[$i] . '" CodeContext="' . $CodeContext[$i] . '" Quantity="' . $Quantity[$i] . '">';
            $fields[]='                        <ns:Name>';
            foreach($list as $tag){
                $temp=(is_null($$tag[$i])) ? "<ns:{$tag}/>" : "<ns:{$tag}>{$$tag[$i]}</ns:{$tag}>";
                $fields[]=str_repeat(" ", 28) . $temp;
            }
            $needs=$SpecializedNeed[$i];
            foreach($needs as $data => $value) {
                if(!is_null($value)) {
                    $temp='<ns:SpecialNeed Code="' . $data . '">' . $value . '</ns:SpecialNeed>';
                    $fields[]=str_repeat(" ", 28) . $temp;
                }
            }
            $fields[]='                        </ns:Name>';
            $fields[]='                    </ns:PassengerListItem>';
        }
        $fields[]='               </ns:PassengerListItems>';
        return $fields;
    }

    public function AirLowFareSearchRQ($EchoToken, $DepartureDate, $OriginLocationCode, $OriginCodeContext, $DestinationLocationCode, $DestinationCodeContext, $PassengerTypeQuantity )
    {
        $fields = array();
        $fields[] = '        <tak:OTA_AirLowFareSearchRQ>';
        $fields[] = '            <tak:AirLowFareSearchRQ EchoToken="' . $EchoToken . '">';
        $fields[] = '                <ns:OriginDestinationInformation>';
        $fields[] = '                    <ns:DepartureDateTime>' . $DepartureDate . '</ns:DepartureDateTime>';
        $fields[] = '                    <ns:OriginLocation LocationCode="' . $OriginLocationCode . '" CodeContext="' . $OriginCodeContext . '"></ns:OriginLocation>';
        $fields[] = '                    <ns:DestinationLocation LocationCode="' . $DestinationLocationCode . '" CodeContext="' . $DestinationCodeContext . '"></ns:DestinationLocation>';
        $fields[] = '                </ns:OriginDestinationInformation>';
        $fields[] = '                <ns:TravelerInfoSummary>';
        $fields[] = '                    <ns:AirTravelerAvail>';
        foreach ($PassengerTypeQuantity as $data){
            $fields[] = '                        <ns:PassengerTypeQuantity Code="' . $data["Code"] . '" Quantity="' . $data["Quantity"] . '"/>';
        }
        $fields[]='                    </ns:AirTravelerAvail>';
        $fields[]='                </ns:TravelerInfoSummary>';
        $fields[]='            </tak:AirLowFareSearchRQ>';
        $fields=array_merge($fields, $this->credentials);
        $fields[]='        </tak:OTA_AirLowFareSearchRQ>';
        return $this->callCurl($fields, $this->top_fare);
    }


    public function trimString($fields){
        foreach($fields as $key => $line){
            $fields[$key]=trim($line);
        }
        return $fields;
    }

    /*
     * output the wsdl file to wsdl.xml if the existing one is more than one day old
     * return that the file exists true or false
     */
    public function getWsdl()
    {
        $uri = WSDL_ADDR;
        $wsdl_file = WSDL_FILE;

        // if the folder does not exist, create it
        if(!is_dir(WSDL_FOLDER)){
            if(!mkdir(WSDL_FOLDER, 0777, true)){
                die("Could not create folder " . WSDL_FOLDER . ". Check access permissions. ");
            }
        }

        // find out when the cache was last updated
        if (file_exists($wsdl_file)) {
            $modified = filemtime($wsdl_file);
        }

        // create or update the cache if necessary
        if (!isset($modified) || $modified + CACHE_LIFETIME < time()) {
            if ($string = @ file_get_contents($uri)) {
                //file_put_contents($wsdl_file, $string);
                $xml = new SimpleXMLElement($string);
                $doc = new DOMDocument('1.0', 'utf-8');
                $doc->formatOutput = true;
                $node = dom_import_simplexml($xml);  // convert simpleXML data to DOM node
                $node = $doc->importNode($node, true);  // import the converted xml can be imported into the empty dom document
                $doc->appendChild($node);
                $doc->save($wsdl_file);
            } else {
                die("Could not create file " . WSDL_FILE . ". Check access permissions. ");
            }
            return true;
        }

        // file_get_contents($uri) does the same thing
//        return $this->callCurl('',$uri);
        return file_exists($wsdl_file);
    }

    public function instantiateSoapClient(){
        if($this->getWsdl()) {
            ini_set('soap.wsdl_cache_enabled', '0');
            $http = ['method' => 'GET', 'header' => "Content-Type: application/soap+xml\r\n" . "Charset=utf-8\r\n",];
            $ssl  = ['verify_peer' => false, 'verify_peer_name' => false];
            $opts = [ 'http' => $http, 'ssl' => $ssl ];
//            $context = stream_context_create($opts);
//            $options = ['stream_context' => $context, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE, 'trace' => TRACE ];
            $options = ['http' => $http, 'ssl' => $ssl, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE, 'trace' => TRACE ];

//            $options['soapAction'] ='http://tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_PkgAvailRQ';

            $client = null;
            try {
                $client = new SoapClient(WSDL_FILE, $options);
                return $client;
            } catch (SoapFault $e) {
                echo "<pre>";
                dumpCatch($e, $client);
                echo "</pre>";
            }
        }
        return false;
    }

    private function callCurl($fields, $top, $uri='https://apps8.tflite.com/PublicService/Ota.svc')
    {

        $options=array(
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array( 'Content-Type: application/soap+xml' ),
        );

        $curl=curl_init();
        curl_setopt_array($curl, $options);
        // if fields is empty then we are using getWsdl()
        if(!empty($fields)) {
            $fields=array_merge($top, $fields, $this->bot);
            $fields=$this->trimString($fields);
            $postFields=implode("\r\n", $fields);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        $response=curl_exec($curl);
        curl_close($curl);

        if($response === false)
        {
            echo 'Curl error: ' . curl_error($curl);
            die();
        }
        return $response;
    }

}
