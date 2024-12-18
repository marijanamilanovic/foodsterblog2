<?php
    require_once "../config/connection.php";

    $data = "";
    $query = "SELECT * FROM comments c INNER JOIN users u ON c.idUser=u.idUser ORDER BY RAND() LIMIT 4";

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