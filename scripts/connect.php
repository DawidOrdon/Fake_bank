<?php
    function connect()
    {
        $db = new mysqli("localhost","root","","wsb_fake_bank");
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