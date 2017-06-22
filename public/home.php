<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
require '../vendor/autoload.php';

session_start();
ob_start(); 
include_once 'dbconnect.php';
include_once 'mongodbconnect.php';
//include_once 'textanalytics.php';

if (!isset($_SESSION['userSession'])) {
 header("Location: index.php");
}

$usession = $_SESSION['userSession'];
$query = $DBcon->query("SELECT * FROM users WHERE username='$usession'");
$userRow=$query->fetch_array();
$DBcon->close();


if (isset($_POST['btn-notePost'])) {
  $insertOneResult = $collection->insertOne([
    'name' => $userRow['username'],
    'note' =>  $_POST['note'],
    'posted_at' => new DateTime(),
  ]);
  header("Location: home.php");
  unset($_POST['btn-notePost']);
}

if (isset($_POST['btn-noteEdit'])) {
  $updateResult = $collection->updateOne(
    ['_id' =>  new \MongoDB\BSON\ObjectID($_GET['id'])],
    ['$set' => ['note' => $_POST['noteEdit']]]
);
  header("Location: home.php");
  unset($_POST['noteEdit']);
}
  
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="icon" type="image/ico" href="favicon.ico">
<title>Welcome on Azure OSS sample - <?php echo $userRow['email']; ?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="assets/style.css" type="text/css"  />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>

<header>
  <div class="headerLegend">
    <ul class="menu">
      <li>
        <img class="settingsImg" src="images/settings.png" alt="Settings"  onclick="$('#settings').toggle();">
      </li>
      <li>
        <a href="#" class="headerUsername"><?php echo $userRow['username'];?></a>
      </li>
    </ul>
  </div>
</header>
<div id="settings">
  <ul>
    <li><a href="#">In Progress</a></li>
    <li><a href="#">In Progress</a></li>
    <li><a href="logout.php?logout">Logout</a></li>
  </ul>
</div>

<div class="container">
  <div class="notesListBlock">
    <h2 class="signInTitle">All notes.</h2>
    <div>
      <?php
         $cursor = $collection->find(['name' => $userRow['username']],['sort' => ['_id' => -1]]);
          foreach ($cursor as $document) {
            echo '<div class="noteBlock"><a href="?id='.$document['_id'].'">';
            echo substr($document['note'],0,50);
            echo '</a><a href="?rm='.$document['_id'].'" onclick="myFunction()"><img class="trash" src="images/garbage.svg" alt="Trash" title="Delete"></a></div>';
              $removeNote = $document['_id'];
              if ($removeNote == $_GET['rm']) {
              $deleteResult = $collection->deleteOne(['_id' => $document['_id']]);
              header( "Location: home.php" );
              }
            }
          if (empty($document['note'])) { 
          echo '<p class="noNote">Nothing yet</p>';
      }

      
    ?>   
    </div>
  </div>
  <div class="takeNoteBlock" <?php if (!empty($_GET['id'])){ echo 'style="display:none;"'; } ?>>  
      <form method="post" >
        <h2 class="signInTitle">Take note.</h2>
      <div class="noteInput"> 
          <textarea name="note" placeholder="Write something ..." required maxlength="1000"></textarea>
      </div>
      <div class="signInSubmit">
        <button type="submit" name="btn-notePost" class="btn-notePost">&nbsp; Save</button>    
      </div>  
      </form>
  </div>
  
  
  <div class="notesBlock">
    <?php
          $cursor = $collection->find(['name' => $userRow['username']]);
          //echo $document['posted_at']->format('Y-m-d\TH:i:s.u');
  foreach ($cursor as $document){
  if ($document['_id'] == $_GET['id']) {
      echo '<div>
        <a href="home.php"><img class="back" src="images/restart.svg" alt="Back" title="Back to take a post"></a>  
      </div><form method="post">
      <div id="note'.$_GET['id'].'" class="fullNoteBlock" contenteditable="true" title="Tap to edit">'.$document['note'].'
      </div><textarea id="textareaNone" name="noteEdit"></textarea><button type="submit" name="btn-noteEdit" class="btn-noteEdit">Update</button></form>';
  }}
       if (!empty($document['note']) && empty($_GET['id'])) { 
          echo '<p class="noNote"><b>Display or edit a note</b><br/>Click on note left</p>';
      }
      elseif (empty($document['note'])) {
        echo '<p class="noNote"><b>Write a note</b><br/>Type your first note above</p>';
      }
    ?>
    <?php 
      if (!empty($_GET['id'])) {
      echo '<div class="noteAnalyticsBlock">
              <div class="noteAnalytics">
                <span class="lskAnalytics">Language : '.$lngAnalytic.'</span>
                <span class="lskAnalytics">Sentiment : '.$sntAnalytic.'</span>
                <span class="lskAnalytics">Key Phrase : '.$kpAnalytic.'</span>
              </div>
            </div>';
    } 
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

//echo $document['note'];

$document = $document['note'];
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
    global $lngAnalytic;
}
catch (HttpException $ex)
{
    echo $ex;
}
    ?>
  </div>
<div> 
  <script>
$(function getContent(){
    $('.btn-noteEdit').click(function () {
        var mysave = $('.fullNoteBlock').html();
        $('#textareaNone').val(mysave);
    });
});
function deleteFunction() {
    return confirm('Are you sure you want to delete this note?');
    setTimeout(function () {
    window.location.reload(true);}, 0);
}
</script>
</body>
</html>