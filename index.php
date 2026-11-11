<?php  

    require_once "libs/config.php";
    require_once "libs/functions.php";

    if( isset($_SESSION['fname']) || isset($_SESSION['uname']) || isset($_SESSION['email'])  ){

        header("location:dashboard.php");
    }

    include 'index.html';
?>
