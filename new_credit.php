<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/function.php");
    include_once("./scripts/generate_iban.php");
    include_once("./scripts/find_iban.php");
    include_once("./scripts/new_account.php");
    include_once("./scripts/fake_bank_data.php");
    is_logged();
    $db=connect();
    if(!empty($_POST['name'])&&!empty($_POST['value'])&&is_numeric($_POST['value'])&&$_POST['value']>0&&$_POST['value']<=10000)
    {
        $sql_account=$db->prepare("SELECT iban FROM `account` WHERE user_id= ? limit 1");
        $sql_account->bind_param('i',$_SESSION['user_id']);
        $sql_account->execute();
        $result_account=$sql_account->get_result();
        if($result_account->num_rows!=1)//sprawdzenie czy klient ma zwykle konto
        {
            $iban=generate_iban($db);
            new_account($db,$iban,0,'Pierwsze konto');
        }
        else
        {
            $row_account=$result_account->fetch_assoc();
            $iban=$row_account['iban'];
        }
        
        $sql_bank=$db->prepare("UPDATE `account` SET `balance` = balance - ? WHERE `account`.`iban` = ?");// zabranie pieniędzy z konta banku
        $sql_bank->bind_param('ds',$_POST['value'],$bank_iban);
        $sql_bank->execute();
        $sql_transfer=$db->prepare("INSERT INTO `transfers` (`id`, `title`, `id_user_source`, `id_user_destiny`, `value`, `date`, `accept`) VALUES ('', ?, ?, ?, ?, NOW(), 0)");//zlecenie dodania pieniedzy do konta klienta
        $sql_transfer->bind_param('sssd',$credit_title,$bank_iban,$iban,$_POST['value']);
        $sql_transfer->execute();


        $sql_credit=$db->prepare("SELECT value FROM `percents` WHERE id=2");//pobranie aktualnego oprocentowania
        $sql_credit->execute();
        $result_credit=$sql_credit->get_result();
        $row_credit=$result_credit->fetch_assoc();
        new_credit($db,generate_iban($db),$row_credit['value'],$_POST['value'],$_POST['name']); //wywołanie funkcji która stworzy nowe konto
    }
    else
    {
    
    }
        
    header("Location:main.php");
?>