s<?php
require_once '../config.php';

// Aqui você deve adicionar a lógica para atualizar o like no banco de dados
// Certifique-se de ajustar o código para suas necessidades específicas

// Obter o ID do post e do usuário
$postId = $_POST['postId'];
$userId = $_SESSION['user']['id'];


function checkLike($postId, $userId, $pdo) {
    
      
    // Preparar a consulta SQL para verificar se o usuário já deu "like" no post
    $sql = "SELECT * FROM post_likes WHERE id_post = :postId AND id_user = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':postId', $postId);
    $stmt->bindValue(':userId', $userId);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (!empty($result)) {
      // O usuário já deu "like" no post
      return true; 
    } else {
      // O usuário ainda não deu "like" no post
      return false;
    //   echo "fds";
    }
}



// Atualizar o "like" no banco de dados
function updateLike($postId, $userId, $pdo) {
    $isLiked = checkLike($postId, $userId, $pdo);

    if ($isLiked) {
        // O usuário já curtiu o post, então é uma ação para descurtir
        $sql = "DELETE FROM post_likes WHERE id_post = :postId AND id_user = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId);
        $stmt->bindValue(':userId', $userId);
        $result = $stmt->execute();

        if ($result) {
            return "Post descurtido com sucesso.";
        } else {
            return "Erro ao descurtir o post.";
        }
    } else {
        // O usuário ainda não curtiu o post, então é uma ação para curtir
        $sql = "INSERT INTO post_likes (id_post, id_user) VALUES (:postId, :userId)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId);
        $stmt->bindValue(':userId', $userId);
        $result = $stmt->execute();

        if ($result) {
            return "Post curtido com sucesso.";
        } else {
            return "Erro ao curtir o post.";
        }
    }
}




// Verificar se o usuário já deu "like" no post
echo updateLike($postId, $userId, $pdo);
?>
