<?php

function dumpAttributes($prefix, $separator, $data, $newline, $dump=false )
{
    if($dump) echo "1 ";
    $eol = ($newline) ? "<br />\r\n" : "";
    foreach ($data->attributes() as $key => $value) {
        if($dump) echo "2 ";
        echo "{$prefix} {$key}{$separator} {$value} {$eol}";
    }
    if($dump) echo "3 ";
    if($dump){
        echo "<pre>";
        print_r($data);
    }
}

function dumpErrors($data){

// ERROR: didn't like the data sent
    if(isset($data->Errors)){
        $error_count = 1;
        foreach($data->Errors as $Error){
            echo "Error #" . $error_count++ . ": <br />\r\n";
            dumpAttributes("&nbsp;&nbsp;", " = ", $data->Errors->Error,true);
        }
    }
}


function dumpCatch($e, $soapClient, $location=''){
    echo "<p><b><u>catch:</u></b> ";
    echo htmlentities($e->getMessage()) . "<br />\r\n" . $location;
    echo "</p>" . PHP_EOL . PHP_EOL;

    if ($soapClient instanceof SoapClient) {
        $array = ['__getLastRequest', '__getLastResponse', '__getLastRequestHeaders', '__getLastResponseHeaders'];
        $count = 0;
        foreach($array as $method){
            echo "<p>";
            echo "<b><u>{$method}:</u></b> <br />" . PHP_EOL;
            $string = htmlentities($soapClient->$method());
//echo $string . "<br>\r\n";
            $tmp = str_replace('xmlns:'  , "~~&nbsp;&nbsp;&nbsp;&nbsp;xmlns:", $string);
            $tmp = str_replace(PHP_EOL . '&lt;env:Envelope', "~~&lt;env:Envelope", $tmp);
            $tmp = str_replace('&gt;&lt;', "&gt;~~&lt;", $tmp);
            $output = explode("~~",$tmp);
//print_r($output);

            $count = 0;
            $limit = count($output);
            echo $output[0] . "<br />";
            for($i=1; $i<$limit; $i++){
                // if the line starts with '</' then we indent less
                $this_line_ends_with = substr($output[$i], 0,5);
                $found_closing_tag_above = (strripos($output[$i-1],'&lt;/', 0)!==false || strripos($output[$i-1],'?&gt;', 0)!==false);

                if($this_line_ends_with == '&lt;/') {
                    if ($count > 0) $count--;
                }elseif(substr($output[$i-1],-5) != '/&gt;') {
                    if(substr($output[$i-1], -4) == '&gt;')
                        if(!$found_closing_tag_above) $count++;
                }
//echo substr($output[$i-1],-5) . "<br />\r\n";
//echo  $this_line_ends_with . "<br />\r\n";
//echo ($found_closing_tag_above) ? "Found Closing Tag " : "NO CLOSING TAG ";
//echo " - " .  strripos(trim($output[$i-1]),'&lt;/', 0);
//echo " - " .  $output[$i-1];
//echo "<br>" . $count . "<br>\r\n";
                $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", ($count>0)?$count:0 );
                echo $indent . $output[$i] . "<br />";
            }

            echo "</p>" . PHP_EOL . PHP_EOL;
        }
    }
}


function echoThis($array, $indent = -1)
{
    $indent++;

    if(gettype($array)=="array"){
        foreach ($array as $key => $value)
        {
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
    }else{
        echo str_repeat("_", $indent) . "$array <br />\r\n";
    }
}