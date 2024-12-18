<?php
    require_once "../config/connection.php";

    if(isset($_POST['button'])){
        $id = $_POST['id'];
        $data = "";
        $query = "SELECT * FROM posts WHERE idPost=:id";
        $preparation = $connection->prepare($query);
        $preparation->bindParam(":id", $id);

        try{
            $preparation->execute();
            $data = $preparation->fetch();
            $code = 200;
        }
        catch(PDOException $e){
            $code = 500;
            $data = "Server error";
        }
    }
    else{
        $data = "Error";
        $code = 404;
    }

    echo json_encode($data);
    http_response_code($code);
?>