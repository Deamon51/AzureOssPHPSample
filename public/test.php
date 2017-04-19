<?php
include 'mongodbconnect.php';

              $deleteResult = $collection->deleteOne(['_id' => $_GET['rm']]);
              printf("Deleted %d note\n", $deleteResult->getDeletedCount());

?>