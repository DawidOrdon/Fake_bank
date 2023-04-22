<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/get_random_pattern_id.php");
    $db=connect();
    if(isset($_SESSION['id_account_hash'])&&!isset($_SESSION['account_hash']))//pobranie hasha hasła jeżeli go nie ma
    {
        
        $sql = $db->prepare("SELECT * FROM `account_hash` WHERE id=?");
        $sql->bind_param("i",$_SESSION['id_account_hash']);
        $sql->execute();
        $result=$sql->get_result();
        $result=$result->fetch_assoc();
        $_SESSION['account_hash']=$result['hash'];
    }
    if(isset($_POST['password'])&&!empty($_POST['password']))//sprawdzenie hasła
    {
        $password="";
        foreach($_POST['password'] as $pass_char)//sklejenie hasła w jeden string
        {
            $password.=$pass_char;
        }
        if(password_verify($password,$_SESSION['account_hash']))//weryfikacja hasła 
        {
            echo"hasło ok";
            get_random_pattern_id($db,$_SESSION['login']);//losowanie następnego patternu do kolejnego logowania
            unset($_SESSION['account_hash'],$_SESSION['id_account_hash'],$_SESSION['id_hash_id']);
            $sql = $db->prepare("UPDATE `login` SET `succes` = '1' WHERE `login`.`id` = ?");//aktualizacja danych logowania aby potwordzic poprawność logowania 
            $sql->bind_param("i",$_SESSION['login_id']);
            $sql->execute();
            //session_destroy();
        }
        else //błędne logowanie 
        {
            $trials=2-$_SESSION['wrong_password'];
            echo"błędne hasło pozsotały ".$trials." proby";
            $wrong_password=$_SESSION['wrong_password']+1;// zwiększenie numeru próby logowania 
            //$sql="INSERT INTO `login` (`id`, `id_user`, `id_account_hash`, `wrong_password`, `date`, `succes`) VALUES (NULL, '".$_SESSION['login']."', '".$_SESSION['id_account_hash']."', '$wrong_password', '".date('Y-m-d')."', 'false')";
            //$db->query($sql);
            $sql = $db->prepare("INSERT INTO `login` (`id`, `id_user`, `id_account_hash`, `wrong_password`, `date`, `succes`) VALUES (NULL, ?, ?, ?, 'NOW()', 'false')");//aktualizacja danych logowania aby potwordzic poprawność logowania 
            $sql->bind_param('iii',$_SESSION['login'],$_SESSION['id_account_hash'],$wrong_password);
            $sql->execute();
        }
        
        echo "<br />";
    }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if(isset($_POST['login'])&&!empty($_POST['login'])&&strlen($_POST['login'])==12)//wstepne sprawdzenie loginu
        {
            $_SESSION['login']=$_POST['login'];
        }
        if(isset($_SESSION['login']))
        {
            // pobranie ostatniej informacji o logowaniu
            $sql="SELECT account_hash.id_pattern as 'id_pattern', login.id_account_hash as 'id_account_hash', login.wrong_password as 'wrong_password', login.id as 'login_id' FROM login join account_hash on login.id_account_hash=account_hash.id  WHERE login.id_user=".$_SESSION['login']." order by login.id desc limit 1";
            $result=$db->query($sql);
            $sql = $db->prepare("INSERT INTO `login` (`id`, `id_user`, `id_account_hash`, `wrong_password`, `date`, `succes`) VALUES (NULL, ?, ?, ?, 'NOW()', 'false')");//aktualizacja danych logowania aby potwordzic poprawność logowania 
            $sql->bind_param('iii',$_SESSION['login'],$_SESSION['id_account_hash'],$wrong_password);
            $sql->execute();
            $result=$sql->get_result();
            if(mysqli_num_rows($result)!=1)//brak pasującego loginu
            {
                $_SESSION['error']="Podany login nie istnieje";
                header("Location:./login.php");
            }
            else
            {
                $result=$result->fetch_assoc();
                if($result['wrong_password']<3)
                {
                    $_SESSION['wrong_password']=$result['wrong_password'];
                    $_SESSION['login_id']=$result['login_id'];
                    if(!isset($_SESSION['account_hash_id']))//zapisanie id hasha patternu
                    {
                        $_SESSION['id_account_hash']=$result['id_account_hash'];
                    }
                    $sql="SELECT * FROM `pattern_char` WHERE id_pattern=".$result['id_pattern'];//pobranie numerow znakow hasla
                    $result=$db->query($sql);
                    while($rekord=$result->fetch_assoc())//przypisanie numerow znakow do tablicy
                    {
                        $password_chars[]=$rekord['pass_char'];
                    }
                    


                    echo"<form method='POST' style='text-align:center;'>";
                    for($i=1;$i<=21;$i++)//wypisanie pol do hasla 
                    {
                        if(!(false === array_search($i,$password_chars)))
                        {
                            ?>
                            <div style='float:left'>
                                <input type='password' name='password[]' maxlength='1' size='1'><br />
                                <?php echo $i?>
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div style='float:left'>
                                <input type='password' name='password[]' maxlength='1' size='1' disabled><br />
                                <?php echo $i?>
                            </div>
                            <?php
                        }
                    }
                    echo"<input type='submit'>
                        </form>";
                }
                else
                {
                    $_SESSION['error']="Twoje konto zostało zablokowane udaj się do najbliższej placówki banku"; // ustawienie błędu 
                    header("Location:./login.php");
                }
                



            }
        }
        else
        {
            $_SESSION['error']="Prosze podac prawidlowy login";
            header("Location:./login.php");
        }
    ?>
</body>
</html>