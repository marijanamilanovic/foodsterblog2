<?php
    require_once "../config/connection.php";
    
    $data = "";
    if(isset($_POST["btnSend"])){
        $code = 200;
        $error = 0;

        $email = $_POST["emailMess"];
        $title = $_POST["tbTitle"];
        $message = $_POST["taMessage"];
        $reEmail = "/^\w([\.-]?\w+\d*)*@\w+\.\w{2,6}$/";
        $reTitle = "/^[\w\s]+$/";

        if(!preg_match($reEmail, $email)){
            $error++;
            $data = "Invalid email. Example: paulwesley@gmail.com";
        }
        if(!preg_match($reTitle, $title)){
            $error++;
            $data = "The title is incorrect.";
        }
        if(count($message) < 5){
            $error++;
            $data = "The message must contain at least 5 words.";
        }

        if($error == 0){
            $message = implode(" ", $message);
            $date = date("Y-m-d H:i:s");
            $insert = "INSERT INTO messages VALUES(null, :title, :message, :email, :date)";
            $preparation = $connection->prepare($insert);
            $preparation->bindParam(":title", $title);
            $preparation->bindParam(":message", $message);
            $preparation->bindParam(":email", $email);
            $preparation->bindParam(":date", $date);
            try{
                $preparation->execute();
                $data = "The message has been sent!";
                $code = 201;
            }
            catch(PDOException $e){
                $data = "Server error";
                $code = 500;
                noteError($e);
            }
        }
    }
    else{
        $code = 404;
        $data = "Error";
    }
    echo json_encode($data);
    http_response_code($code);
?>