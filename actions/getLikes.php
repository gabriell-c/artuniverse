<?php
// getComments.php

// Primeiro, inclua o arquivo de configuração do banco de dados
require_once '../config.php';

// Verifique se a variável $_POST['post_id'] está definida e não está vazia
if (isset($_POST['postId']) && !empty($_POST['postId'])) {
    // Obtenha o ID do post enviado através do formulário
    $postId = $_POST['postId'];

    // Prepare e execute a consulta SQL para buscar os comentários com base no ID do post
    $sql = "SELECT * FROM post_likes WHERE id_post = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();

    // Obtém todos os comentários como um array associativo
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comments);

} else {
    // Se a variável post_id não estiver definida ou estiver vazia, exiba uma mensagem de erro
    echo '<p>Erro: ID do post não fornecido.</p>';
}
?>
