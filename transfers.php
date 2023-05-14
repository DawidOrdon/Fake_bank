<?php
    ob_start();
    session_start();
    include_once("./scripts/function.php");
    include_once("./scripts/connect.php");
    include_once("./scripts/navbar.php");
    include_once("./scripts/generate_iban.php");
    is_logged();
    start_html();
    start_navbar();
    $db=connect();
    foreach($_POST as $key => $value) //sprawdzenie czy user wpisał dane
    {
        if(empty($value))
        {
            $error=1;
        }
    }
    if(!isset($error))
    {
        if(isset($_SESSION['token'])&&isset($_POST['token']))
        {
            if($_POST['token']==$_SESSION['token'])
            {
                if(check_iban($_POST['destiny']))
                $sql=$db->prepare("SELECT user_id, balance from account where iban = ?"); //zabezpieczenie przed podmiana numeru konta
                $sql->bind_param('s', $_POST['source']);
                $sql->execute();
                $result=$sql->get_result();
                $result=$result->fetch_assoc();
                if(@$result['user_id']==$_SESSION['user_id'])// sprawdzanie właściciela konta
                {
                    if($_POST['source']!=$_POST['destiny'])//sprawdzanie czy numery nie sa identyczne 
                    {
                        if($result['balance']>=$_POST['amount']&&$_POST['amount']>0)// sprawdzanie czy user ma odpowiednia ilosc pieniedzy
                        {
                            $sql=$db->prepare("UPDATE `account` SET `balance` = balance - ? WHERE `account`.`iban` = ?");
                            $sql->bind_param('ds',$_POST['amount'],$_POST['source']);
                            $sql->execute();
                            $sql=$db->prepare("INSERT INTO `transfers` (`id`, `title`, `id_user_source`, `id_user_destiny`, `value`, `date`, `accept`) VALUES ('', ?, ?, ?, ?, NOW(), 0)");
                            $sql->bind_param('sssd',$_POST['title'],$_POST['source'],$_POST['destiny'],$_POST['amount']);
                            $sql->execute();
                            $_SESSION['error']='Przelew został zlecony do realizacji';
                            header('Location:./main.php');
                        }
                        else
                        $_SESSION['error']='Niestety nie masz tyle pieniędzy';
                    }
                    else
                    {
                        $_SESSION['error']='Po co chcesz przelać pieniądze na to samo konto?';
                    }
                }
                else
                {
                    $_SESSION['error']='Przykro nam ale nie jesteś wałścicielem konta';
                }
            }
        }
    }
    else
    {
        $_SESSION['error']='Prosze uzupełnić wszystkie pola';
    }
?>
    <div class="container-fluid p-5 text-white">
        <div class="row">
            <div class="col-sm-4">
                <form method="POST">
                    <?php
                        $rand=rand();
                        $_SESSION['token']=$rand;
                    ?>
                    <input type="hidden" name="token" value="<?php echo $rand;?>">
                    Z rachunku
                    <select name='source' class="col-sm-12 p-2">
                        <div class="row">
                        <?php
                            $sql=$db->prepare("select * from account where user_id = ?");
                            $sql->bind_param('i',$_SESSION['user_id']);
                            $sql->execute();
                            $result=$sql->get_result();
                            while($row=$result->fetch_assoc())
                            {
                                echo "<option value=".$row['iban']." class='form-control'>";
                                echo $row['name'];
                                echo "; stan konta :".$row['balance']."zł<br />";
                                echo "</option>";
                            }
                        ?>
                        </div>
                    </select>
                <div class="row">
                        <div class="col-sm-12">
                            do odbiorcy 
                        </div>
                        <div class="col-sm-12" style="float:left">
                            <input type="text" name='destiny' class="form-control" placeholder="nazwa konta">
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            Kwota
                                        </div>
                                        <div class="col-sm-12">
                                            <input type="number" min='0.01' step="0.01" name='amount' class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            Tytuł
                                        </div>
                                        <div class="col-sm-12">
                                            <input type="text" name='title' class="form-control">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        

                        <div class="col-sm-12 m-t-3" style="float:left">
                            <input type="submit" value="Nowe konto" class="form-control w-100">
                        </div>
                    </form>
                    <div class="col-sm-12">
                        <?php
                            show_error();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    end_navbar();
    end_html();
?>