<?php
    require_once "../config/connection.php";

    $query = "SELECT p.*, ROUND(AVG(r.rating)) AS rating FROM posts p LEFT JOIN rating r on p.idPost=r.idPost GROUP BY p.name";
    try{
        $data = $connection->query($query)->fetchAll();
        $code = 200;
    }
    catch(PDOException $e){
        $data = "Server error";
        $code = 500;
    }
    echo json_encode($data);
    http_response_code($code);
?>