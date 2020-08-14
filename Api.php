<?php
namespace Takeflite;
/*
 * all the calls to the api
 * I manually check the modify date of the WSDL file against my constant, CACHE_LIFETIME
 * That function is supposed to exist in
 */
class Api {
    private $opts;

    public function __construct()
    {
        $http = array('method' => 'GET', 'header' => "Content-Type: application/soap+xml\r\n" . "Charset=utf-8\r\n",);
        $ssl  = array('verify_peer' => false, 'verify_peer_name' => false);
        $this->opts = array('http' => $http, 'ssl' => $ssl);
    }

    /*
     * output the wsdl file to wsdl.xml if the existing one is more than one day old
     * return that the file exists true or false
     */
    public function getWsdl()
    {
        // if the folder does not exist, create it
        $wsdl_folder = WSDL_FOLDER;
        if(!mkdir($wsdl_folder, 0777, true) && !is_dir($wsdl_folder)) {
            die("Could not create folder {$wsdl_folder}. Create manually and grant write access. ");
        }

        // find out when the cache was last updated
        if (file_exists(WSDL_FILE)) {
            $modified = filemtime(WSDL_FILE);
        }

        // create or update the cache if necessary
        if (!isset($modified) || $modified + CACHE_LIFETIME < time()) {
            if($string = @file_get_contents(WSDL_ADDR, false, stream_context_create($this->opts))) {
                //file_put_contents(WSDL_FILE, $string);
                $xml = new \SimpleXMLElement($string);
                $doc = new \DOMDocument('1.0', 'utf-8');
                $doc->formatOutput = true;
                $node = dom_import_simplexml($xml);  // convert simpleXML data to DOM node
                $node = $doc->importNode($node, true);  // import the converted xml can be imported into the empty dom document
                $doc->appendChild($node);
                if(@$doc->save(WSDL_FILE)===false) {
                    die("Could not create file " . WSDL_FILE . ". Check access permissions to folder " . WSDL_FOLDER . '.');
                }
            } else {
                die("Could not get file " . WSDL_FILE . ". ");
            }
            return true;
        }

        return file_exists(WSDL_FILE);
    }

    public function setupHeader($function)
    {
        $action_data = "tflite.com/TakeFliteExternalService/TakeFliteOtaService/" . $function;
        $to_data = 'https://apps8.tflite.com/PublicService/Ota.svc';
        $wsa = 'http://www.w3.org/2005/08/addressing';
        $action = new \SoapHeader($wsa, 'Action', $action_data, false);
        $to = new \SoapHeader($wsa, 'To', $to_data, false);
        return array('Action' => $action, 'To' => $to);
    }

    // $function is the tflite api function to be called
    public function instantiateSoapClient($function){
        if($this->getWsdl()) {
            ini_set('soap.wsdl_cache_enabled', '0');
            $params = array(
                'soap_version' => SOAP_1_2,
                'cache' => WSDL_CACHE_NONE,
                'trace' => TRACE,
                'stream_context' => stream_context_create($this->opts)
            );

            $client = null;
            try {
                $client = new \SoapClient(WSDL_FILE, $params);
                $headerBody = $this->setupHeader($function);
                $client->__setSoapHeaders($headerBody);
                return $client;
            } catch (\SoapFault $e) {
                echo "<pre>";
                dumpCatch($e, $client);
                echo "</pre>";
            }
        }
        return false;
    }
}
