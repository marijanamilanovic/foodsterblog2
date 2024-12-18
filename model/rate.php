<?php
    session_start();
    include "../config/connection.php";

    if(isset($_POST['click'])){
        if(isset($_SESSION['user'])){
            $rating = $_POST['rating'];
            $user = $_SESSION['user'];
            $idUser = $user->idUser;
            $recipe = $_POST['recipe'];
            $query = "SELECT * FROM rating WHERE idUser=:idUser AND idPost=:idPost";
            $preparation = $connection->prepare($query);
            $preparation->bindParam(":idUser", $idUser);
            $preparation->bindParam(":idPost", $recipe);
            try{
                $preparation->execute();
                $result = $preparation->fetch();
                if($result){
                    $update = "UPDATE rating SET rating=:rating WHERE idUser=:idUser AND idPost=:idPost";
                    $preparation2 = $connection->prepare($update);
                    $preparation2->bindParam(":rating", $rating);
                    $preparation2->bindParam(":idUser", $idUser);
                    $preparation2->bindParam(":idPost", $recipe);
                    try{
                        $preparation2->execute();
                        $mess = "Thank you for rating!";
                        $code = 204;
                    }
                    catch(PDOException $e){
                        $mess = "Server error";
                        $code = 500;
                    }
                }
                else{
                    $insert = "INSERT INTO rating VALUES(null, :idPost, :idUser, :rating)";
                    $preparation3 = $connection->prepare($insert);
                    $preparation3->bindParam(":idPost", $recipe);
                    $preparation3->bindParam(":idUser", $idUser);
                    $preparation3->bindParam(":rating", $rating);
                    try{
                        $preparation3->execute();
                        $mess = "Thank you for rating!";
                        $code = 201;
                    }
                    catch(PDOException $e){
                        $mess = "Server error";
                        $code = 500;
                    }
                }
            }
            catch(PDOException $e){
                $mess = "Server error";
                $code = 500;
                noteError($e);
            }
        }
        else{
            $mess = "Log in to rate";
            $code = 200;
        }
    }

    echo json_encode($mess);
    http_response_code($code);
?>