<?php
    require_once '../config.php';

    // Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verifica se o campo 'content' está presente no corpo da requisição
        if (isset($_POST['content'])) {
            $comment = $_POST['content'];
            // Conecta ao banco de dados (você pode incluir aqui o código de configuração do banco)
            $currentTime = date('Y-m-d H:i:s');

            // Obtém o ID do usuário que está fazendo o comentário (você pode usar o ID do usuário logado)
            $userId = $_SESSION['user']['id'];

            // Obtém o ID do post ao qual o comentário será associado (você pode passar esse ID por parâmetro na requisição)
            $postId = $_POST['post_id'];

            // Obtém o nome de usuário e a foto do perfil do usuário (você pode obtê-los de acordo com sua lógica)
            $userName = $_SESSION['user']['user_name']; // Substitua pelo nome de usuário do usuário logado
            // $profilePhoto = $_SESSION['user']['profile_photo'] === null ? 'saturn_background.jpg' : $_SESSION['user']['profile_photo']; // Substitua pelo nome do arquivo da foto do perfil

            // Insere o novo comentário no banco de dados
            $sql = "INSERT INTO post_comments (id_post, id_user, user_name, comment, comment_date) VALUES (:post_id, :user_id, :user_name, :comment, :comment_date)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':user_name', $userName);
            $stmt->bindValue(':comment', $comment);
            $stmt->bindValue(':comment_date', $currentTime); // Utilize ':comment_date' em vez de ":comment_date"
            $stmt->execute();

            // Resposta para a requisição (você pode retornar uma mensagem de sucesso ou redirecionar para a página do post, por exemplo)
            echo 'Comentário adicionado com sucesso!';
        } else {
            // Caso o campo 'content' não esteja presente na requisição
            echo 'Campo de comentário não encontrado na requisição.';
        }
    } else {
        // Caso o método da requisição não seja POST
        echo 'Requisição inválida.';
    }
?>
