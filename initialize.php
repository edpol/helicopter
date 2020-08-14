<?php
namespace Takeflite;

defined('DS')             ? null : define('DS', DIRECTORY_SEPARATOR);
defined('LIB_PATH')       ? null : define('LIB_PATH', __DIR__);
defined('CACHE_LIFETIME') ? null : define('CACHE_LIFETIME', 60 * 60 * 24 * 7); // 7 days
defined('WSDL_FOLDER')    ? null : define('WSDL_FOLDER', __DIR__ . DS . 'xml_files');
defined('WSDL_FILE')      ? null : define('WSDL_FILE', WSDL_FOLDER . DS . 'wsdl.xml');

// DEVELOPMENT
defined('ENDPOINT_LOCATION') ? null : define('ENDPOINT_LOCATION', 'https://apps8.tflite.com/PublicService/Ota.svc/mex/');
defined('WSDL_ADDR')      ? null : define('WSDL_ADDR', 'https://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl');
defined('TRACE')          ? null : define('TRACE', true);

//PRODUCTION
//defined('WSDL_ADDR')      ? null : define('WSDL_ADDR', 'https://apps6.tflite.com/PublicService/Ota.svc/mex?wsdl');
//defined('TRACE')          ? null : define('TRACE', false);

if(!file_exists(LIB_PATH.DS.'credentials.php')) {
    die(LIB_PATH.'credentials.php not found');
}
require_once(LIB_PATH.DS.'credentials.php');
if(!isset($AgentLogin, $AgentPassword, $ServiceId)){
    die('Missing credentials in the credentials.php file');
}

require_once(LIB_PATH.DS.'Request.php');
$request = new Request($AgentLogin, $AgentPassword, $ServiceId);

require_once(LIB_PATH.DS.'Api.php');
$api = new Api();

require_once(LIB_PATH.DS.'Output.php');
$output = new Output();

require_once(LIB_PATH.DS.'functions.php');