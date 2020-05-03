<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "http://apps8.tflite.com/PublicService/Ota.svc/mex?wsdl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Cookie: __cfduid=d8e2fdcec1e1d64ac1903dace8596954f1588356650"
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
