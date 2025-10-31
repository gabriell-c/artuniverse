<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $videoId = $_POST['id_name']; // Deve vir do form hidden
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = nl2br(filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_SPECIAL_CHARS);
    $currentDateTime = date('Y-m-d H:i:s');

    // Busca dados atuais do post
    $stmt = $pdo->prepare("SELECT * FROM allposts WHERE id_name = :id_name AND id_user = :id_user");
    $stmt->execute([
        ':id_name' => $videoId,
        ':id_user' => $_SESSION['user']['id']
    ]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        exit("Post não encontrado ou não pertence a você.");
    }

    // ---------- Atualizar poster se enviado ----------
    $posterFileName = $post['poster']; // Padrão: manter o atual

    if (isset($_FILES['poster_video_file']) && $_FILES['poster_video_file']['error'] === 0 && $_FILES['poster_video_file']['size'] > 0) {
        $file_tmp = $_FILES['poster_video_file']['tmp_name'];
        $file_name = $_FILES['poster_video_file']['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $posterDir = "C:/xampp/htdocs/artuniverse/public/storage/posterVideo/";
        $newPosterName = $videoId . '.jpg';
        $posterPath = $posterDir . $newPosterName;

        // Apaga o poster antigo (se existir)
        if (!empty($post['poster']) && file_exists($posterDir . $post['poster'])) {
            unlink($posterDir . $post['poster']);
        }

        // Converte PNG para JPG se necessário
        if ($ext === 'png') {
            $image = imagecreatefrompng($file_tmp);
            imagejpeg($image, $posterPath, 90);
            imagedestroy($image);
        } else {
            move_uploaded_file($file_tmp, $posterPath);
        }

        $posterFileName = $newPosterName;
    }

    // ---------- Atualiza no banco ----------
    $update = $pdo->prepare("UPDATE allposts SET title = :title, description = :description, tags = :tags, poster = :poster, creation_date = :updated_at WHERE id_name = :id_name AND id_user = :id_user");
    $update->execute([
        ':title' => $title,
        ':description' => $description,
        ':tags' => $tags,
        ':poster' => $posterFileName,
        ':updated_at' => $currentDateTime,
        ':id_name' => $videoId,
        ':id_user' => $_SESSION['user']['id']
    ]);

    header('Location: '.$base.'/watch/'.$videoId);
    exit;
}
?>
