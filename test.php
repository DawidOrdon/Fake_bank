<?php
/*
    $password="11111";
    $hash=password_hash($password,PASSWORD_DEFAULT, ['cost' => 6]);
    $hash='$2y$06$vTjy41wR1.AaqZK896vqLumpMboNVcdoT9mM99Q4PindXbVVQZijy';
    if(password_verify($password,$hash))
    {
        echo"ok";
    }
    else
    {
        echo"error";
    }*/
    include_once("./scripts/get_random_pattern_id.php");
    include_once("./scripts/connect.php");
    //$db=connect();
    //get_random_pattern_id($db,472142250751);
    session_start();
    //session_destroy();
    echo $_SESSION['user_id'];
?>