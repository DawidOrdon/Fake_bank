<?php
    session_start();
    include_once("./scripts/function.php");
    include_once("./scripts/connect.php");
    include_once("./scripts/navbar.php");
    is_logged();
    start_html();
    start_navbar();
    $db=connect();
?>
    <div class="container-fluid p-5 text-white">
        <div class="row">
            <div class="col-sm-4">
                <?php show_error()?>
                <h1>Twoje konta:</h1>
                <div class="row">
                    
                        <?php
                            $sql=$db->prepare("select * from account where user_id = ?");
                            $sql->bind_param('i',$_SESSION['user_id']);
                            $sql->execute();
                            $result=$sql->get_result();
                            while($row=$result->fetch_assoc())
                            {
                                echo "<div class='col-sm-12'>";
                                echo "Nazwa :".$row['name']."<br />";
                                echo "Numer Konta :".$row['iban']."<br />";
                                echo "stan konta:".$row['balance'];
                                echo "</div>";
                            }
                        ?>
                    
                </div>
                <div class="row">
                    <form method="POST" action="new_account.php">
                        <div class="col-sm-8" style="float:left">
                            <input type="text" name='name' class="form-control" placeholder="nazwa konta">
                        </div>
                        <div class="col-sm-4" style="float:left">
                            <input type="submit" value="Nowe konto" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    end_navbar();
    end_html();
?>