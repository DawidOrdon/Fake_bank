<?php
    session_start();
    include_once("./scripts/connect.php");
    $db=connect();
    
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
        if(isset($_POST['password'])&&!empty($_POST['password']))
        {
            foreach($_POST['password'] as $pass_char)
            {
                echo $pass_char."<br />";
            }
        }
    ?>
</body>
</html>