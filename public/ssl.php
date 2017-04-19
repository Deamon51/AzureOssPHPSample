<?php

$uri = 'https://nosqljoel.documents.azure.com:10250';

$context = stream_context_create([
    'ssl' => [
        'capture_peer_cert' => true,
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
    ],
]);
$contents = file_get_contents($uri, false, $context);
$response = stream_context_get_params($context);

$certificateProperties = openssl_x509_parse($response['options']['ssl']['peer_certificate']);
var_dump($certificateProperties);
var_dump(strlen($contents));

?>