<?php
    function connect()
    {
        $db = mysqli_init();
        mysqli_ssl_set($db,NULL,NULL, "./BaltimoreCyberTrustRoot.crt.pem", NULL, NULL);
        mysqli_real_connect($db, "fakebankdawidordon.mysql.database.azure.com", "dawid", "lphR9to/q=o#yZjB;'=>", "fakebank", 3306, MYSQLI_CLIENT_SSL);
        //$db = new mysqli("fakebankdawidordon.mysql.database.azure.com","dawid","lphR9to/q=o#yZjB;'=>","fakebank");
        if (mysqli_connect_errno() != 0)
        {
            echo mysqli_error($db);
            return false;
        }
        else
        {
            $db -> query("set names utf8");
            return $db;
        }
    }
?>