<?php
    function show_error()
    {
        if(isset($_SESSION['error']))
        {
            echo"<div clas='row'><div class='col-sm-12 mt-3 text-danger'>";
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            echo "</div></div>";
        }
    }
    function start_html()
    {
        ?>
            <!DOCTYPE html>
            <html lang="pl">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Fake Bank</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                <link rel="stylesheet" href="./main.css" type="text/css">
            </head>
            <body>
            <script src="./scripts/scripts.js"></script>
        <?php
    }
    function end_html()
    {
        ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            </body>
            </html>
        <?php
    }
    function is_logged()
    {
        if(empty($_SESSION['user_id']))
        {
            header("Location:./login.php");
            exit();
        }
    }
    
?>