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

    $opts2 = [ 'ssl'=> ['verify_peer'=>false, 'verify_peer_name'=>false ] ];
    $context2 = stream_context_create($opts2);
    $soapClientOptions2 = [ 'stream_context' => $context2, 'cache' => WSDL_CACHE_NONE ];

    $opts = [ 'http' => [ 'user_agent' => 'PHPSoapClient' ], 'ssl'=> ['verify_peer'=>false, 'verify_peer_name'=>false ] ];
    $context = stream_context_create($opts);
    $soapClientOptions = [ 'stream_context' => $context, 'cache' => WSDL_CACHE_NONE ];

    $options = [ 'trace' => 1, 'verify_peer' => false, 'verify_peer_name' => false ];
    $options2 = [ 'cache_wsdl' => 0 ];

    $uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';
    $client = new SoapClient( $uri, $soapClientOptions2 );

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
