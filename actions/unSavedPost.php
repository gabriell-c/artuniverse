<?php

    require_once '../config.php';
    header('Content-Type: application/json');

    if (isset($_POST['userId'])) {
        $id_post = $_POST['postId'];
        $id_user = $_POST['userId'];
        $user_name = $_POST['user_name'];

        //-------------------------------------------//

        $sqlP = "SELECT * FROM itemsave WHERE id_post = :id_post";
        $stmtP = $pdo->prepare($sqlP);
        $stmtP->bindValue(':id_post', $id_post);
        $stmtP->execute();
        $saveItem = $stmtP->fetch(PDO::FETCH_ASSOC);

        //----------------------------------------//

        if(!empty($saveItem)){


            $sql = "DELETE FROM itemsave WHERE id_post = :postId AND id_user = :userId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':postId', $id_post);
            $stmt->bindValue(':userId', $id_user);
            $stmt->execute();

            if($stmt->execute()){
                echo json_encode("Item removido com sucesso!");
            }
        }

    }

?>