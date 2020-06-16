<?php

function dumpAttributes($prefix, $separator, $data, $newline)
{
    $eol = ($newline) ? "<br />\r\n" : "";
    foreach ($data->attributes() as $key => $value) {
        echo "{$prefix} {$key}{$separator} {$value} {$eol}";
    }
}

function dumpErrors($data){

// ERROR: didn't like the data sent
    if(isset($data->Errors)){
        $error_count = 1;
        foreach($data->Errors as $Error){
            echo "Error #" . $error_count++ . ": <br />\r\n";
            dumpAttributes("&nbsp;&nbsp;", " = ", $Error,true);
        }
    }
}