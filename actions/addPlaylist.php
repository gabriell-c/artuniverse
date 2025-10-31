<?php
require_once '../config.php';


$postId = $_POST['postId'];
$userId = $_SESSION['user']['id'];
$playlistId = $_POST['playlistId'];



function savePlaylist($postId, $userId, $pdo, $playlistId) {

    if ($postId && $userId && $playlistId) {

        $sqlAll = "SELECT * FROM allposts WHERE id = :post_id";
        $stmtAll = $pdo->prepare($sqlAll);
        $stmtAll->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmtAll->execute();
        $postItem = $stmtAll->fetch(PDO::FETCH_ASSOC);

        //----------------------------//

        $sqlP = "SELECT * FROM allplaylist WHERE id = :id";
        $stmtP = $pdo->prepare($sqlP);
        $stmtP->bindValue(':id', $playlistId);
        $stmtP->execute();
        $playlistItem = $stmtP->fetch(PDO::FETCH_ASSOC);


        //-----------------------------//

        $sql = "INSERT INTO playlist (id_post, id_user, user_name, file, type, title, description, tags, creation_date, poster, archive, id_name)
        VALUES (:id_post, :id_user, :user_name, :file, :type, :title, :description, :tags, :creation_date, :poster, :archive, :id_name)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_post', $postId);
        $stmt->bindValue(':id_user', $userId);
        $stmt->bindValue(':user_name', $postItem['user_name']);
        $stmt->bindValue(':file', $postItem['file']);
        $stmt->bindValue(':type', $postItem['type']);
        $stmt->bindValue(':title', $postItem['title']);
        $stmt->bindValue(':description', $postItem['description']);
        $stmt->bindValue(':tags', $postItem['tags']);
        $stmt->bindValue(':creation_date', $postItem['creation_date']);
        $stmt->bindValue(':poster', $postItem['poster']);
        $stmt->bindValue(':archive', $postItem['archive']);
        $stmt->bindValue(':id_name', $playlistItem['id_name']);
        $result = $stmt->execute();

        if ($result) {
            return "Item adicionado a playlist com sucesso";
        } else {
            return "Erro ao adicionar a playlist";
        }
    } else {
        return "Faltando informações necessárias";
    }
}

echo savePlaylist($postId, $userId, $pdo, $playlistId);
?>
