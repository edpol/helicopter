<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

defined('WSDL_ADDR')      ? null : define('WSDL_ADDR', 'https://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl');

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER => array( 'Content-Type: application/soap+xml' ),
));

$response = curl_exec($curl);

curl_close($curl);

file_put_contents("xml_files/wsdl.xml", $response);

//header('Content-type: text/xml');
//echo $response;

ini_set('soap.wsdl_cache_enabled', '0');
$http = ['method' => 'GET', 'header' => "Content-Type: application/soap+xml\r\n" . "Charset=utf-8\r\n",];
$ssl  = ['verify_peer' => false, 'verify_peer_name' => false];
$options = ['http' => $http, 'ssl' => $ssl, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE, 'trace' => true ];

//$client = new SoapClient("xml_files/wsdl.xml", $options);
try {
    $client = new SoapClient(WSDL_ADDR, $options);
}catch(Exception $e){
var_dump($client);
    echo "<p><b><u>catch</u>:</b> ";
    echo htmlentities($e->getMessage());
    echo "</p>" . PHP_EOL . PHP_EOL;

    echo $client->__getLastRequest();
}