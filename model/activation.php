<?php
    $code = $_GET['code'];

    $query = "SELECT * FROM users WHERE activationCode=:code";
    $preparation = $connection->prepare($query);
    $preparation->bindParam(":code", $code);
    try{
        $preparation->execute();
        if($preparation->rowCount()==1){
            $user = $preparation->fetch();
            $name = $user['name'];
            $active = 1;
            $query2 = "UPDATE users SET active=:active WHERE activationCode=:code";
            $preparation2 = $connection->prepare($query2);
            $preparation2->bindParam(":active", $active);
            $preparation2->bindParam(":code", $code);
            try{
                $preparation2->execute();
                $data = "$name, you have successfully activated your account.";
            }
            catch(PDOException $e){
                $data = "Server error";;
            }
        }
        else{
            $data = "Invalid code";
        }
    }
    catch(PDOException $e){
        $data = "Server error";
    }
?>