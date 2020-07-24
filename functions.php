<?php
namespace Takeflite {

    function dumpAttributes($prefix, $separator, $data, $newline, $dump = false)
    {
        if ($dump) echo "1 ";
        $eol = ($newline) ? "<br />\r\n" : "";
        foreach ($data->attributes() as $key => $value) {
            if ($dump) echo "2 ";
            echo "{$prefix} {$key}{$separator} {$value} {$eol}";
        }
        if ($dump) echo "3 ";
        if ($dump) {
            echo "<pre>";
            print_r($data);
        }
    }

    // this is for a simpleXML string
    function dumpErrors($data)
    {
        // ERROR: didn't like the data sent
        if (isset($data->Errors)) {
            $error_count = 1;
            foreach ($data->Errors as $Error) {
                echo "Error #" . $error_count++ . ": <br />\r\n";
                dumpAttributes("&nbsp;&nbsp;", " = ", $data->Errors->Error, true);
            }
        }
    }

    // this is for an array
    function dumpErrorsArray($data)
    {
        if (isset($data['Errors'])) {
            $error_count = 1;
            foreach ($data['Errors'] as $Error) {
                echo "Error #" . $error_count++ . ": <br />";
                foreach ($Error as $key => $detail) {
                    if (!empty($detail)) echo "&nbsp;&nbsp; {$key} = {$detail} <br />";
                }
            }
        }
    }

    function dumpCatch($e, $soapClient, $location = '')
    {
        echo "<p><b><u>catch:</u></b> ";
        echo htmlentities($e->getMessage()) . "<br />\r\n" . $location;
        echo "</p>" . PHP_EOL . PHP_EOL;

        if ($soapClient instanceof SoapClient) {
            $array = ['__getLastRequest', '__getLastResponse', '__getLastRequestHeaders', '__getLastResponseHeaders'];
            $count = 0;
            foreach ($array as $method) {
                echo "<p>";
                echo "<b><u>{$method}:</u></b> <br />" . PHP_EOL;
                $string = htmlentities($soapClient->$method());
//echo $string . "<br>\r\n";
                $tmp = str_replace('xmlns:', "~~&nbsp;&nbsp;&nbsp;&nbsp;xmlns:", $string);
                $tmp = str_replace(PHP_EOL . '&lt;env:Envelope', "~~&lt;env:Envelope", $tmp);
                $tmp = str_replace('&gt;&lt;', "&gt;~~&lt;", $tmp);
                $output = explode("~~", $tmp);
//print_r($output);

                $count = 0;
                $limit = count($output);
                echo $output[0] . "<br />";
                for ($i = 1; $i < $limit; $i++) {
                    // if the line starts with '</' then we indent less
                    $this_line_ends_with = substr($output[$i], 0, 5);
                    $found_closing_tag_above = (strripos($output[$i - 1], '&lt;/', 0) !== false || strripos($output[$i - 1], '?&gt;', 0) !== false);

                    if ($this_line_ends_with == '&lt;/') {
                        if ($count > 0) $count--;
                    } elseif (substr($output[$i - 1], -5) != '/&gt;') {
                        if (substr($output[$i - 1], -4) == '&gt;')
                            if (!$found_closing_tag_above) $count++;
                    }
//echo substr($output[$i-1],-5) . "<br />\r\n";
//echo  $this_line_ends_with . "<br />\r\n";
//echo ($found_closing_tag_above) ? "Found Closing Tag " : "NO CLOSING TAG ";
//echo " - " .  strripos(trim($output[$i-1]),'&lt;/', 0);
//echo " - " .  $output[$i-1];
//echo "<br>" . $count . "<br>\r\n";
                    $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", ($count > 0) ? $count : 0);
                    echo $indent . $output[$i] . "<br />";
                }

                echo "</p>" . PHP_EOL . PHP_EOL;
            }
        }
    }


    function echoThis($array, $indent = -1)
    {
        $indent++;

        if (gettype($array) == "array") {
            foreach ($array as $key => $value) {
                $type = gettype($value);
                switch ($type) {
                    case "NULL":
                        break;
                    case "integer":
                    case "string":
                        echo str_repeat("&nbsp;&nbsp;", $indent) . "{$key}: $value <br />\r\n";
                        break;
                    case "array":
                        echo str_repeat("&nbsp;&nbsp;", $indent) . "{$key}:<br />\r\n";
                        echoThis($value, $indent);
                        break;
                    default:
                        echo "$key $value type: $type * <br />\r\n";
                }
            }
        } else {
            echo str_repeat("_", $indent) . "$array <br />\r\n";
        }
    }

    function dumpArray($array, $msg){
        foreach($array as $key => $value){
            if(is_array($value)){
                $msg = dumpArray($value, $msg);
            } elseif(!empty($value)) {
                $msg .= "$key: $value <br>\r\n";
            }
        }
        return $msg;
    }


    function printAllPkgs($response)
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
            $msg = dumpArray($item, $msg);
            $msg .= "</div>";
        }
        $msg .= "<div style='clear:both;'></div>";
        return $msg;
    }

    function printPkgList($response, $Start)
    {
        $array = json_decode(json_encode($response), true);

        $msg  = "<div id='wrapper'>\r\n";
        $msg .= "Tours for " . $Start . "<br />";
        $msg .= "<p>\r\n";

        $msg .= "Token:       " . $array['OTA_PkgAvailRQResult']['EchoToken'] . "<br />\r\n";
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
            $msg .= "<div class='blueBox'>";
            $msg .= "DepartureAirport: " . $value['Flight']['DepartureAirport']['LocationCode'] . "<br />\r\n";
            $msg .= "ArrivalAirport:   " . $value['Flight']['ArrivalAirport']['LocationCode']   . "<br />\r\n";
            $msg .= "FlightNumber:     " . $value['Flight']['OperatingAirline']['FlightNumber'] . "<br />\r\n";

            $list = array( 'DepartureDateTime', 'ArrivalDateTime', 'TravelCode', 'Duration', 'CheckInDate');
            foreach($list as $target) {
                $msg .= "{$target}:   " . $value['Flight'][$target]   . "<br />\r\n";
            }
            $msg .= "</div>\r\n";
        }

//        echo "<pre>";
//        print_r($array['OTA_PkgAvailRQResult']);
        $msg .= "</div>\r\n";
        return $msg;
    }

}