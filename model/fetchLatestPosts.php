<?php
    require_once "../config/connection.php";

    $data = "";
    $query = "SELECT * FROM posts ORDER BY date DESC LIMIT 3";

    try{
        $post = $connection->query($query)->fetchAll();
        $data = $post;
        $code = 200;
    }
    catch(PDOException $e){
        $code = 500;
        $data = "Server error";
    }
    echo json_encode($data);
    http_response_code($code);
?>