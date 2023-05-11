<?php
    session_start();
    include_once("./scripts/function.php");
    include_once("./scripts/connect.php");
    include_once("./scripts/navbar.php");
    is_logged();
    start_html();
    navbar();
?>
   
<?php
    end_html()
?>