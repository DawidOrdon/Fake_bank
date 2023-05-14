<?php
    session_start();
    include_once("./function.php");
    include_once("./connect.php");
    include_once("./navbar.php");
    $db=connect();
    $sql=$db->prepare("UPDATE `credit` SET `interest` = interest+value*(percent/365/100)");
    $sql->execute();
    $sql=$db->prepare("UPDATE `investment` SET `interest` = interest+value*(percent/365/100)");
    $sql->execute();
?>