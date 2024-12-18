<?php
    require_once "../config/connection.php";

    $mess = "";
    $code = 200;

    @$img = $_FILES['img'];
    $title = $_POST['name'];
    $error = 0;

    $reTitle = "/^[A-Z][a-z]+(\s[a-z]+)*$/";

    if(@!$_FILES['img']){
        $mess = "You have to choose image.";
        $error++;
    }
    if(!preg_match($reTitle, $title)){
        $mess = "Title must begin with capital letter and have 1 word minimum.";
        $error++;
    }
    
    if($error == 0){
        $tmpName = $img['tmp_name'];
        $size = $img['size'];
        $type = $img['type'];
        $name = $img['name'];
        $namePath = time().$name;
        $path = "../assets/images/$namePath";
        $result = move_uploaded_file($tmpName, $path);
        if(!$result){
            $mess = "Error";
            $code = 200;
        }
        else{
            $query = "INSERT INTO categories VALUES (NULL, :name, :img)";
            $preparation = $connection->prepare($query);
            $preparation->bindParam(":name", $title);
            $preparation->bindParam(":img", $namePath);
            try{
                $preparation->execute();
                $mess = "Category successfully added!";
                $code = 201;
            }
            catch(PDOException $e){
                $mess = "Server error";
                $code = 500;
            }
        }
    }

    echo json_encode($mess);
    http_response_code($code);
?>