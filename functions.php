<?php
namespace Takeflite {

    function dumpCatch($e, $soapClient, $location = '')
    {
        echo "<p><b><u>Catch:</u></b> ";
        echo "<span style='color:yellow;'>" . htmlentities($e->getMessage()) . "</span><br />\r\n" . $location;
        echo "</p>" . PHP_EOL . PHP_EOL;

        if ($soapClient instanceof SoapClient) {
            $array = ['__getLastRequest', '__getLastResponse', '__getLastRequestHeaders', '__getLastResponseHeaders'];
            $count = 0;
            foreach ($array as $method) {
                echo "<p>";
                echo "<b><u>{$method}:</u></b> <br />" . PHP_EOL;
                $string = htmlentities($soapClient->$method());

                $tmp = str_replace(
                    array('xmlns:', PHP_EOL . '&lt;env:Envelope', '&gt;&lt;'),
                    array("~~&nbsp;&nbsp;&nbsp;&nbsp;xmlns:", "~~&lt;env:Envelope", "&gt;~~&lt;"),
                    $string);
                $output = explode("~~", $tmp);

                $count = 0;
                $limit = count($output);
                echo $output[0] . "<br />";
                for ($i = 1; $i < $limit; $i++) {
                    // if the line starts with '</' then we indent less
                    $this_line_ends_with = substr($output[$i], 0, 5);
                    $found_closing_tag_above = (strripos($output[$i - 1], '&lt;/', 0) !== false || strripos($output[$i - 1], '?&gt;', 0) !== false);

                    if ($this_line_ends_with === '&lt;/') {
                        if ($count > 0) {
                            $count--;
                        }
                    } elseif (substr($output[$i - 1], -5) !== '/&gt;') {
                        if (substr($output[$i - 1], -4) === '&gt;' && !$found_closing_tag_above) {
                            $count++;
                        }
                    }

                    $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", ($count > 0) ? $count : 0);
                    echo $indent . $output[$i] . "<br />";
                }

                echo "</p>" . PHP_EOL . PHP_EOL;
            }
        }
    }

    // this is for an array
    function dumpErrorsArray($data)
    {
        if (is_bool($data)) {
            $msg = ($data) ? 'TRUE ' : 'FALSE ';
        } else {
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

    function echoThis($string)
    {
        $first_character = true;
        $msg = '';
        $previous_letter_uppercase = false;
        $array = str_split($string);
        foreach ($array as $c) {
            //           $uppercase = ord($c)>=65 && ord($c)<=90;
            if ($c === '_') {
                $msg .= ($first_character) ? '' : ' ';
            } else {
                $uppercase = preg_match('~(\p{Lu})~u', $c, $out) ? true : false;
                $msg .= ($uppercase && !$previous_letter_uppercase && !$first_character) ? ' ' . $c : $c;
                $previous_letter_uppercase = $uppercase;
            }
            $first_character = false;
        }
        return $msg;
    }

    function dumpTimeStamps($time)
    {
        $first = true;
        $previous = 0;
        foreach ($time as $loc => $stamp) {
            if($first){
                $first=false;
                $diff = 0;
            }else{
                $diff = $stamp - $previous;
            }
            echo '<span class="label3">' . $loc . '</span>' . date(' Y-m-d H:m:s ', $stamp) . ($diff) . '<br>';
            $previous = $stamp;
        }
    }

}