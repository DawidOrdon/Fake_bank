<?php
    function find_iban($iban, $db)
    {
        $sql=$db->prepare("select * from account where iban = ?");
        $sql->bind_param('s',$iban);
        $sql->execute();
        $sql->store_result();
        if($sql->num_rows>0)
        {
            return(true);
            exit();
        }
        $sql=$db->prepare("select * from credit where iban = ?");
        $sql->bind_param('s',$iban);
        $sql->execute();
        $sql->store_result();
        if($sql->num_rows>0)
        {
            return(true);
            exit();
        }
        $sql=$db->prepare("select * from investment where iban = ?");
        $sql->bind_param('s',$iban);
        $sql->execute();
        $sql->store_result();
        if($sql->num_rows>0)
        {
            return(true);
            exit();
        }
        return(false);

    }
?>