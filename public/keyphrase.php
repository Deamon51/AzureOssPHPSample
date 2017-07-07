<?php
// This sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)

$request = new Http_Request2('https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/keyPhrases');
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
);

$url->setQueryVariables($parameters);

$request->setMethod(HTTP_Request2::METHOD_POST);

//$document = 'Hello ! How are you my friend ? Do you know where is New York ?';
$cursor = $collection->find();
foreach ($cursor as $document){
if ($document['_id'] == $_GET['id']) {    
   $document = $document['note'];
$json = '{
    "documents": [
    {
    "id": "1",
    "text": '.json_encode($document).'
}]}';
}
}
// Request body
$request->setBody($json);

try
{
    $response = $request->send();
    $response->getBody();

    $postJson =  $response->getBody();
    $keyphrase= json_decode($postJson, true);

    $kpAnalytic = $keyphrase["documents"][0]["keyPhrases"][0];
    $kpAnalytic1 = $keyphrase["documents"][0]["keyPhrases"][1];
    $kpAnalytic2 = $keyphrase["documents"][0]["keyPhrases"][2];

}
catch (HttpException $ex)
{
    echo $ex;
}

?>
