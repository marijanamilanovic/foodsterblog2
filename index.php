<?php
    ob_start();
    session_start();
    include "config/connection.php";
    include "view/fixed/head.php";
    if(isset($_GET['site-page'])){
        switch($_GET['site-page']){
            case "about":
                include "view/fixed/header.php";
                include "view/pages/about.php";
                include "view/fixed/footer.php";
                break;
            case "admin":
                include "admin.php";
                break;
            case "activation":
                include "view/fixed/header.php";
                include "view/pages/activation.php";
                include "view/fixed/footer.php";
                break;
            case "author":
                include "view/fixed/header.php";
                include "view/pages/author.php";
                include "view/fixed/footer.php";
                break;
            case "contact":
                include "view/fixed/header.php";
                include "view/pages/contact.php";
                include "view/fixed/footer.php";
                break;
            case "error":
                include "view/fixed/header.php";
                include "view/pages/error.php";
                include "view/fixed/footer.php";
                break;
            case "login":
                include "view/fixed/header.php";
                include "view/pages/login.php";
                include "view/fixed/footer.php";
                break;
            case "recipe":
                include "view/fixed/header.php";
                include "view/pages/recipe.php";
                include "view/fixed/footer.php";
                break;
            case "recipes":
                include "view/fixed/header.php";
                include "view/pages/recipes.php";
                include "view/fixed/footer.php";
                break;
            case "register":
                include "view/fixed/header.php";
                include "view/pages/register.php";
                include "view/fixed/footer.php";
                break;
            case "logout":
                include "view/fixed/header.php";
                include "model/logout.php";
                include "view/fixed/footer.php";
                break;
            default:
                include "view/fixed/header.php";
                include "view/pages/index.php";
                include "view/fixed/footer.php";
        }
    }
    else{
        include "view/fixed/header.php";
        include "view/pages/index.php";
        include "view/fixed/footer.php";
    }
?>