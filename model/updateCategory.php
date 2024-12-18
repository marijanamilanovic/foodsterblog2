<?php
    require_once "../config/connection.php";

    if(isset($_POST["btnUpdate"])){
        $id = $_POST['idCategory'];
        $title = $_POST['name'];
        $code = 200;
        $error = 0;
        //$reTitle = "/^[A-Z][a-z]+(\s[a-z]+)*$/";

        // if(!preg_match($reTitle, $title)){
        //     $data = "Name must begin with capital letter and have 1 word minimum.";
        //     $error++;
        // }

        if($error == 0){
            if(@$_FILES['img']!=NULL){
                $img = $_FILES['img'];
                $tmpName = $img['tmp_name'];
                $size = $img['size'];
                $type = $img['type'];
                $name = $img['name'];
                $namePath = time().$name;
                $path = "../assets/images/$namePath";

                $result = move_uploaded_file($tmpName, $path);

                if($result){
                    $query = "UPDATE categories SET name=:title, img=:img WHERE idCategory=:id";
                    $preparation = $connection->prepare($query);
                    $preparation->bindParam(":img", $namePath);
                }
            }
            else{
                $query = "UPDATE categories SET name=:title WHERE idCategory=:id";
                $preparation = $connection->prepare($query);
            }
            $preparation->bindParam(":id", $id);
            $preparation->bindParam(":title", $title);

            try{
                $preparation->execute();
                $data = "Updated successfully!";
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