<?php
    session_start();
    include_once("./scripts/connect.php");
    include_once("./scripts/function.php");
    start_html();
?>
<body class="bg-dark">
    <a href="./pattern_generator.php">Pattern generator</a><br />
    <a href="./password_pattern.php">Zmiana hasła na patterny</a><br />
    <a href="./register.php">Rejestracja</a><br />
    <a href="./Login.php">Login</a><br />
<?php
header("Location:login.php");
    end_html();
?>