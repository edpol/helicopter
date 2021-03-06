<?php
namespace Takeflite;

defined('DS')             ? null : define('DS', DIRECTORY_SEPARATOR);
defined('CACHE_LIFETIME') ? null : define('CACHE_LIFETIME', 60 * 60 * 24);
defined('WSDL_FOLDER')    ? null : define('WSDL_FOLDER', __DIR__ . DS . 'xml_files');
defined('WSDL_FILE')      ? null : define('WSDL_FILE', WSDL_FOLDER . DS . 'wsdl.xml');

// DEVELOPMENT
defined('ENDPOINT_LOCATION') ? null : define('ENDPOINT_LOCATION', 'https://apps8.tflite.com/PublicService/Ota.svc/mex/');
defined('WSDL_ADDR')      ? null : define('WSDL_ADDR', 'https://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl');
defined('TRACE')          ? null : define('TRACE', true);

//PRODUCTION
//defined('WSDL_ADDR')      ? null : define('WSDL_ADDR', 'https://apps6.tflite.com/PublicService/Ota.svc/mex?wsdl');
//defined('TRACE')          ? null : define('TRACE', false);

require_once('credentials.php');

require_once('Api.php');
$api = new Api($AgentLogin, $AgentPassword, $ServiceId);

require_once('Request.php');
$request = new Request($AgentLogin, $AgentPassword, $ServiceId);

require_once('Consume.php');
$consume = new Consume();

require_once('functions.php');