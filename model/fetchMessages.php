<?php
    require_once "../config/connection.php";

    $data = "";
    $query = "SELECT * FROM messages ORDER BY date DESC";

    try{
        $messages = $connection->query($query)->fetchAll();
        $data = $messages;
        $code = 200;
    }
    catch(PDOException $e){
        $code = 500;
        $data = "Server error";
    }
    echo json_encode($data);
    http_response_code($code);
?>