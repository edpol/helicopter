<?php
Namespace Takeflite;

class Output {

    public function printList($response, $output_format){
        if($output_format === "AllPackages") { $msg = $this->printAllPkgs($response); }
        elseif($output_format === "PackagesSpecificDate") { $msg = $this->printPkgList($response); }
        else { die("Problem selecting report"); }
        return $msg;
    }

    private function printAllPkgs($response)
    {
        $array = json_decode(json_encode($response), true);
//print_r($array['OTA_PkgAvailRQResult']['TravelChoices']['TravelItem']);
        $msg  = "<div id='wrapper'>\r\n";
        $msg .= "<p>\r\n";
        $msg .= "Token:       " . $array['OTA_PkgAvailRQResult']['EchoToken'] . "<br />";
        $msg .= "TimeStamp:   " . $array['OTA_PkgAvailRQResult']['TimeStamp'] . "<br />";
        $msg .= "Version:     " . $array['OTA_PkgAvailRQResult']['Version']   . "<br />";
        $msg .= "</p>\r\n";
        $msg .= "<hr>";

        $TravelItem = $array['OTA_PkgAvailRQResult']['TravelChoices']['TravelItem'];
        foreach($TravelItem as $item){
            $msg .= "<div class='greenBox'>";
            $msg = $this->dumpArray($item, $msg);
            $msg .= "</div>";
        }
        $msg .= "<div style='clear:both;'></div>";
        return $msg;
    }

    private function printPkgList($response)
    {
        $array = json_decode(json_encode($response), true);

        $Start = $array['Start'];

        $msg  = "<div id='wrapper'>\r\n";
        $msg .= "<p>\r\n";
        $msg .= "Tours for " . date('m/d/Y', strtotime($Start)) . "<br />\r\n";

        $msg .= "EchoToken:   " . $array['OTA_PkgAvailRQResult']['EchoToken'] . "<br />\r\n";
        $msg .= "TimeStamp:   " . $array['OTA_PkgAvailRQResult']['TimeStamp'] . "<br />\r\n";
        $msg .= "Version:     " . $array['OTA_PkgAvailRQResult']['Version']   . "<br />\r\n";

        if(isset($array['OTA_PkgAvailRQResult']['Package'])) {
            $msg .= "Price:       " . $array['OTA_PkgAvailRQResult']['Package']['PriceInfo']['Amount'] . "<br />\r\n";
            $msg .= "ID:          " . $array['OTA_PkgAvailRQResult']['Package']['ID'] . "<br />\r\n";
            $msg .= "Travel Code: " . $array['OTA_PkgAvailRQResult']['Package']['TravelCode'] . "<br />\r\n";
        }
        if(!empty($array['Quantity'])) { $msg .= "Quantity: " . $array['Quantity'] . "<br />\r\n"; }
        if(!empty($array['Code']    )) { $msg .= "Code: " . $array['Code'] . "<br />\r\n"; }
        $msg .= "</p>\r\n";

        $ItineraryItem = $array['OTA_PkgAvailRQResult']['Package']['ItineraryItems']['ItineraryItem'];
        foreach($ItineraryItem as $value){
//            $msg .= "<div class='blueBox'>";
            $msg .= "<a style='display:block' class='blueBox' href='http://mdr.com'>";
            $msg .= "DepartureAirport: " . $value['Flight']['DepartureAirport']['LocationCode'] . "<br />\r\n";
            $msg .= "ArrivalAirport:   " . $value['Flight']['ArrivalAirport']['LocationCode']   . "<br />\r\n";
            $msg .= "FlightNumber:     " . $value['Flight']['OperatingAirline']['FlightNumber'] . "<br />\r\n";

            $list = array( 'DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate');
            foreach($list as $target) {
                $msg .= "{$target}:   " . $value['Flight'][$target]   . "<br />\r\n";
            }
            $msg .= "</a>\r\n";
//            $msg .= "</div>\r\n";  // id=blueBox
        }

//        echo "<pre>";
//        print_r($array['OTA_PkgAvailRQResult']);
        $msg .= "</div>\r\n";  // id=wrapper
        return $msg;
    }

    private function dumpArray($array, $msg){
        foreach($array as $key => $value){
            if(is_array($value)){
                $msg = $this->dumpArray($value, $msg);
            } elseif(!empty($value)) {
                $msg .= "$key: $value <br>\r\n";
            }
        }
        return $msg;
    }

}