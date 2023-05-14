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


            <div class="col-sm-4">
                <?php show_error()?>
                <h1>Konta oszczędnościowe:</h1>
                <div class="row">
                    
                        <?php
                            $sql=$db->prepare("select * from investment where user_id = ? and flex=0");
                            $sql->bind_param('i',$_SESSION['user_id']);
                            $sql->execute();
                            $result=$sql->get_result();
                            while($row=$result->fetch_assoc())
                            {
                                echo "<div class='col-sm-12'>";
                                echo "Nazwa :".$row['name']."<br />";
                                echo "Numer Konta :".$row['iban'].", oprocentowanie :".$row['percent']."<br />";
                                echo "stan konta:".$row['value'];
                                echo "odsetki:".$row['interest'];
                                echo "</div>";
                            }
                        ?>
                    
                </div>
                <div class="row">
                    <form method="POST" action="new_investment.php">
                        <div class="col-sm-6" style="float:left">
                            <input type="text" name='name' class="form-control" placeholder="nazwa konta">
                        </div>
                        <div class="col-sm-3" style="float:left">
                            <?php
                                $sql=$db->prepare("SELECT value FROM `percents` WHERE id=1");
                                $sql->execute();
                                $result=$sql->get_result();
                                $row=$result->fetch_assoc();
                                echo "Aktualne oprocentowanie<br />".$row['value']."%";
                            ?>
                            
                        </div>
                        <div class="col-sm-3" style="float:left">
                            <input type="submit" value="Nowe konto" class="form-control">
                        </div>
                    </form>
                </div>
            </div>


            <div class="col-sm-4">
                <?php show_error()?>
                <h1>Kredyty</h1>
                <div class="row">
                    
                        <?php
                            $sql=$db->prepare("select * from credit where user_id = ?");
                            $sql->bind_param('i',$_SESSION['user_id']);
                            $sql->execute();
                            $result=$sql->get_result();
                            while($row=$result->fetch_assoc())
                            {
                                echo "<div class='col-sm-12'>";
                                echo "Nazwa :".$row['name']."<br />";
                                echo "Numer Konta :".$row['iban'].", oprocentowanie :".$row['percent']."<br />";
                                echo "stan konta:".$row['value'];
                                echo "odsetki:".round($row['interest'],2);
                                echo "</div>";
                            }
                        ?>
                    
                </div>
                <div class="row">
                    <form method="POST" action="new_credit.php">
                        <div class="col-sm-6" style="float:left">
                            <input type="text" name='name' class="form-control" placeholder="nazwa konta">
                        </div>
                        <div class="col-sm-3" style="float:left">
                            <?php
                                $sql=$db->prepare("SELECT value FROM `percents` WHERE id=2");
                                $sql->execute();
                                $result=$sql->get_result();
                                $row=$result->fetch_assoc();
                                echo "Aktualne oprocentowanie<br />".$row['value']."%";
                            ?>
                            
                        </div>
                        <div class="col-sm-3" style="float:left">
                            <input type="number" class="form-control" value="kwota kredytu" name="value">
                        </div>
                        <div class="col-sm-3" style="float:left">
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