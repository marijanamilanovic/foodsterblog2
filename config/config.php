<?php
    define("BASE_URL",$_SERVER['DOCUMENT_ROOT']);
    define("envPath",__DIR__."/.env");

    define("LOG_FILE", BASE_URL."/data/log.txt");   
    
    define("DATABASE", env("DATABASE"));
    define("SERVER", env("SERVER"));
    define("USERNAME", env("USERNAME"));
    define("PASSWORD", env("PASSWORD"));

    function env($name){
        $array = file(envPath);
        $requiredValue = "";
    
        foreach($array as $row){
            $row = trim($row);
    
            list($id, $value) = explode("=", $row);
    
            if($id == $name){
                $requiredValue = $value;
                break;
            }
        }
    
        return $requiredValue;
    }
?>