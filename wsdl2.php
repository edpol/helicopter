<?php
defined('WSDL_ADDR')      ? null : define('WSDL_ADDR', 'https://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl');

$http = ['method' => 'GET', 'header' => "Content-Type: application/soap+xml\r\n" . "Charset=utf-8\r\n",];
$ssl  = ['verify_peer' => false, 'verify_peer_name' => false];
$opts = [ 'http' => $http, 'ssl' => $ssl ];
$params = [
    'soap_version' => SOAP_1_2,
    'cache' => WSDL_CACHE_NONE,
    'trace' => true,
    'stream_context' => stream_context_create($opts)
];

//            $options['soapAction'] ='http://tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_PkgAvailRQ';

$client = null;
try {
    $client = new SoapClient(WSDL_ADDR, $params);
    return $client;
} catch (SoapFault $e) {
    var_dump($client);
    echo "<p><b><u>catch</u>:</b> ";
    echo htmlentities($e->getMessage());
    echo "</p>" . PHP_EOL . PHP_EOL;

    echo $client->__getLastRequest();
}
