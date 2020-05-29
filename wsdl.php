<!doctype html>
<html lang="en">
<head>
    <title>WSDL File</title>
    <style>
        body {background-color:black; color:white; font-family: Arial, serif;}
        .box {margin-left:10px; float:left; padding:5px; border: red solid 1px; border-radius:10px; background-color:#333333;}
    </style>
</head>
<body>
<?php
require_once('initialize.php');

// Create Contact class
class Contact {
    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
/*
 * During development, WSDL caching may be disabled by the use of the soap.wsdl_cache_ttl php.ini
 * setting otherwise changes made to the WSDL file will have no effect until soap.wsdl_cache_ttl is expired.
 */
ini_set('soap.wsdl_cache_enabled', '0');
try{

    echo '<pre>';
    $wsdl = $api->getWsdl();
//  echo $wsdl;

    $opts = [ 'ssl'=> ['verify_peer'=>false, 'verify_peer_name'=>false ] ];
    $context = stream_context_create($opts);
    $soapClientOptions = [ 'stream_context' => $context, 'soap_version' => SOAP_1_2, 'cache' => WSDL_CACHE_NONE ];

    $uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';
    $client = new SoapClient( $uri, $soapClientOptions );

    echo "<p class='box'>";
    echo 'get_class_methods: ';
    $class_methods = get_class_methods($client);
    print_r($class_methods);
    echo "</p>\r\n";

    echo "<p class='box'>";
    echo '__getFunctions: ';
    $classFunctions = $client->__getFunctions();
    print_r($classFunctions);
    echo "</p>\r\n";

    echo "<p class='box'>";
    echo '__getTypes: ';
    $classTypes = $client->__getTYPES();
    print_r($classTypes);
    echo "</p>\r\n";

    echo "<p class='box'>";
    $function = "OTA_AirLowFareSearchRQ";
    $credentials = ["AgentLogin"=>$AgentLogin, "AgentPassword"=>$AgentPassword, "ServiceId"=>$ServiceId];
    $parameters = ["AirLowFareSearchRQ"=>"", "Credentials"=>$credentials];
    $arguments = array('parameters'=>$parameters);
                         //__soapCall($function, $arguments, $options = array(), $input_headers = null, &$output_headers = null);
    $fareSearch = $client->__soapCall($function, $arguments);
    print_r($fareSearch);
    echo "</p>\r\n";

} catch (Exception $e) {
    print_r($e);
}

?>
</body>
</html>
