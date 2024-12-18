<?php
    require_once "view/fixed/head.php";
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        if($user->idRole == "1"){
            include "view/pages/admin.php";
        }
        else{
            include "view/pages/error.php";
        }
    }
    else{
        include "view/pages/error.php";
    }
?>