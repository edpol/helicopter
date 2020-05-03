<!doctype html>
<html lang="en">
<head>
    <title>WSDL File</title>
    <style>
        body {
            background-color:black;
            color:white;
            font-family: Arial, serif;
        }
    </style>
</head>
<body>
<?php
require_once('initialize.php');

/*
 * During development, WSDL caching may be disabled by the use of the soap.wsdl_cache_ttl php.ini
 * setting otherwise changes made to the WSDL file will have no effect until soap.wsdl_cache_ttl is expired.
 */
ini_set('soap.wsdl_cache_enabled', '0');
try{

    echo '<pre>';
$wsdl = $api->getWsdl();
//echo $wsdl;

//    $client = new SoapClient($file);
    $uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';
    $client = new SoapClient($uri, ['trace' => 1, 'verify_peer' => false, 'verify_peer_name' => false ] );
















    echo 'get_class_methods: ';
    $class_methods = get_class_methods($client);
    print_r($class_methods);

    echo '__getFunctions: ';
    $classFunctions = $client->__getFunctions();
    print_r($classFunctions);

} catch (Exception $e) {
    print_r($e);
}

?>
</body>
</html>
