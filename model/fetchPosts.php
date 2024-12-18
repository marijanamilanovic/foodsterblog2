<?php
    require_once "config/connection.php";

    $data = "";
    $mess = "";

    $idCat = 0;
    $page = 1;

    if(isset($_GET['category'])){
        $idCat = $_GET['category'];
        $catName = "SELECT * FROM categories WHERE idCategory=:idCat";
        $preparation = $connection->prepare($catName);
        $preparation->bindParam(":idCat", $idCat);
        try{
            $preparation->execute();
            if($preparation->rowCount() == 0){
                $mess = "There is no match.";
            }
            $category = $preparation->fetch();
            $title = $category->name;
        }
        catch(PDOException $e){
            $code = 500;
            $mess = "Server error";
        }

        $fetchAll = "SELECT * FROM posts WHERE idCategory=:idCat";
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $fetchAll .= " AND name LIKE '%$search%'";
        }
        $preparation2 = $connection->prepare($fetchAll);
        $preparation2->bindParam(":idCat", $idCat);
    }
    else{
        $fetchAll = "SELECT * FROM posts";
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $fetchAll .= " WHERE name LIKE '%$search%'";
        }
    }

    try{
        if(isset($_GET['category'])){
            $preparation2->execute();
            if($preparation2->rowCount() == 0){
                $mess = "There is no match.";
            }
            $result = $preparation2->fetchAll();
            $total = $preparation2->rowCount();
        }
        else{
            $all = $connection->query($fetchAll);
            $total = $all->rowCount();

            if($total == 0){
                $mess = "There is no match";
            }
            $title = "Recipes";
        }

        $offset = 1;
        $limit = 4;

        $numberOfPages = ceil($total/$limit);

        $query = "SELECT * FROM posts";

        if(isset($_GET['category'])){
            $query .= " WHERE idCategory=:idCat";
        }
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            if(isset($_GET['category'])){
                $query .= " AND name LIKE '%$search%'";
                $echo = "TU";
            }
            else{
                $query .= " WHERE name LIKE '%$search%'";

            }
        }

        $query .= " GROUP BY name LIMIT :limitValue OFFSET :offsetValue";

        $preparation3 = $connection->prepare($query);

        if(isset($_GET['category']) != 0){
            $preparation3->bindParam(":idCat", $_GET['category']);
        }
        if(isset($_GET['page'])){
            $page = $_GET['page'] - 1;
            $offset = $page * $limit;
        }
        $preparation3->bindParam(":limitValue", $limit, PDO::PARAM_INT);
        $preparation3->bindParam(":offsetValue", $offset, PDO::PARAM_INT);
        try{
            $preparation3->execute();
            $data = $preparation3->fetchAll();
            if($preparation3->rowCount() == 0){
                $mess = "There is no match.";
            }
        }
        catch(PDOException $e){
            $code = 500;
            $mess = $e;
        }
    }
    catch(PDOException $e){
        $mess = $e;
        $code = 500;
    }
?>