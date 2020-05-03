<?php

//stream_context_create and file_get_contents
//$post_date = [  ];
//'content' => http_build_query($post_date);

    $uri = 'http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl';

    $ssl = [
            "verify_peer" => false,
            "verify_peer_name" => false,
    ];

    $http = [
        'method' => 'GET',
        'header' => "Content-Type: application/soap+xml\r\n" . "Charset=utf-8\r\n",
    ];
    $options = ['http' => $http, 'ssl' => $ssl ];
    $context =  stream_context_create($options);
    $xml = file_get_contents($uri, null, $context);
    echo $xml;
