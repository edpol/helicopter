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