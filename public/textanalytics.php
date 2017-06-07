<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// This sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)
require_once 'HTTP/Request2.php';

$request = new Http_Request2('https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/languages');

$url = $request->getUrl();

$SubKey=getenv("SubKey");
$headers = array(
    // Request headers
    'Content-Type' => 'application/json',
    'Ocp-Apim-Subscription-Key' => $SubKey
);

$request->setHeader($headers);

$parameters = array(
    // Request parameters
    'numberOfLanguagesToDetect' => '1',
);

$url->setQueryVariables($parameters);

$request->setMethod(HTTP_Request2::METHOD_POST);

//$document['note'];
$document = 'hello';
$json = '{
    "documents": [
    {
    "id": "1",
    "text": '.json_encode($document).'
}]}';

// Request body
$request->setBody($json);


try
{
    $response = $request->send();
    echo $response->getBody();
    
    $postJson =  $response->getBody();
    $name = json_decode($postJson, true);

    $lngAnalytic = $name["documents"][0]["detectedLanguages"][0]["name"];
}
catch (HttpException $ex)
{
    echo $ex;
}

?>