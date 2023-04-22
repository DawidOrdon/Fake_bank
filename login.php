<?php
    session_start();
    session_destroy();
    include_once("./scripts/connect.php");
    include_once("./scripts/function.php");
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
    <form method="POST" action="login_password.php">
        <input type="text" name="login">
        <input type="submit">
    </form>
    <?php
        show_error();
    ?>
</body>
</html>