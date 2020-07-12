<?php

/*
 * all the methods that consume the api results
 */
class Consume
{
    /*
     * Get namespace
     * If there is more than 1 type of namespace we need to figure something out
     */
    private function buildNamespaceArray($xml): array
    {
        $namespace_list = [];
        // only returns namspaces that are used
        $namespaces = $xml->getNamespaces();
        // displaces all namespaces declared
//        $namespaces = $xml->getDocNamespaces();
        foreach($namespaces as $key => $value){
            $namespace_list[] = $key;
        }

        // list methods in this class
        // $this->listMethods($namespaces, 'Namespaces');

        if(count($namespace_list)===0) {
            $namespace_list[0] = '';
        }
        return $namespace_list;
    }

    // echo all the methods in this class
    public function listMethods($target, $name=''): void
    {
        $type = gettype($target);
        switch($type){
            case 'array':
                echo "\r\n<table>\r\n";
                echo "<caption><u>{$name}</u></caption>\r\n";
                foreach($target as $key => $value){
                    echo "<tr><td>{$key}</td><td>{$value}</td></tr>\r\n";
                }
                echo "</table>\r\n<br />\r\n";
                break;
            case 'object':
                $class_methods = get_class_methods($target);
                echo "\r\n<table style='border-collapse: collapse;'>\r\n";
                echo '<caption>get_class_methods for class ' . get_class($target) . "</caption>\r\n";
                foreach($class_methods as $key => $value){
                    echo "<tr><td style='border: 1px solid #ddd; padding: 6px;'>{$key}</td><td style='border: 1px solid #ddd; padding: 6px;'>{$value}</td></tr>\r\n";
                }
                echo "</table>\r\n<br />\r\n";
                break;
        }
    }

    public function readAllPackages($inputString): SimpleXMLElement
    {
        // load the xml response string
        $xml = @simplexml_load_string($inputString);
//        $this->listMethods($xml);
        $namespaces = $this->buildNamespaceArray($xml);
        $namespace = $namespaces[0];

/*
 *          was this FAILURE or ERROR ?????
 *
 */

        // check for failure
        if (isset($xml->children($namespace, true)->Body->Fault->Reason->Text)) {
            $children = $xml->children($namespace, true)->Body->Fault->Reason->Text;
        } else {
            // took out (string)
            $children = $xml->children($namespace, true)->Body->children()
                ->OTA_PkgAvailRQResponse
                ->OTA_PkgAvailRQResult
                ->TravelChoices
                ->TravelItem;
        }
        return $children;
    }

    public function readSpecificPackage($inputString)
    {
        // load the xml response string
        $xml = @simplexml_load_string($inputString);
        $namespaces = $this->buildNamespaceArray($xml);
        $namespace = $namespaces[0];

        // check for failure
        if  (isset($xml->children($namespace, true)->Body->children()->OTA_PkgAvailRQResponse->OTA_PkgAvailRQResult)) {
            return $xml->children($namespace, true)->Body->children()->OTA_PkgAvailRQResponse->OTA_PkgAvailRQResult;
        }
        return "Error, OTA_PkgAvailRQResult not found ";
    }

/*
    <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <OTA_PkgAvailRQResponse xmlns="tflite.com/TakeFliteExternalService/">
            <OTA_PkgAvailRQResult EchoToken="Test" TimeStamp="2020-06-10T02:33:20" Version="1.0">
                <Success xmlns="http://www.opentravel.org/OTA/2003/05"/>
                <Package ID="128256" TravelCode="Icefield Excursion" xmlns="http://www.opentravel.org/OTA/2003/05">
                    <PriceInfo Amount="252.00"/>
                    <ItineraryItems>
                        <ItineraryItem>
                            <Flight DepartureDateTime="2021-04-26T16:15:00" ArrivalDateTime="2021-04-26T17:10:00" TravelCode="123925" Duration="55" CheckInDate="2021-04-26T15:45:00">
                                <DepartureAirport LocationCode="Juneau Airport"/>
                                <ArrivalAirport LocationCode="Juneau Airport"/>
                                <OperatingAirline FlightNumber="IE1615"/>
                            </Flight>
                        </ItineraryItem>
                        <ItineraryItem>
                            <Flight DepartureDateTime="2021-04-26T17:15:00" ArrivalDateTime="2021-04-26T18:10:00" TravelCode="123922" Duration="55" CheckInDate="2021-04-26T16:45:00">
                                <DepartureAirport LocationCode="Juneau Airport"/>
                                <ArrivalAirport LocationCode="Juneau Airport"/>
                                <OperatingAirline FlightNumber="IE1715"/>
                            </Flight>
                        </ItineraryItem>
                        <ItineraryItem>
                            <Flight DepartureDateTime="2021-04-26T18:15:00" ArrivalDateTime="2021-04-26T19:10:00" TravelCode="123919" Duration="55" CheckInDate="2021-04-26T17:45:00">
                                <DepartureAirport LocationCode="Juneau Airport"/>
                                <ArrivalAirport LocationCode="Juneau Airport"/>
                                <OperatingAirline FlightNumber="IE1815"/>
                            </Flight>
                        </ItineraryItem>
                    </ItineraryItems>
                </Package>
            </OTA_PkgAvailRQResult>
        </OTA_PkgAvailRQResponse>
    </s:Body>
*/

    public function readBookingRequest($inputString)
    {
        // load the xml response string
        $xml = @simplexml_load_string($inputString);
        $namespaces = $this->buildNamespaceArray($xml);
        $namespace = $namespaces[0];

        if  (isset($xml->children($namespace, true)->Body->children()->Fault)) {
            return $xml->children($namespace, true)->Body->children()->Fault;
        }
        if  (isset($xml->children($namespace, true)->Body->children()->OTA_PkgBookRQResponse->OTA_PkgBookRQResult)) {
            return $xml->children($namespace, true)->Body->children()->OTA_PkgBookRQResponse->OTA_PkgBookRQResult;
        }
        return "Error, OTA_PkgAvailRQResult not found ";
    }
/*
 * fail
    <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <OTA_PkgBookRQResponse xmlns="tflite.com/TakeFliteExternalService/">
            <OTA_PkgBookRQResult EchoToken="BooktestLerry" TimeStamp="2020-06-11T22:28:42" Version="1.0">
                <Errors xmlns="http://www.opentravel.org/OTA/2003/05">
                    <Error Type="Advisory" ShortText="No availability" Code="322"/>
                </Errors>
            </OTA_PkgBookRQResult>
        </OTA_PkgBookRQResponse>
    </s:Body>
 * Success
    <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <OTA_PkgBookRQResponse xmlns="tflite.com/TakeFliteExternalService/">
            <OTA_PkgBookRQResult EchoToken="BooktestLerry" TimeStamp="2020-06-12T17:13:45" Version="1.0">
                <Success xmlns="http://www.opentravel.org/OTA/2003/05"/>
                <PackageReservation xmlns="http://www.opentravel.org/OTA/2003/05">
                    <UniqueID Type="16" ID="200005" ID_Context="Booking reference number"/>
                </PackageReservation>
            </OTA_PkgBookRQResult>
        </OTA_PkgBookRQResponse>
    </s:Body>
 */

    public function readAirLowFareSearchRQ($inputString){
        // load the xml response string
        $xml = @simplexml_load_string($inputString);
        $namespaces = $this->buildNamespaceArray($xml);
        $namespace = $namespaces[0];

        if  (isset($xml->children($namespace, true)->Body->children()->OTA_AirLowFareSearchRQResponse->OTA_AirLowFareSearchRQResult)) {
            return $xml->children($namespace, true)->Body->children()->OTA_AirLowFareSearchRQResponse->OTA_AirLowFareSearchRQResult;
        }
        return "Error, OTA_PkgAvailRQResult not found ";
    }
	

/*
<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <OTA_AirLowFareSearchRQResponse xmlns="tflite.com/TakeFliteExternalService/">
        <OTA_AirLowFareSearchRQResult EchoToken="?" TimeStamp="2020-06-16T01:33:14" Version="1.0">
            <Errors xmlns="http://www.opentravel.org/OTA/2003/05">
                <Error Type="Application error" ShortText="Itinerary not possible" Code="18"/>
            </Errors>
        </OTA_AirLowFareSearchRQResult>
    </OTA_AirLowFareSearchRQResponse>

*/
}
