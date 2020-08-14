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

    function echoThis($string)
    {
        $first_character  = true;
        $msg = '';
        $previous_letter_uppercase = false;
        $array = str_split($string);
        foreach ($array as $c) {
 //           $uppercase = ord($c)>=65 && ord($c)<=90;
            if($c === '_'){
                $msg .= ($first_character) ? '' : ' ';
            }else{
                $uppercase = preg_match('~(\p{Lu})~u', $c, $out) ? true : false;
                $msg .= ($uppercase && !$previous_letter_uppercase && !$first_character) ? ' ' . $c : $c;
                $previous_letter_uppercase = $uppercase;
            }
            $first_character = false;
        }
        return $msg;
    }


}