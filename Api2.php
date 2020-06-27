<?php

class Api2
{
    public function allPackages()
    {
        // create the root element
        $xml = simplexml_load_string('<courses></courses>');

    }
}

$opts = [ 'ssl'=> ['verify_peer'=>false, 'verify_peer_name'=>false ] ];
$context = stream_context_create($opts);
$soapClientOptions = [ 'stream_context' => $context, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE ];
$uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';

try {
    $client = new SoapClient($uri, $soapClientOptions);
}catch(Exception $e){
    echo 'Caught exception: ' . $e->getMessage() . "<br />\r\n";
}
// dump wsdl
//header('Content-type: text/xml');
echo "<pre>";
print_r($client);


//$methods_soap = get_class_methods($wsdl);

//$xml = simplexml_load_string('<courses></courses>');
//$methods_simple = get_class_methods($xml);
//
//echo "<pre>";
//print_r ($methods_soap);
//print_r ($methods_simple);

/*
<soap:Envelope
    xmlns:soap="http://www.w3.org/2003/05/soap-envelope"
    xmlns:tak="tflite.com/TakeFliteExternalService/"
    xmlns:ns="http://www.opentravel.org/OTA/2003/05">
    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <wsa:Action>tflite.com/TakeFliteExternalService/TakeFliteOtaService/OTA_PkgAvailRQ</wsa:Action>
        <wsa:To>https://apps8.tflite.com/PublicService/Ota.svc</wsa:To>
    </soap:Header>
    <soap:Body>
        <tak:OTA_PkgAvailRQ>
            <tak:PkgAvailRQ EchoToken="test">
                <ns:PackageRequest TravelCode="*"/>
            </tak:PkgAvailRQ>
            <tak:Credentials>
                <tak:AgentLogin>{{AgentLogin}}</tak:AgentLogin>
                <tak:AgentPassword>{{AgentPassword}}</tak:AgentPassword>
                <tak:ServiceId>{{ServiceId}}</tak:ServiceId>
            </tak:Credentials>
        </tak:OTA_PkgAvailRQ>
    </soap:Body>
</soap:Envelope>

$args = array("zipCodeList"=>'10001');
try {
$z = $client->LatLonListZipCode($args);
} catch (SoapFault $e) {
    echo $e->faultcode;
}

*/