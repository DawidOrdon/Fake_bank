<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/password_hash_generator.php");
    include_once("./scripts/get_random_pattern_id.php");
    include_once("./scripts/function.php");
    $db=connect();
    function generate_id($db)
    {
        $id=rand(100000000000,999999999999); //losowanie 12 znakowego id
        $sql = $db->prepare("select * from users where id=?");//sprawdzenie czy id wystąpiło wcześniej
        $sql->bind_param("i",$id);
        $sql->execute();
        $result=$sql->get_result();
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
                $sql = $db->prepare("INSERT INTO `users` (`id`, `pesel`, `firstname`, `lastname`) VALUES (?, ?, ?, ?);");
                $sql->bind_param("isss",$id,$_POST['pesel'],$_POST['firstname'],$_POST['lastname']);
                $sql->execute();//dodanie usera
                generate_hash($db,$_POST['password'],$id);//generowanie haseł
                get_random_pattern_id($db,$id);

                //dane do informacji zwrotnej
                $_SESSION['error'] = $_POST['firstname']."! dziękujemu za rejestracje<br /> Twój numer klienta to:".$id." zapisz go w bezpiecznym miejscu<br />";
                header('Location:./login.php');
                exit();
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
    start_html();
?>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            
        </div>
    </nav>
    <div class="container">
        <div class="row" >
            <div class="col-sm-6 col-lg-3 text-light pt-5 pr-5 pb-5 mt-5 text-center">
            <h2>Witaj w Fake bank</h2>
            Rejestracja
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-6 col-lg-3  bg-dark text-light p-5 rounded-3">
            <form method="POST">
                <?php
                    $rand=rand();
                    $_SESSION['rand']=$rand;
                ?>
                <input type="hidden" name="token" value="<?php echo $rand;?>">
                Imie
                <input type="text" class="form-control bg-dark text-light" name="firstname" placeholder="firstname" value="<?php if(isset($_POST['firstname'])){echo $_POST['firstname'];} ?>"><br />
                Nazwisko
                <input type="text" class="form-control bg-dark text-light" name="lastname" placeholder="lastname" value="<?php if(isset($_POST['lastname'])){echo $_POST['lastname'];} ?>"><br />
                Pesel
                <input type="text" class="form-control bg-dark text-light" name="pesel" placeholder="pesel" value="<?php if(isset($_POST['pesel'])){echo $_POST['pesel'];} ?>"><br />
                Hasło
                <input type="password" class="form-control bg-dark text-light" name="password" placeholder="password"><br />
                <input type="submit" name="submitbtn" class="btn bg-secondary text-light w-100">
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
                
            </div>

        </div>
        <div class="row">
    </div>
    
    <?php
        end_html();
    ?>