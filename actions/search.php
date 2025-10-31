<?php
header('Content-Type: application/json; charset=UTF-8'); // Garante que a resposta seja JSON puro

require_once '../config.php'; // Conexão com o banco

$searchTerm = $_GET['q'] ?? ''; // Pega o termo da URL
$searchTerm = trim($searchTerm); // Remove espaços extras

if (!$searchTerm) {
    echo json_encode(['error' => 'Nenhum termo de busca fornecido']);
    exit;
}

try {
    $results = [];

    // 🔹 1. Busca FULLTEXT nos posts (vídeos, fotos, músicas)
    $stmt = $pdo->prepare("
        SELECT 
            p.id, p.id_name, p.user_name, p.title, p.description, p.creation_date, 
            p.poster, p.type AS post_type, COUNT(v.id) AS video_views,
            u.profile_photo, 'post' AS type
        FROM allposts p
        LEFT JOIN users u ON p.user_name = u.user_name
        LEFT JOIN video_views v ON p.id = v.id_video
        WHERE p.archive = 'false' AND MATCH(p.title, p.description) AGAINST (?)
        GROUP BY p.id, u.profile_photo
        ORDER BY video_views DESC
        LIMIT 10
    ");
    $stmt->execute([$searchTerm]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($posts) > 0) {
        $results = array_merge($results, $posts);
    } else {
        // 🔹 2. Fallback para LIKE (caso FULLTEXT não encontre resultados)
        $stmt = $pdo->prepare("
            SELECT 
                p.id, p.id_name, p.user_name, p.title, p.description, p.creation_date, 
                p.poster, p.type AS post_type, COUNT(v.id) AS video_views,
                u.profile_photo, 'post' AS type
            FROM allposts p
            LEFT JOIN users u ON p.user_name = u.user_name
            LEFT JOIN video_views v ON p.id = v.id_video
            WHERE p.archive = 'false' AND (p.title LIKE ? OR p.description LIKE ?)
            GROUP BY p.id, u.profile_photo
            LIMIT 10
        ");
        $likeTerm = "%{$searchTerm}%";
        $stmt->execute([$likeTerm, $likeTerm]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = array_merge($results, $posts);
    }

    // 🔹 3. Busca usuários parecidos (para erros de digitação) com seguidores
    $stmt = $pdo->query("
        SELECT u.id, u.user_name, u.full_name, u.bio_user, u.profile_photo, 
               COUNT(f.follower_id) AS followers_count 
        FROM users u
        LEFT JOIN followers f ON u.id = f.followed_id
        GROUP BY u.id, u.user_name, u.full_name, u.bio_user, u.profile_photo
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $userResults = [];
    foreach ($users as $user) {
        $levenshteinDistance = levenshtein(strtolower($searchTerm), strtolower($user['user_name']));
        if ($levenshteinDistance <= 3) { // Permite erro de até 3 caracteres
            $userResults[] = [
                'id' => $user['id'],
                'user_name' => $user['user_name'],
                'full_name' => $user['full_name'],
                'bio_user' => $user['bio_user'],
                'profile_photo' => $user['profile_photo'],
                'followers_count' => $user['followers_count'],
                'type' => 'user',
                'relevance' => $levenshteinDistance // Quanto menor, mais relevante
            ];
        }
    }

    // 🔹 4. Busca posts parecidos usando Levenshtein
    $stmt = $pdo->query("SELECT id, id_name, user_name, title, description, creation_date, poster, type AS post_type FROM allposts WHERE archive = 'false'");
    $allPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $similarPosts = [];
    foreach ($allPosts as $post) {
        $levenshteinDistance = levenshtein(strtolower($searchTerm), strtolower($post['title']));
        if ($levenshteinDistance <= 3) { // Permite erro de até 3 caracteres
            $similarPosts[] = [
                'id' => $post['id'],
                'id_name' => $post['id_name'],
                'user_name' => $post['user_name'],
                'title' => $post['title'],
                'description' => $post['description'],
                'creation_date' => $post['creation_date'],
                'poster' => $post['poster'],
                'post_type' => $post['post_type'],
                'type' => 'post',
                'relevance' => $levenshteinDistance // Quanto menor, mais relevante
            ];
        }
    }

    // Ordena usuários e posts por menor distância Levenshtein (mais parecido primeiro)
    usort($userResults, fn($a, $b) => $a['relevance'] - $b['relevance']);
    usort($similarPosts, fn($a, $b) => $a['relevance'] - $b['relevance']);

    // Junta os resultados de posts e usuários
    $results = array_merge($results, $userResults, $similarPosts);

    // 🔹 Retorna JSON válido
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro no banco: ' . $e->getMessage()]);
}