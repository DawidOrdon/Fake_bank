<?php
    function get_random_pattern_id($db, $user_id, $wrong_password=0)
    {
        $sql="SELECT id FROM `users_hash` where id_user = ".$user_id." ORDER BY rand() limit 1";
        $result=$db->query($sql);
        $result=$result->fetch_assoc();
        $pattern_id=$result['id'];
        $sql="INSERT INTO `login` (`id`, `id_user`, `id_users_hash`, `wrong_password`, `date`, `succes`) VALUES (NULL, '$user_id', '$pattern_id', '$wrong_password', '".date('Y-m-d')."', 'false')";
        $db->query($sql);
    }
?>