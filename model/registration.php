<?php
    require_once "../config/connection.php";

    $data = "";
    if(isset($_POST["btnReg"])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['passReg'];
        $passConf = $_POST['passConf'];

        $reName = "/^(([A-ZČĆŽĐŠ][a-zčćžđš]{2,15})+)\s(([A-ZČĆŽĐŠ][a-zčćžđš]{2,15})+)$/";
        $reEmail = "/^\w([\.-]?\w+\d*)*@\w+\.\w{2,6}$/";
        $rePass = "/^.{8,50}$/";
        $error = [];

        if(!preg_match($reName, $name)){
            array_push($error, "Invalid name. Example: Paul Wesley");
        }
        if(!preg_match($reEmail, $email)){
            array_push($error, "Invalid email. Example: paulwesley@gmail.com");
        }
        if(!preg_match($rePass, $pass) && strlen($pass) < 8){
            array_push($error, "Password must contain at least 8 characters.");
        }
        if($passConf != $pass){
            array_push($error, "Passwords don't match.");
        }

        $checkEmail = "SELECT email FROM users WHERE email=:email";
        $preparation = $connection->prepare($checkEmail);
        $preparation->bindParam(":email", $email);
        try{
            $preparation->execute();
            $result = $preparation->fetch();
            if($preparation->rowCount()==1){
                $data = "There is already a user with that email address.";
                $code = 200;
            }
            else{
                if(count($error) == 0){
                    $insert = "INSERT INTO users VALUES(NULL, :name, :email, :pass, :active, :activationCode, :date, :idRole)";
                    $pass = md5($pass);
                    $date = date("Y-m-d H:i:s");
                    $role = 2;
                    $active = 1;
                    $acCode = md5(time().md5($email));

                    mail($email, "Account activation", "https://foodsterblog.000webhostapp.com/activation.php?code=" . $acCode);
                    $preparation2 = $connection->prepare($insert);
                    $preparation2->bindParam(":name", $name);
                    $preparation2->bindParam(":email", $email);
                    $preparation2->bindParam(":pass", $pass);
                    $preparation2->bindParam(":active", $active);
                    $preparation2->bindParam(":activationCode", $acCode);
                    $preparation2->bindParam(":date", $date);
                    $preparation2->bindParam(":idRole", $role);
                    try{
                        $success = $preparation2->execute();
                        $code = 201;
                        $data = "You have successfully registered! Check your email to activate your account.";
                    }
                    catch(PDOException $e){
                        $code = 500;
                        $data = "Server error";
                    }
                }
                else{
                    $data = $error;
                    $code = 422;
                }
            }
        }
        catch(PDOException $e){
            $data = "Server error";
            $code = 500;
        }
    }
    else{
        $code = 404;
        $data = "Error";
    }
    echo json_encode($data);
    http_response_code($code);
?>