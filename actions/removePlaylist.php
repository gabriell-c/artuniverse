<?php
    require_once '../config.php';

    // Verificar se os valores foram recebidos
    if (!isset($_POST['postId']) || !isset($_POST['playlistId'])) {
        echo "Erro: postId ou playlistId não foram enviados.";
        exit;
    }

    $postId = $_POST['postId'];
    $userId = $_SESSION['user']['id'] ?? null;
    $playlistId = $_POST['playlistId'];

    if (!$userId) {
        echo "Erro: Usuário não autenticado.";
        exit;
    }

    function deletePlaylist($postId, $userId, $pdo, $playlistId) {
        // Buscar a playlist no banco
        $sqlP = "SELECT * FROM allplaylist WHERE id = :id";
        $stmtP = $pdo->prepare($sqlP);
        $stmtP->bindValue(':id', $playlistId);
        $stmtP->execute();
        $playlistItem = $stmtP->fetch(PDO::FETCH_ASSOC);

        if (!$playlistItem) {
            return "Playlist não encontrada.";
        }

        // Deletar a playlist
        $sql = "DELETE FROM playlist WHERE id_post = :postId AND id_user = :userId AND id_name = :id_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':postId', $postId);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':id_name', $playlistItem['id_name']);
        
        if ($stmt->execute()) {
            return "Playlist deletada com sucesso";
        } else {
            return "Erro ao deletar a playlist";
        }
    }

    // Chama a função e exibe o resultado
    echo deletePlaylist($postId, $userId, $pdo, $playlistId);
?>
