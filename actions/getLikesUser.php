<?php

require_once '../config.php';
header('Content-Type: application/json');

if (isset($_POST['id_user'])) {
    $id_user = $_POST['id_user'];

    $sql = "SELECT * FROM post_likes WHERE id_user = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_user', $id_user);
    $stmt->execute();
    $postLikes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Criar uma array para armazenar os id_post dos likes
    $id_posts = array();

    // Loop para obter os id_post dos likes
    foreach ($postLikes as $like) {
        $id_posts[] = $like['id_post'];
    }

    // Verificar se há id_post para buscar os posts
    if (!empty($id_posts)) {
        // Converter a array de id_post em uma string separada por vírgulas para usar na cláusula IN
        $id_posts_str = implode(",", $id_posts);

        // Consulta para obter todos os posts associados aos likes do usuário
        $sqlP = "SELECT * FROM allposts WHERE id IN ($id_posts_str)";
        $stmtP = $pdo->prepare($sqlP);
        $stmtP->execute();
        $posts = $stmtP->fetchAll(PDO::FETCH_ASSOC);

        // Retornar a array de posts no formato JSON
        echo json_encode($posts);
    } else {
        echo json_encode(array()); // Retorna um JSON vazio se não houver likes associados ao usuário
    }

} else {
    echo json_encode(array('error' => 'ID do usuário não fornecido.'));
}

?>