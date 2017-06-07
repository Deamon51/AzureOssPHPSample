<?php
        $DBpass=getenv("DbPassword");
        $DBcon = mysqli_init();
        $DBcon->ssl_set(NULL, NULL, "certs/azure_mysql_root.pem", NULL, NULL);
        mysqli_real_connect($DBcon, "mysqljoel.database.windows.net", "joel@mysqljoel", $DBpass, "azure_schema", 3306);

?>