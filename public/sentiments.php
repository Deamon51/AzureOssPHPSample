<?php
// This sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)

$request = new Http_Request2('https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/sentiment');
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
    $name = json_decode($postJson, true);

    $number = $name["documents"][0]["score"];

    $percent = round((float)$number * 100 ) . '%';
    $sntAnalytic = $percent;
    if ($sntAnalytic < 50 ){
       $sntAnalyticSmiley = ':(';
    }
    elseif ($sntAnalytic > 50 ){
    $sntAnalyticSmiley = ':)';
    }
    else {
        $sntAnalyticSmiley = ':|';
    }
}
catch (HttpException $ex)
{
    echo $ex;
}

?>