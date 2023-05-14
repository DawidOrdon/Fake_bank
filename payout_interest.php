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

    if(check_iban($_GET['iban']))
    $sql=$db->prepare("SELECT user_id from investment where iban = ?"); //zabezpieczenie przed podmiana numeru konta
    $sql->bind_param('s', $_GET['iban']);
    $sql->execute();
    $result=$sql->get_result();
    $result=$result->fetch_assoc();
    if(@$result['user_id']==$_SESSION['user_id'])// sprawdzanie właściciela konta
    {
        echo $_GET['iban'];
        $sql_account=$db->prepare("SELECT iban FROM `account` WHERE user_id= ? limit 1"); //pobieranie zwyklego konta
        $sql_account->bind_param('i',$_SESSION['user_id']);
        $sql_account->execute();
        $result_account=$sql_account->get_result();
        if($result_account->num_rows!=1)//sprawdzenie czy klient ma zwykle konto
        {
            $iban_normal=generate_iban($db);
            new_account($db,$iban,0,'Pierwsze konto');
        }
        else
        {
            $row_account=$result_account->fetch_assoc();
            $iban_normal=$row_account['iban'];
        }

        $sql_save=$db->prepare("SELECT interest as 'money' FROM `investment` WHERE iban=?");//pobranie wartosci zaoszczedzonyc pieniedzy
        $sql_save->bind_param('s',$_GET['iban']);
        $sql_save->execute();
        $result_save=$sql_save->get_result();
        $row_save=$result_save->fetch_assoc();
        $save_money=$row_save['money'];

        $sql=$db->prepare("UPDATE `investment` SET `interest` = '0' WHERE `iban` = ?");//wyzerowanie konta oszczednosciowego
        $sql->bind_param('s',$_GET['iban']);
        $sql->execute();
        echo $save_money;
        $title="Konto oszczednosciowe";
        $sql=$db->prepare("INSERT INTO `transfers` (`id`, `title`, `id_user_source`, `id_user_destiny`, `value`, `date`, `accept`) VALUES ('', ?, ?, ?, ?, NOW(), 0)");
        $sql->bind_param('sssd',$title,$_GET['iban'],$iban_normal,$save_money);
        $sql->execute();

        header("Location:./main.php");
    }

    
?>