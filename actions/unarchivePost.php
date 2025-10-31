<?php
require_once '../config.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// Lê o JSON enviado via POST
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['postId'])) {
    echo json_encode(['success' => false, 'message' => 'ID do post não recebido.']);
    exit;
}

$postId = $data['postId'];
$userId = $_SESSION['user']['id'];

try {
    // Atualiza nas três tabelas
    // Atualiza o post principal
    $stmt = $pdo->prepare("UPDATE allposts SET archive = 'false' WHERE id = :id AND id_user = :id_user");
    $stmt->bindValue(':id', $postId);
    $stmt->bindValue(':id_user', $userId);
    $stmt->execute();

    // Atualiza a tabela itemsave
    $stmt = $pdo->prepare("UPDATE itemsave SET archive = 'false' WHERE id_post = :postId AND id_user = :id_user");
    $stmt->bindValue(':postId', $postId);
    $stmt->bindValue(':id_user', $userId);
    $stmt->execute();

    // Atualiza a tabela playlist
    $stmt = $pdo->prepare("UPDATE playlist SET archive = 'false' WHERE id_post = :postId AND id_user = :id_user");
    $stmt->bindValue(':postId', $postId);
    $stmt->bindValue(':id_user', $userId);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados.']);
}
