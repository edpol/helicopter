<?php

/*
 * all the methods that consume the api results
 */
class Consume
{
    /*
     * If there are more than 1 type of namespace we need to figure something out
     */
    private function buildNamespaceArray($xml): array
    {
        $namespace_list = [];
        $namespaces = $xml->getNamespaces();
        foreach($namespaces as $key => $value){
            $namespace_list[] = $key;
        }
        $this->listMethods($namespaces, 'Namespaces');

        if(count($namespace_list)===0) {
            $namespace_list[0] = '';
        }
        return $namespace_list;
    }

    // for debugging only
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
                echo "\r\n<table>\r\n";
                echo '<caption>get_class_methods for class ' . get_class($target) . "</caption>\r\n";
                foreach($class_methods as $key => $value){
                    echo "<tr><td>{$key}</td><td>{$value}</td></tr>\r\n";
                }
                echo "</table>\r\n<br />\r\n";
                break;
        }
    }

    public function checkForFailure($inputString): void
    {
        $xml = @simplexml_load_string($inputString);

        $namespaces = $this->buildNamespaceArray($inputString);
        $namespace = $namespaces[0];

        try {
            $children = $xml->children($namespace, true)->Body->Fault->Reason->Text;

            foreach ($children as $child) {
                echo $child . "<br />\r\n";
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    public function read($inputString): void
    {
        $xml = @simplexml_load_string($inputString);

        $this->listMethods($xml);

        $namespaces = $this->buildNamespaceArray($xml);
        $namespace = $namespaces[0];

        // took out (string)
        $child = $xml->children($namespace,true)
            ->Body
            ->children()
            ->OTA_PkgAvailRQResponse
            ->OTA_PkgAvailRQResult
            ->TravelChoices
            ->TravelItem;

        echo "Type Returned: <u>" . gettype($child) . '</u><br />';

        foreach ($child as $travelItem) {
    //    $DepartureAirport = $travelItem->TravelDetail->OutwardTravel->AirSegment->DepartureAirport->attributes();
    //    $ArrivalAirport   = $travelItem->TravelDetail->OutwardTravel->AirSegment->ArrivalAirport->attributes();
            $AirSegment = $travelItem->TravelDetail->OutwardTravel->AirSegment;
            foreach ($AirSegment->attributes() as $key => $value) {
                echo "<p>{$key}: $value <br />\r\n";
                foreach ($AirSegment->children() as $key1 => $value1) {
                    echo '     ' . $key1 . ': ';
                    $child = $travelItem->TravelDetail->OutwardTravel->AirSegment->{$key1};
                    foreach ($child->attributes() as $key2 => $value2) {
                        echo " $key2 = $value2 ";
                    }
                    echo "<br />\r\n";
                }
                echo "</p>\r\n";
            }
        }
    }

}