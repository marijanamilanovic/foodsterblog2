<?php
    require_once "config/connection.php";

    $comment = "";

    if(isset($_GET['id'])){
        $postId = $_GET['id'];
        $query = "SELECT c.*, u.*, c.date AS dateOfComment, c.text AS commentText, u.name AS userName FROM comments AS c INNER JOIN users AS u ON c.idUser=u.idUser WHERE c.idPost=:id";
        $preparation = $connection->prepare($query);
        $preparation->bindParam(":id", $postId);
        try{
            $preparation->execute();
            $result = $preparation->fetchAll();
            if($preparation->rowCount() != 0){
                $comment = $result;
            }
            else{
                $code = 404;
            }
        }
        catch(PDOException $e){
            $code = 500;
        }
    }
    else{
        $code = 404;
    }
?>