<?php
    require_once "../config/connection.php";

    if(isset($_POST["btnDelete"])){
        $id = $_POST['id'];
        $data = "";
        $query = "DELETE FROM categories WHERE idCategory=:id";
        $preparation = $connection->prepare($query);
        $preparation->bindParam(":id", $id);

        try{
            $preparation->execute();
            $data = "Category deleted!";
            $code = 200;
        }
        catch(PDOException $e){
            $code = 500;
            $data = "Server error";
        }
    }
    else{
        $data = "Error";
        $code = 404;
    }

    echo json_encode($data);
    http_response_code($code);
?>