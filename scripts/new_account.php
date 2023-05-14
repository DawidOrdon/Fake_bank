<?php
    
    function new_account($db, $iban, $balance,$name)//nowe konto
    {
        $sql=$db->prepare("INSERT INTO `account` (`iban`, `name`, `user_id`, `balance`) VALUES (?, ?, ?, ?)");
        $sql->bind_param('ssid',$iban,$name,$_SESSION['user_id'],$balance);
        $sql->execute();
    }
    function new_investment($db, $iban, $percent,$name)//nowe konto oszczędnościowe
    {
        $sql=$db->prepare("INSERT INTO `investment` (`iban`, `user_id`, `name`, `value`, `interest`, `percent`, `flex`) VALUES (?, ?, ?, 0, 0, ?, 0)");
        $sql->bind_param('sssd',$iban,$_SESSION['user_id'],$name,$percent);
        $sql->execute();
    }
    function new_credit($db, $iban, $percent,$name,$value)//nowe kredyt
    {
        $sql=$db->prepare("INSERT INTO `credit` (`iban`, `user_id`, `name`, `value`, `interest`, `percent`) VALUES (?, ?, ?, ?, 0, ?)");
        $sql->bind_param('sssdd',$iban,$_SESSION['user_id'],$value,$name,$percent);
        $sql->execute();
    }
    
?>