<?php
    require_once "../config/connection.php";

    $data = "";
    $query = "SELECT * FROM users";
    
    try{
        $users = $connection->query($query)->fetchAll();
        $data = $users;
        $code = 200;
    }
    catch(PDOException $e){
        $code = 500;
        $data = "Server error";
    }
    
    echo json_encode($data);
    http_response_code($code);
?>