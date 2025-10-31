<?php
    require_once '../config.php'; // Inclui a conexão com o banco
    session_start();

    if (!isset($_GET['contentId'])) {
        http_response_code(400);
        exit;
    }

    $contentId = (int) $_GET['contentId'];
    $user_id = $_SESSION['user']['id'] ?? null;
    $ip = $_SERVER['REMOTE_ADDR']; // Captura o IP do usuário

    try {
        // Verifica se já tem uma view recente nos últimos 30 minutos
        $stmt = $pdo->prepare("SELECT id FROM video_views 
                               WHERE id_video = ? 
                               AND (id_user = COALESCE(?, id_user) OR ip_address = ?) 
                               AND viewed_at >= NOW() - INTERVAL 30 MINUTE");
        $stmt->execute([$contentId, $user_id, $ip]);

        if ($stmt->rowCount() == 0) { // Só registra se não tiver view recente
            $stmt = $pdo->prepare("INSERT INTO video_views (id_video, id_user, ip_address, viewed_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$contentId, $user_id, $ip]);
        }

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        error_log("Erro ao registrar view: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['status' => 'error']);
    }
?>
