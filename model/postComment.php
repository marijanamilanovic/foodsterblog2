<?php
    session_start();
    include "../config/connection.php";

    if(isset($_POST['postCom'])){
        $idPost = $_POST['idPost'];
        $comment = $_POST['comment'];
        $mess = $idPost;
        $user = $_SESSION['user'];
        $idUser = $user->idUser;

        if(count($comment)<=4){
            $mess = "Comment must contain more than 5 words!";
            $code = 200;
        }
        else{
            $insert = "INSERT INTO comments VALUES (NULL, :text, :date, :idUser, :idPost)";
            $date = date("Y-m-d H:i:s");
            $comment = implode(" ", $comment);
            $preparation = $connection->prepare($insert);
            $preparation->bindParam(":text", $comment);
            $preparation->bindParam(":date", $date);
            $preparation->bindParam(":idUser", $idUser);
            $preparation->bindParam(":idPost", $idPost);
            try{
                $preparation->execute();
                $mess = "Success";
                $code = 201;
            }
            catch(PDOException $e){
                $mess = "Server error";
                $code = 500;
            }
        }
    }
    else{
        $code = 404;
        $mess = "Error";
    }
    echo json_encode($mess);
    http_response_code($code);
?>