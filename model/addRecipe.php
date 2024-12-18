<?php
    require_once "../config/connection.php";

    if(isset($_POST['btn'])){
        $mess = "";
        $code = 200;
        @$img = $_FILES['img'];
        @$tmpName = $img['tmp_name'];
        @$size = $img['size'];
        @$type = $img['type'];
        @$name = $img['name'];
        @$namePath = time().$name;
        @$path = "../assets/images/$namePath";

        $title = $_POST['title'];
        $openingText = $_POST['openingText'];
        $text = $_POST['text'];
        $cat = $_POST['category'];
        $date = date("Y-m-d H:i:s");

        $error = 0;

        $reTitle = "/^[A-Z][a-z]+(\s[A-Z][a-z]+)+$/";
        $reOpText = explode(" ", $openingText);
        $reText = explode(" ", $text);

        if(!preg_match($reTitle, $title)){
            $mess = "Name must begin with capital letter and have 2 words minimum.";
            $error++;
        }
        if(count($reOpText) < 5){
            $mess = "Opening text must begin with capital letter and have 5 words minimum.";
            $error++;
        }
        if(count($reText) < 50){
            $mess = "Text must begin with capital letter and have 50 words minimum.";
            $error++;
        }
        if($cat == 0){
            $mess = "You have to choose category.";
            $error++;
        }
        if(@!$_FILES['img']){
            $mess = "You have to choose image.";
            $error++;
        }
        if($error == 0){
            $result = move_uploaded_file($tmpName, $path);
            if(!$result){
                $mess = "Error";
                $code = 200;
            }
            else{
                $query = "INSERT INTO posts VALUES (null, :title, :text, :date, :img, :openingText, :idCategory)";
                $preparation = $connection->prepare($query);
                $preparation->bindParam("title", $title);
                $preparation->bindParam("text", $text);
                $preparation->bindParam("date", $date);
                $preparation->bindParam("img", $namePath);
                $preparation->bindParam("openingText", $openingText);
                $preparation->bindParam("idCategory", $cat);
                try{
                    $preparation->execute();
                    $mess = "Recipe successfully added!";
                    $code = 201;
                }
                catch(PDOException $e){
                    $mess = "Server error";
                    $code = 500;
                }
            }
        }
    }
    else{
        $code = 404;
        $mess = "The page you are looking for doesn't exist, have been removed or you have to log in.";
    }

    echo json_encode($mess);
    http_response_code($code);
?>