<?php
    
    function new_account($db, $iban, $balance,$name)
    {
        $sql=$db->prepare("INSERT INTO `account` (`iban`, `name`, `user_id`, `balance`) VALUES (?, ?, ?, ?)");
        $sql->bind_param('ssid',$iban,$name,$_SESSION['user_id'],$balance);
        $sql->execute();
    }
    
?>