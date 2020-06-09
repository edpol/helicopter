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
        $this->listMethods($namespaces, 'Namespaces');

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
        $this->listMethods($xml);
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

    public function readSpecificPackage($inputString): SimpleXMLElement
    {
        // load the xml response string
        $xml = @simplexml_load_string($inputString);
        $namespaces = $this->buildNamespaceArray($xml);
        $namespace = $namespaces[0];

        // check for failure
        if (isset($xml->children($namespace, true)->Body->children()->OTA_PkgAvailRQResponse->OTA_PkgAvailRQResult->Errors)) {
            $children = $xml->children($namespace, true)->Body->children()->OTA_PkgAvailRQResponse->OTA_PkgAvailRQResult;
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

}