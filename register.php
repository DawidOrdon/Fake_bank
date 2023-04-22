<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/password_hash_generator.php");
    include_once("./scripts/get_random_pattern_id.php");
    include_once("./scripts/function.php");
    $db=connect();
    function generate_id($db)
    {
        $id=rand(10000000000,999999999999); //losowanie 11 znakowego id
        $sql = $db->prepare("select * from users where id=?");//sprawdzenie czy id wystąpiło wcześniej
        $sql->bind_param("i",$id);
        $result=$sql->execute();
        if(mysqli_num_rows($result))//wystąpiło
        {
            return(false);
            $id=generate_id($db);
            return($id);
        }
        else//nie wystąpiło
        {
            return($id);
        }
    }
    if(isset($_POST['submitbtn'])&&$_POST['token']==$_SESSION['rand'])//sprawdzaie czy token jest inny niż poprzedni
    {
        if(isset($_POST['firstname'])&&!empty($_POST['firstname'])&&
        isset($_POST['lastname'])&&!empty($_POST['lastname'])&&
        isset($_POST['pesel'])&&!empty($_POST['pesel'])&&
        isset($_POST['password'])&&!empty($_POST['password']))//sprawdzanie podstawowych informacji czy istnieja 
        {
            if(strlen($_POST['pesel'])!=11)//sprawdzenie dlugosci numeru pesele
            {
                $_SESSION['error']="Pesel musi posiadać 11 cyfr";
                goto end;
            }
            if(!is_numeric($_POST['pesel']))//sprawdzanie czy pesel jest liczbowy
            {
                $_SESSION['error']="Pesel może skladać się tylko z cyfr";
                goto end;
            }
            if(strlen($_POST['password'])<12)
            {
                $_SESSION['error']="Twoje hasło musi miec conajmniej 12 znakow";
                goto end;
            }
            if(strlen($_POST['password'])>20)
            {
                $_SESSION['error']="Twoje hasło musi miec nie wiecej niz 20 znakow";
                goto end;
            }
            




            if(!isset($_SESSION['error']))//proces rejestracji
            {
                echo"dane ok";
                $id=generate_id($db);//generowanie id
                $sql = $db->prepare("INSERT INTO `users` (`id`, `pesel`, `imie`, `nazwisko`) VALUES ('?', '?', '?', '?");
                $sql->bind_param("isss",$id,$_POST['pesel'],$_POST['firstname'],$_POST['lastname']);
                $sql->execute();//dodanie usera
                generate_hash($db,$_POST['password'],$id);//generowanie haseł
                get_random_pattern_id($db,$id);

                //dane do informacji zwrotnej
                $_SESSION['firstname']=$_POST['firstname'];
                $_SESSION['id']=$id;
            }
            
            




        }
        else
        {   if(isset($_POST['firstname'])||!empty($_POST['firstname'])||
            isset($_POST['lastname'])||!empty($_POST['lastname'])||
            isset($_POST['pesel'])||!empty($_POST['pesel'])||
            isset($_POST['password'])||!empty($_POST['password']))//sprawdzenie czy user cokolwiek wpisał 
            {
                $_SESSION['error']="Prosze wypełnić wszystkie pola";
            }
            
        }
    }
    end:
    
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
    <h1>Rejestracja Fake bank</h1>
    <form method="POST">
        <?php
            $rand=rand();
            $_SESSION['rand']=$rand;
        ?>
        <input type="hidden" name="token" value="<?php echo $rand;?>">
        <input type="text" name="firstname" placeholder="firstname" value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname'];} ?>"><br />
        <input type="text" name="lastname" placeholder="lastname" value="<?php if(isset($_POST['lastname'])){echo $_POST['lastname'];} ?>"><br />
        <input type="text" name="pesel" placeholder="pesel" value="<?php if(isset($_POST['pesel'])){echo $_POST['pesel'];} ?>"><br />
        <input type="password" name="password" placeholder="password"><br />
        <input type="submit" name="submitbtn">
    </form>
    <?php
        show_error();
        if(isset($_SESSION['firstname'])&&isset($_SESSION['id']))
        {
            echo $_SESSION['firstname']."! dziękujemu za rejestracje<br />";
            echo "Twój numer klienta to:".$_SESSION['id']." zapisz go w bezpiecznym miejscu<br />";
            unset($_SESSION['id'],$_SESSION['firstname']);
        }
    ?>
</body>
</html>