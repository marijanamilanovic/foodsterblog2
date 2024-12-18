<?php
    require_once "config/connection.php";

    $data = "";
    $mess = "";

    if(isset($_GET['id'])){
        $postId = $_GET['id'];
        $query = "SELECT p.*, p.name AS postName FROM posts AS p WHERE p.idPost=:id";
        $preparation = $connection->prepare($query);
        $preparation->bindParam(":id", $postId);
        try{
            $preparation->execute();
            $result = $preparation->fetch();
            $code = 200;
            if($preparation->rowCount() == 1){
                $data = $result;
                $rating = 0;
                if(isset($_SESSION['user'])){
                    $user = $_SESSION['user'];
                    $idUser = $user->idUser;

                    $fetchRating = "SELECT r.rating FROM rating r INNER JOIN users u ON r.idUser=u.idUser WHERE r.idUser=:idUser AND r.idPost=:idPost";
                    $preparation2 = $connection->prepare($fetchRating);
                    $preparation2->bindParam(":idUser", $idUser);
                    $preparation2->bindParam(":idPost", $postId);
                    try{
                        $preparation2->execute();
                        $res = $preparation2->fetch();
                        $rating = $res->rating;
                    }
                    catch(PDOException $e){
                        $data = "Server error";
                    }
                }
            }
            else{
                $mess = "There is no match.";
                $code = 404;
            }
        }
        catch(PDOException $e){
            $data = "Server error";
            $data = 500;
        }
    }
    else{
        $data = "Server error";
        $code = 404;
    }
?>