<?php
    function generate_hash($db,$pass,$id_user)
    {
        $pass= str_split($pass);
        $sql="select * from patterns where number_of_characters=".count($pass)."";
        $result=$db->query($sql);
        while($rekord = $result->fetch_assoc())
        {
            $sql2=("SELECT pass_char FROM pattern_char WHERE id_pattern=".$rekord["id"]);
            $pass_char=$db->query($sql2);
            $password="";
            while($pass_rekord=$pass_char->fetch_assoc())
            {
                 $password.=$pass[$pass_rekord["pass_char"]-1];
            }
            $pass_hash=password_hash($password,PASSWORD_DEFAULT, ['cost' => 6]);
            $sql="INSERT INTO `account_hash` (`id`, `id_user`, `id_pattern`, `hash`) VALUES ('', '$id_user', '".$rekord['id']."', '$pass_hash')";
            $db->query($sql);
            //echo"$pass_hash<br />";
        }
    }
?>