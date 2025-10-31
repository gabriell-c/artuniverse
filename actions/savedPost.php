<?php

    require_once '../config.php';
    header('Content-Type: application/json');

    if (isset($_POST['userId'])) {
        $id_post = $_POST['postId'];
        $id_user = $_POST['userId'];
        $user_name = $_POST['user_name'];

        $sql = "SELECT * FROM allsave WHERE user_name = :user_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_name', $user_name);
        $stmt->execute();
        $postSaved = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //-------------------------------------------//

        $sqlP = "SELECT * FROM allposts WHERE id = :id_post";
        $stmtP = $pdo->prepare($sqlP);
        $stmtP->bindValue(':id_post', $id_post);
        $stmtP->execute();
        $postItem = $stmtP->fetch(PDO::FETCH_ASSOC);

        //----------------------------------------//

        if(empty($postSaved)){

            $id_name = uniqid().time();

            $stmtCS = $pdo->prepare("INSERT INTO allsave (id_name, user_name, save_name)
            VALUES (:id_name, :user_name, :save_name)");
            $stmtCS->bindValue(':id_name', $id_name);
            $stmtCS->bindValue(':user_name', $_SESSION['user']['user_name']);
            $stmtCS->bindValue(':save_name', 'Saved');

            if($stmtCS->execute()){

                $stmtIS = $pdo->prepare("INSERT INTO itemsave (id_post, id_user, user_name, file, title, description, tags, creation_date, id_name)
                VALUES (:id_post, :id_user, :user_name, :file, :title, :description, :tags, :creation_date, :id_name )");
                $stmtIS->bindValue(':id_post', $id_post);
                $stmtIS->bindValue(':id_user', $id_user);
                $stmtIS->bindValue(':user_name', $user_name);
                $stmtIS->bindValue(':file', $postItem['file']);
                $stmtIS->bindValue(':title', $postItem['title']);
                $stmtIS->bindValue(':description', $postItem['description']);
                $stmtIS->bindValue(':tags', $postItem['tags']);
                $stmtIS->bindValue(':creation_date', $postItem['creation_date']);
                $stmtIS->bindValue(':id_name', $id_name);

                if($stmtIS->execute()){
                    echo json_encode("Item salvo com sucesso!");
                }
            }
        }else{

            $stmtIS = $pdo->prepare("INSERT INTO itemsave (id_post, id_user, user_name, file, title, description, tags, creation_date, id_name)
            VALUES (:id_post, :id_user, :user_name, :file, :title, :description, :tags, :creation_date, :id_name )");
            $stmtIS->bindValue(':id_post', $id_post);
            $stmtIS->bindValue(':id_user', $id_user);
            $stmtIS->bindValue(':user_name', $user_name);
            $stmtIS->bindValue(':file', $postItem['file']);
            $stmtIS->bindValue(':title', $postItem['title']);
            $stmtIS->bindValue(':description', $postItem['description']);
            $stmtIS->bindValue(':tags', $postItem['tags']);
            $stmtIS->bindValue(':creation_date', $postItem['creation_date']);
            $stmtIS->bindValue(':id_name', $postSaved[0]['id_name']);

            if($stmtIS->execute()){
                echo json_encode("Item salvo com sucesso!");
            }
        }



    }

?>