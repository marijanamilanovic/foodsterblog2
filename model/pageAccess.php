<?php
    require_once "../config/connection.php";

    $file = file("data/log.txt");
    $index = $about = $recipes = $recipe = $contact = 0;
    $rows = count($file);
    $ms = 86400;
    $checkDate = date("d.m.Y. H:i:s", time()-$ms);
    foreach($file as $f => $row){
        list($page, $address, $date) = explode("__",trim($row));
        if($date > $checkDate){
            switch($page){
                case $page == "https://foodsterblog.000webhostapp.com/index.php":
                    $index++;
                    break;
                case $page == "https://foodsterblog.000webhostapp.com/index.php?site-page=about";
                    $about++;
                    break;
                case $page == "https://foodsterblog.000webhostapp.com/index.php?site-page=recipes";
                    $recipes++;
                    break;
                case $page == "https://foodsterblog.000webhostapp.com/index.php?site-page=contact";
                    $contact++;
                    break;
                case $page == "https://foodsterblog.000webhostapp.com/index.php?site-page=recipe";
                    $recipe++;
                    break;
            }
        }
    }
?>