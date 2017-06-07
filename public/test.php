<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
//require_once 'HTTP/Request2.php';
//require '../vendor/autoload.php';
//use GuzzleHttp\Client;


//$request = new GuzzleHttp\Client('https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/sentiment');
//$url = $request->getUrl();

$SubKey=getenv("SubKey");
echo $SubKey;
$headers = array(
    // Request headers
    'Content-Type' => 'application/json',
    'Ocp-Apim-Subscription-Key' => $SubKey,
);

$request->setHeader($headers);

$parameters = array(
    // Request parameters
);

$url->setQueryVariables($parameters);

$request->setMethod(HTTP_Request2::METHOD_POST);

// Request body
$request->setBody("{body}");

try
{
    $response = $request->send();
    echo $response->getBody();
}
catch (HttpException $ex)
{
    echo $ex;
}

?>