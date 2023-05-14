<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/function.php");
    include_once("./scripts/generate_iban.php");
    include_once("./scripts/find_iban.php");
    include_once("./scripts/new_account.php");
    is_logged();
    $db=connect();
    if(!empty($_POST['name'])&&!empty($_POST['value'])&&is_numeric($_POST['value'])&&$_POST['value']>0)
    {
        
        $sql=$db->prepare("SELECT value FROM `percents` WHERE id=2");//pobranie aktualnego oprocentowania
        $sql->execute();
        $result=$sql->get_result();
        $row=$result->fetch_assoc();
        new_credit($db,generate_iban($db),$row['value'],$_POST['value'],$_POST['name']); //wywołanie funkcji która stworzy nowe konto
    }
        
    header("Location:main.php");
?>