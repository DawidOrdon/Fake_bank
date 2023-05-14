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
        $sql_account=$db->prepare("UPDATE `account` SET `balance` = balance + ? WHERE `account`.`iban` = ? ");// operacje na zwykle konta
        $sql_account->bind_param('ds',$row['value'],$row['id_user_destiny']);
        $sql_account->execute();

        $sql_credit=$db->prepare("SELECT * FROM `credit` WHERE `iban` = ? ");// operacje na konta kredytowe
        $sql_credit->bind_param('s',$row['id_user_destiny']);
        $sql_credit->execute();
        $result_credit=$sql_credit->get_result();
        if(mysqli_num_rows($result_credit)==1)//oblicznie kwoty jaka będzie odliczona od oprocentowania a jaka od kapitalu
        {
            $row_credit=$result_credit->fetch_assoc();
            if($row_credit['interest']+$row_credit['value']<=$row['value'])//splacenie kredytu
            {
                $sql_drop_credit=$db->prepare("DELETE FROM credit WHERE `credit`.`iban` = ?");
                $sql_drop_credit->bind_param('s',$row['id_user_destiny']);
                $sql_drop_credit->execute();
            }
            else
            {
                $interest=$row_credit['interest']-$row['value'];
                if($interest<0)//kwota wplacona jest wieksza od odsetek
                {
                    $interest=0;
                    $balance=$row_credit['value']-($row['value']-$row_credit['interest']);
                }
                else// kwota wplacona jest mniejsza od odsetek
                {
                    $interest=$row_credit['interest']-$row['value'];
                    $balance=$row_credit['value'];
                }
                $sql=$db->prepare("UPDATE `credit` SET `value` = ?, `interest` = ? WHERE `credit`.`iban` = ?");
                $sql->bind_param('dds',$balance,$interest,$row['id_user_destiny']);
                $sql->execute();
            }
            

        }

        $sql_account=$db->prepare("UPDATE `investment` SET `value` = value + ? WHERE `iban` = ? ");// operacje na konto oszczednosciowe
        $sql_account->bind_param('ds',$row['value'],$row['id_user_destiny']);
        $sql_account->execute();

    }
    $sql_update_status=$db->prepare("UPDATE `transfers` SET `accept` = '1' WHERE `accept` = '0'");
    $sql_update_status->execute();
?>