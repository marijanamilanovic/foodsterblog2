<?php
    require_once "../config/connection.php";

    $data = "";
    $query = "SELECT * FROM categories";

    try{
        $category = $connection->query($query)->fetchAll();
        $data = $category;
        $code = 200;
    }
    catch(PDOException $e){
        $data = "Server error";
        $code = 500;
    }
    echo json_encode($data);
    http_response_code($code);
?>