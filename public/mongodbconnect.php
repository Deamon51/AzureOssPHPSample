<?php
  // connect to MongoDB
$MongoCon=getenv("MongoDbClient");
$m = new MongoDB\Client($MongoCon);
$collection = $m->MyDatabase->MyCollection;
?>