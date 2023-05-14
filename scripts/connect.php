<?php
    function connect()
    {
        $db = new mysqli("fakebankdawidordon.mysql.database.azure.com","dawid","lphR9to/q=o#yZjB;'=>","fakebank");
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