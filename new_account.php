<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/function.php");
    include_once("./scripts/generate_iban.php");
    include_once("./scripts/find_iban.php");
    include_once("./scripts/new_account.php");
    $db=connect();
    if(!empty($_POST['name']))
    {
        new_account($db,generate_iban($db),200,$_POST['name']); //wywołanie funkcji która stworzy nowe konto
    }
        
    header("Location:main.php");
?>