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
    
?>
    <div class="container-fluid p-5 text-white">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <h1>Historia</h1>
                </div>
                <div class="row">
                        <div class="col-sm-4">
                            adres źródłowy
                        </div>
                        <div class="col-sm-4">
                            adres docelowy
                        </div>
                        <div class="col-sm-3">
                            tytuł
                        </div>
                        <div class="col-sm-1">
                            kwota
                        </div>
                </div>
                <?php
                    $sql=$db->prepare("SELECT transfers.title as 'title', transfers.id_user_source as 'source', transfers.id_user_destiny as 'destiny', transfers.value as 'value' FROM transfers join account on transfers.id_user_source = account.iban where account.user_id=?");
                    $sql->bind_param('i',$_SESSION['user_id']);
                    $sql->execute();
                    $result=$sql->get_result();
                    if($result->num_rows>0)
                    {
                        while($row=$result->fetch_assoc())
                        {
                            echo"
                            <div class='row'>
                                <div class='col-sm-4'>
                                    {$row['source']}
                                </div>
                                <div class='col-sm-4'>
                                    {$row['destiny']}
                                </div>
                                <div class='col-sm-3'>
                                    {$row['title']}
                                </div>
                                <div class='col-sm-1'>
                                    {$row['value']} zł
                                </div>
                            </div>
                            ";
                        }
                    }
                    $sql=$db->prepare("SELECT transfers.title as 'title', transfers.id_user_source as 'source', transfers.id_user_destiny as 'destiny', transfers.value as 'value' FROM transfers join investment on transfers.id_user_source = investment.iban where investment.user_id=?");
                    $sql->bind_param('i',$_SESSION['user_id']);
                    $sql->execute();
                    $result=$sql->get_result();
                    if($result->num_rows>0)
                    {
                        while($row=$result->fetch_assoc())
                        {
                            echo"
                            <div class='row'>
                                <div class='col-sm-4'>
                                    {$row['source']}
                                </div>
                                <div class='col-sm-4'>
                                    {$row['destiny']}
                                </div>
                                <div class='col-sm-3'>
                                    {$row['title']}
                                </div>
                                <div class='col-sm-1'>
                                    {$row['value']} zł
                                </div>
                            </div>
                            ";
                        }
                    }
                    $sql=$db->prepare("SELECT transfers.title as 'title', transfers.id_user_source as 'source', transfers.id_user_destiny as 'destiny', transfers.value as 'value' FROM transfers join credit on transfers.id_user_source = credit.iban where credit.user_id=?");
                    $sql->bind_param('i',$_SESSION['user_id']);
                    $sql->execute();
                    $result=$sql->get_result();
                    if($result->num_rows>0)
                    {
                        while($row=$result->fetch_assoc())
                        {
                            echo"
                            <div class='row'>
                                <div class='col-sm-4'>
                                    {$row['source']}
                                </div>
                                <div class='col-sm-4'>
                                    {$row['destiny']}
                                </div>
                                <div class='col-sm-3'>
                                    {$row['title']}
                                </div>
                                <div class='col-sm-1'>
                                    {$row['value']} zł
                                </div>
                            </div>
                            ";
                        }
                    }
                ?>
                <div class="col-sm-12">
                    <?php
                        show_error();
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
    end_navbar();
    end_html();
?>