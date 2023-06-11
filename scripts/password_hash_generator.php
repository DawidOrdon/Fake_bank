<?php
    function generate_hash($db,$pass,$id_user)
    {
        $pass= str_split($pass);
        $sql=$db->prepare("select * from patterns where number_of_characters=?");
        $pass_number=count($pass);
        $sql->bind_param('i',$pass_number);
        $sql->execute();
        $result=$sql->get_result();
        while($rekord = $result->fetch_assoc())
        {
            $sql=$db->prepare("SELECT pass_char FROM pattern_char WHERE id_pattern=?");
            $sql->bind_param('i',$rekord["id"]);
            $sql->execute();
            $pass_char=$sql->get_result();
            $password="";
            while($pass_rekord=$pass_char->fetch_assoc())
            {
                 $password.=$pass[$pass_rekord["pass_char"]-1];
            }
            $pass_hash=password_hash($password,PASSWORD_DEFAULT, ['cost' => 8]);
            $sql=$db->prepare("INSERT INTO `users_hash` (`id`, `id_user`, `id_pattern`, `hash`) VALUES ('', ?, ?, ?)");
            $sql->bind_param('iis',$id_user,$rekord['id'],$pass_hash);
            $sql->execute();
        }
    }
?>