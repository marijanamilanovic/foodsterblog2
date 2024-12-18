<?php
    require_once "../config/connection.php";

    $data = "";
    $query = "SELECT p.name AS postName, c.*, u.name AS userName, c.date AS commentDate FROM comments c INNER JOIN users u ON u.idUser=c.idUser INNER JOIN posts p on c.idPost=p.idPost ORDER BY p.idPost";
    
    try{
        $comments = $connection->query($query)->fetchAll();
        $data = $comments;
        $code = 200;
    }
    catch(PDOException $e){
        $code = 500;
        $data = "Server error";
    }
    
    echo json_encode($data);
    http_response_code($code);
?>