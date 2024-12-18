<?php
    require_once "../config/connection.php";

    if(isset($_POST["btnUpdate"])){
        $id = $_POST["id"];
        $title = $_POST["title"];
        $openingText = $_POST["openingText"];
        $text = $_POST["text"];
        $code = 200;
        $error = 0;

        //$reTitle = "/^[A-Z][a-z]+(\s[A-Z][a-z]+)+$/";
        $reOpeningText = explode(" ", $openingText);
        $reText = explode(" ", $text);

        // if(!preg_match($reTitle, $title)){
        //     $data['mess'] = "Name must begin with capital letter and have 2 words minimum.";
        //     $error++;
        // }
        if(count($reOpeningText) < 5){
            $data['mess'] = "Opening text must begin with capital letter and have 5 words minimum.";
            $error++;
        }
        if(count($reText) < 50){
            $data['mess'] = "Text must begin with capital letter and have 50 words minimum.";
            $error++;
        }
           
        if($error == 0){
            if(@$_FILES['img'] != NULL){
                $img = $_FILES['img'];
                $tmpName = $img['tmp_name'];
                $size = $img['size'];
                $type = $img['type'];
                $name = $img['name'];
                $name2 = time().$name;
                $path = "../assets/images/$name2";
                $result = move_uploaded_file($tmpName, $path);
                if($result){
                    $query = "UPDATE posts SET name=:title, imgSrc=:img, text=:text, openingText=:openingText WHERE idPost=:id";
                    $preparation = $connection->prepare($query);
                    $preparation->bindParam(":img", $name2);
                    $data['img'] = $name2;
                }
            }
            else{
                $query = "UPDATE posts SET name=:title, text=:text, openingText=:openingText WHERE idPost=:id";
                $preparation = $connection->prepare($query);
                $data['img'] = $_POST['imgSrc'];
            }
            $preparation->bindParam(":id", $id);
            $preparation->bindParam(":title", $title);
            $preparation->bindParam(":text", $text);
            $preparation->bindParam(":openingText", $openingText);
            try{
                $preparation->execute();
                $data['mess'] = "Updated successfully!";
                $code = 200;
            }
            catch(PDOException $e){
                $code = 500;
                $data = "Server error";
            }
        }
    }
    else{
        $data = "Error";
        $code = 404;
    }
    echo json_encode($data);
    http_response_code($code);
?>