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
        $array = json_decode(json_encode($response->OTA_PkgAvailRQResult), true);
//print_r($array['TravelChoices']['TravelItem']);
        $msg  = "<div id='wrapper'>\r\n";
        $msg .= "<p>\r\n";
        $msg .= "Token:       " . $array['EchoToken'] . "<br />";
        $msg .= "TimeStamp:   " . $array['TimeStamp'] . "<br />";
        $msg .= "Version:     " . $array['Version']   . "<br />";
        $msg .= "</p>\r\n";
        $msg .= "<hr>";

        $TravelItem = $array['TravelChoices']['TravelItem'];
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
        $array = json_decode(json_encode($response->OTA_PkgAvailRQResult), true);
        $Start = $response->Start;
        $EchoToken = $TimeStamp = $Version = null;
        foreach($array as $key => $value){
            $$key = $value;
        }

        $msg  = "<div id='wrapper'>\r\n";
        $msg .= "<p>\r\n";
        $msg .= "Tours for " . date('m/d/Y', strtotime($Start)) . "<br />\r\n";

        $msg .= "EchoToken:   " . $array['EchoToken'] . "<br />\r\n";
        $msg .= "TimeStamp:   " . $array['TimeStamp'] . "<br />\r\n";
        $msg .= "Version:     " . $array['Version']   . "<br />\r\n";

        if(isset($array['Package'])) {
            $msg .= "Price:       " . $array['Package']['PriceInfo']['Amount'] . "<br />\r\n";
            $msg .= "ID:          " . $array['Package']['ID'] . "<br />\r\n";
            $msg .= "Travel Code: " . $array['Package']['TravelCode'] . "<br />\r\n";
        }
        if(!empty($array['Quantity'])) { $msg .= "Quantity: " . $array['Quantity'] . "<br />\r\n"; }
        if(!empty($array['Code']    )) { $msg .= "Code: " . $array['Code'] . "<br />\r\n"; }
        $msg .= "</p>\r\n";

        $ItineraryItem = $array['Package']['ItineraryItems']['ItineraryItem'];
        foreach($ItineraryItem as $value){
//            $msg .= "<div class='blueBox'>";
            $Flight = $value['Flight'];
            $msg .= "<form style='float:left;' action='book.php' method='POST'>\r\n";
            $msg .= "<button type='submit' class='book_up'>\r\n";
            $msg .= "DepartureAirport: " . $Flight['DepartureAirport']['LocationCode'] . "<br />\r\n";
            $msg .= "ArrivalAirport:   " . $Flight['ArrivalAirport']['LocationCode']   . "<br />\r\n";
            $msg .= "FlightNumber:     " . $Flight['OperatingAirline']['FlightNumber'] . "<br />\r\n";

            $list = array( 'DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate');
            $target_input = '';
            foreach($list as $target) {
                $msg .= "{$target}:   " . $value['Flight'][$target]   . "<br />\r\n";
                $target_input .= "<input type='hidden' name='{$target}' value='{$value['Flight'][$target]}' />\r\n";
            }
            $msg .= "</button>\r\n";
            $msg .= "<input type='hidden' name='Start'       value='{$Start}'     />\r\n";
            $msg .= "<input type='hidden' name='EchoToken'   value='{$EchoToken}' />\r\n";
            $msg .= "<input type='hidden' name='TimeStamp'   value='{$TimeStamp}' />\r\n";
            $msg .= "<input type='hidden' name='Version'     value='{$Version}'   />\r\n";
            $msg .= "<input type='hidden' name='DepartureAirport_LocationCode' value='{$value['Flight']['DepartureAirport']['LocationCode']}' />\r\n";
            $msg .= "<input type='hidden' name='ArrivalAirport_LocationCode'   value='{$value['Flight']['ArrivalAirport']['LocationCode']}'   />\r\n";
            $msg .= "<input type='hidden' name='FlightNumber' value='{$value['Flight']['OperatingAirline']['FlightNumber']}' />\r\n";
            $msg .= $target_input;
            $msg .= "</form>\r\n";
//            $msg .= "</div>\r\n";  // id=bookBox
        }

//        echo "<pre>";
//        print_r($array);
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