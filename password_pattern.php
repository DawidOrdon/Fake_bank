<?php
    session_start();
    include_once("./scripts/connect.php");
    $db=connect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    podaj hasło do zaszyfrowania
    <form method="POST">
        <input type="text" name="password" minlength="12" maxlength="20" >
        <input type="submit">
    </form>
    <?php
        if(isset($_POST['password'])&&!empty($_POST['password'])&&strlen($_POST['password'])>=12&&strlen($_POST['password'])<=20)
        {
            //place this before any script you want to calculate time
            $time_start = microtime(true); 
            echo "hasło spełnia wymagania <br />";
            $pass=$_POST['password'];
            $pass= str_split($pass);
            $pass_count=count($pass);
            $sql = $db->prepare("select * from patterns where number_of_characters=?");
            $sql->bind_param("i",$pass_count);
            $sql->execute();
            $result=$sql->get_result();

            while($rekord = $result->fetch_array())
            {
                $sql2 = $db->prepare("SELECT pass_char FROM pattern_char WHERE id_pattern=?");
                $id=$rekord["id"];
                $sql2->bind_param("i",$id);
                $sql2->execute();
                $pass_char=$sql2->get_result();
                $password="";
                while($pass_rekord=$pass_char->fetch_assoc())
                {
                     $password.=$pass[$pass_rekord["pass_char"]-1];
                }
                $pass_hash=password_hash($password,PASSWORD_DEFAULT, ['cost' => 6]);

                //echo"$pass_hash<br />";
            }
            $time_end = microtime(true);
            //dividing with 60 will give the execution time in minutes otherwise seconds
            $execution_time = ($time_end - $time_start);

            //execution time of the script
            echo '<b>Total Execution Time:</b> '.$execution_time.' sec';
            // if you get weird results, use number_format((float) $execution_time, 10) 
        }
        else if(isset($_POST['password']))
        {
            echo "nie umiesz wpsiac hasła";
        }
    ?>
</body>
</html>