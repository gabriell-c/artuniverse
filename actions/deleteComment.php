<?php
    require_once '../config.php'; // Verifique o caminho correto para o arquivo de configuração do seu banco de dados

// Verifica se a solicitação é uma requisição POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se o parâmetro "comment_id" foi enviado pela solicitação AJAX
    if (isset($_POST["comment_id"])) {
        // Obtém o ID do comentário a ser excluído do parâmetro "comment_id"
        $comment_id = $_POST["comment_id"];

        // Realize a lógica para excluir o comentário no banco de dados (substitua os detalhes abaixo pela sua própria lógica de banco de dados)
        // Exemplo: Suponha que você esteja usando PDO para acessar o banco de dados


        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "DELETE FROM post_comments WHERE id = :comment_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
            $stmt->execute();

            // Se a exclusão for bem-sucedida, envie uma resposta de sucesso
            echo json_encode(array("status" => "success"));
        } catch (PDOException $e) {
            // Se ocorrer algum erro durante a exclusão, envie uma resposta de erro
            echo json_encode(array("status" => "error", "message" => "Erro ao excluir o comentário."));
        }
    } else {
        // Se o parâmetro "comment_id" não for fornecido na solicitação, envie uma resposta de erro
        echo json_encode(array("status" => "error", "message" => "ID do comentário não fornecido."));
    }
} else {
    // Se a solicitação não for uma requisição POST, envie uma resposta de erro
    echo json_encode(array("status" => "error", "message" => "Método de solicitação inválido."));
}
?>
