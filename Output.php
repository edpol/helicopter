<?php
Namespace Takeflite;

class Output {

    public function printList($response, $output_format=''){
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

        $Start = $Quantity = $EchoToken = $TimeStamp = $Version = $hidden = "";
        foreach($array as $key => $value){
            $$key = $value;
        }

//print "<pre>"; print_r($array); print "</pre>\r\n";

        $msg  = "<div id='wrapper'>\r\n";  // shouldn't wrapper be in the calling routine?
        $msg .= "<p>\r\n";
        $msg .= "\tTours for " . date('m/d/Y', strtotime($Start)) . "<br />\r\n";

        $msg .= "\tEchoToken:   " . $array['EchoToken'] . "<br />\r\n";
        $msg .= "\tTimeStamp:   " . $array['TimeStamp'] . "<br />\r\n";
        $msg .= "\tVersion:     " . $array['Version']   . "<br />\r\n";

        if(isset($array['Package'])) {
            $msg .= "\tPrice:       " . $Package['PriceInfo']['Amount'] . "<br />\r\n";
            $msg .= "\tID:          " . $Package['ID'] . "<br />\r\n";
            $msg .= "\tTravel Code: " . $Package['TravelCode'] . "<br />\r\n";
        }
        $msg .= "\tQuantity: {$Quantity}<br />\r\n";
        if(!empty($array['Code']    )) { $msg .= "Code: " . $array['Code'] . "<br />\r\n"; }
        $msg .= "</p>\r\n\r\n";

        $hidden_common = "";
        if(isset($EchoToken))    { $hidden_common .= "\t<input type='hidden' name='EchoToken' value='{$EchoToken}' />\r\n"; }
//UniqueID_ID
        if(isset($Package['TravelCode'])){ $hidden_common .= "\t<input type='hidden' name='PackageRequest_TravelCode' value='{$Package['TravelCode']}' />\r\n"; }
        if(isset($Package['ID'])){ $hidden_common .= "\t<input type='hidden' name='PackageRequest_ID' value='{$Package['ID']}' />\r\n"; }
        if(isset($Start))        { $hidden_common .= "\t<input type='hidden' name='Start'     value='{$Start}' />\r\n"; }
        if(isset($Quantity))     { $hidden_common .= "\t<input type='hidden' name='Quantity'  value='{$Quantity}'  />\r\n"; }


        $ItineraryItem = $array['Package']['ItineraryItems']['ItineraryItem'];
        foreach($ItineraryItem as $value){
            $msg .= "<form style='float:left;' action='info.php' method='POST'>\r\n";
            $msg .= "\t<button class='book_up' type='submit' value='submit'>\r\n";

            $Flight = $value['Flight'];

            $Flight['DepartureAirport_LocationCode'] = $Flight['DepartureAirport']['LocationCode'];
            $msg .= "\t\tDepartureAirport: " . $Flight['DepartureAirport_LocationCode'] . "<br />\r\n";

            $Flight['ArrivalAirport_LocationCode'] = $Flight['ArrivalAirport']['LocationCode'];
            $msg .= "\t\tArrivalAirport:   " . $Flight['ArrivalAirport_LocationCode']  . "<br />\r\n";

            $Flight['FlightNumber'] = $Flight['OperatingAirline']['FlightNumber'];
            $msg .= "\t\tFlightNumber:     " . $Flight['FlightNumber'] . "<br />\r\n";

            $list = array( 'DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate', 'DepartureAirport_LocationCode', 'ArrivalAirport_LocationCode', 'FlightNumber');
            $hidden .= $hidden_common;
            foreach($list as $target) {
                if(isset($Flight[$target])) {
                    $msg .= "\t\t{$target}:   " . $Flight[$target] . "<br />\r\n";
                    $hidden .= "\t<input type='hidden' name='{$target}[]' value='{$Flight[$target]}' />\r\n";
                }
            }
            $msg .= "\t</button>\r\n";
            $msg .= $hidden;
            $hidden = '';
            $msg .= "</form>\r\n\r\n";
        }

        $msg .= "<div style='clear:both;'></div>\r\n";
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

    public function dumpAttributes($prefix, $separator, $data, $newline, $dump = false)
    {
        $msg = '';
        $eol = ($newline) ? "<br />\r\n" : "";
        if(!is_object($data)){
            die('$data is not an object in '. __FUNCTION__ . ' file ' . __FILE__ . ' line number ' . __line__);
        }
        foreach ($data as $key => $value) {
            if(!empty($value)) {
                $msg .= "{$prefix} {$key}{$separator} {$value} {$eol}";
            }
        }
        return $msg;
    }

    // this is for a simpleXML string
    public function dumpErrors($data)
    {
        $msg = '';
        // ERROR: didn't like the data sent
        if (isset($data->Errors)) {
            $error_count = 1;
            foreach ($data->Errors as $Error) {
                $msg .= "Error #" . $error_count++ . ": <br />\r\n";
                $msg .= $this->dumpAttributes("&nbsp;&nbsp;", " = ", $data->Errors->Error, true);
            }
        }
        return $msg;
    }

    // this is for an array
    public function dumpErrorsArray($data)
    {
        if(is_bool($data)){
            $msg = ($data) ? 'TRUE ' : 'FALSE ';
        }else{
            $msg = '';
            if (isset($data['Errors'])) {
                $error_count = 1;
                foreach ($data['Errors'] as $Error) {
                    $msg .= "Error #" . $error_count++ . ": <br />";
                    foreach ($Error as $key => $detail) {
                        if (!empty($detail)) {
                            $msg .= "&nbsp;&nbsp; {$key} = {$detail} <br />";
                        }
                    }
                }
            }
        }
        return $msg;
    }

    public function dumpCatch($e, $soapClient, $location = '')
    {
        $msg  = "<p><b><u>Catch:</u></b> ";
        $msg .= "<span style='color:yellow;'>" . htmlentities($e->getMessage()) . "</span><br />\r\n" . $location;
        $msg .= "</p>" . PHP_EOL . PHP_EOL;

        $indent = "&nbsp;&nbsp;&nbsp;&nbsp;";

        if ($soapClient instanceof \SoapClient) {
            $array = ['__getLastRequest', '__getLastResponse', '__getLastRequestHeaders', '__getLastResponseHeaders'];
            foreach ($array as $method) {
                $msg .= "<p>";
                $msg .= "<b><u>{$method}:</u></b> <br />" . PHP_EOL;
                $string = htmlentities($soapClient->$method());

                $tmp = str_replace(
                    array('xmlns:',             PHP_EOL . '&lt;env:Envelope', '&gt;&lt;'),
                    array("~~{$indent}xmlns:", "~~&lt;env:Envelope",          "&gt;~~&lt;"),
                    $string);
                $output = explode("~~", $tmp);

                $count = 0;
                $limit = count($output);
                $msg .= $output[0] . "<br />";
                for ($i = 1; $i < $limit; $i++) {
                    // if the line starts with '</' then we indent less
                    $this_line_ends_with = substr($output[$i], 0, 5);
                    $found_closing_tag_above = (strripos($output[$i - 1], '&lt;/', 0) !== false || strripos($output[$i - 1], '?&gt;', 0) !== false);

                    if($this_line_ends_with === '&lt;/') {
                        if($count > 0) {$count--;}
                    } elseif(substr($output[$i - 1], -5) !== '/&gt;') {
                        if( (substr($output[$i - 1], -4) ===  '&gt;') && !$found_closing_tag_above ) {
                            $count++;
                        }
                    }
                    $indent = str_repeat($indent, ($count > 0) ? $count : 0);
                    $msg .= $indent . $output[$i] . "<br />";
                }
                $msg .= "</p>" . PHP_EOL . PHP_EOL;
            }
        }
        return $msg;
    }

}