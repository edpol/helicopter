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
        $namespaces = $xml->getNamespaces();
        foreach($namespaces as $key => $value){
            $namespace_list[] = $key;
        }
//      this will list the namespace
//        $this->listMethods($namespaces, 'Namespaces');

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
        if (isset($xml->children($namespace, true)->Body->children()->OTA_PkgAvailRQResponse->OTA_PkgAvailRQResult)) {
            $children = $xml->children($namespace, true)->Body->children()->OTA_PkgAvailRQResponse->OTA_PkgAvailRQResult;
            return $children;
        }
        return "Error, OTA_PkgAvailRQResult not found";
    }

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