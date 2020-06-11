<?php

function dumpAttributes($prefix, $separator, $data, $newline)
{
    $eol = ($newline) ? "<br />\r\n" : "";
    foreach ($data->attributes() as $key => $value) {
        echo "{$prefix} {$key}{$separator} {$value} {$eol}";
    }
}
