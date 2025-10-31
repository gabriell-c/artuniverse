<?php
    require_once '../config.php';

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($_SESSION['user']) || empty($data['follower_id']) || empty($data['followed_id'])) {
        echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
        exit;
    }

    $followerId = $data['follower_id'];
    $followedId = $data['followed_id'];

    // Verifica se já segue o usuário
    $sqlCheck = "SELECT * FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['follower_id' => $followerId, 'followed_id' => $followedId]);

    if ($stmtCheck->fetch()) {
        // Se já segue, faz o unfollow (remove da tabela)
        $sqlDelete = "DELETE FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute(['follower_id' => $followerId, 'followed_id' => $followedId]);

        echo json_encode(['success' => true, 'following' => false, 'message' => 'Você deixou de seguir este usuário.']);
    } else {
        // Se não segue, adiciona na tabela (follow)
        $sqlInsert = "INSERT INTO followers (follower_id, followed_id) VALUES (:follower_id, :followed_id)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute(['follower_id' => $followerId, 'followed_id' => $followedId]);

        echo json_encode(['success' => true, 'following' => true, 'message' => 'Você agora está seguindo este usuário.']);
    }
?>
