<?php
    session_start();
    require_once "../config/connection.php";

    $data = "";
    if(isset($_POST["btnLog"])){
        $email = $_POST["emailLog"];
        $pass = $_POST["passLog"];

        $reEmail = "/^\w([\.-]?\w+\d*)*@\w+\.\w{2,6}$/";
        $rePass = "/^.{8,50}$/";
        $error = [];

        if(!preg_match($reEmail, $email)){
            array_push($error, "Invalid email. Example: paulwesley@gmail.com");
        }
        if(!preg_match($rePass, $pass) && strlen($pass) < 8){
            array_push($error, "Password must contain at least 8 characters.");
        }

        if(count($error) == 0){
            $active = 1;
            $checkUser = "SELECT * FROM users WHERE email=:email AND active=:active";
            $preparation = $connection->prepare($checkUser);
            $preparation->bindParam(":email", $email);
            $preparation->bindParam(":active", $active);
            try{
                $preparation->execute();
                if($preparation->rowCount()==1){
                    $query = "SELECT * FROM users u INNER JOIN role r ON u.idRole=r.idRole WHERE email=:email AND pass=:pass";
                    $pass = md5($pass);
                    $preparation2 = $connection->prepare($query);
                    $preparation2->bindParam(":email", $email);
                    $preparation2->bindParam(":pass", $pass);
                    try{
                        $preparation2->execute();
                        $code = 200;
                        if($preparation2->rowCount()==1){
                            $user = $preparation2->fetch();
                            $_SESSION["user"] = $user;
                            $code = 201;
                            $data = "Successfully logged in!";
                        }
                        else{
                            $data = "The password is incorrect.";
                        }
                    }
                    catch(PDOException $e){
                        $code = 500;
                        $data = "Server error";
                    }
                    $code = 200;
                }
                else{
                    $code = 200;
                    $data = "No user with that email address was found or the account isn't activated.";
                }
            }
            catch(PDOException $e){
                $code = 500;
                $data = "Server error";
            }
        }
        else{
            $data = $error;
            $code = 404;
        }
        $code = 200;
    }
    else{
        $data = "The page you are looking for doesn't exist, have been removed or you have to log in.";
        $code = 404;
    }
    echo json_encode($data);
    http_response_code($code);
?>