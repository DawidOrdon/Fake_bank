<?php
    session_start();
    include_once("./function.php");
    include_once("./connect.php");
    include_once("./navbar.php");
    $db=connect();
    $sql=$db->prepare("SELECT * FROM `transfers` WHERE accept = 0");
    $sql->execute();
    $result=$sql->get_result();
    while($row=$result->fetch_assoc())
    {
        $sql=$db->prepare("UPDATE `account` SET `balance` = balance + ? WHERE `account`.`iban` = ? ");
        $sql->bind_param('ds',$row['value'],$row['id_user_destiny']);
        $sql->execute();
    }
    $sql=$db->prepare("UPDATE `transfers` SET `accept` = '1' WHERE `accept` = '0'");
    $sql->execute();
?>