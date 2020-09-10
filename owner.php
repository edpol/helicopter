<?php

echo 'Current script owner: ' . get_current_user() . "<br>";

if(function_exists('posix_getpwuid')) {
    $username = posix_getpwuid(posix_geteuid())['name'];
    echo "Name: $username <br>";
}

echo (extension_loaded('soap')) ? "SOAP is loaded" : "NO SOAP";
