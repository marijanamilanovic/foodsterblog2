<?php
    session_start();
    session_destroy();

    header("location:index.php?site-page=login");
?>