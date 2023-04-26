<?php
    session_start();
    session_destroy();
    include_once("./scripts/connect.php");
    include_once("./scripts/function.php");
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
            Twoje pieniądze są z nami bezpieczne 
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-6 col-lg-3  bg-dark text-light p-5 rounded-3">
                Login:
                <form method="POST" action="login_password.php">
                    <input type="text" name="login" class="form-control mt-2 bg-secondary text-light">
                    <input type="submit" class="btn text-center w-100 mt-4 bg-secondary text-light" vlaue="Dalej">
                </form>
                <?php
                    show_error();
                ?>
                <div class="row">
                    <div class="col-sm-12 mt-4">
                        Nie masz konta?<br />
                        <a href='./register.php'>Zarejestruj się</a>
                    </div>
                </div>
                
            </div>

        </div>
        <div class="row">
    </div>
    
    

<?php
    end_html();
?>
