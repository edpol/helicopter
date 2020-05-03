<?php
require 'functions.php';
require 'lib/nusoap.php';
$server=new nusoap_server();
$server->configureWSDL("soap","urn:soap");
$server->register(
				"price",						// method
				array("name"=>"xsd:string"),	// input
				array("return"=>"xsd:integer")	// output
				);
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
		