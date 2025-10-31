<?php
require_once '../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: '.$base);
    exit;
}

$id = $_POST['id_name'] ?? null;

if (!$id) {
    $_SESSION['warning'] = 'ID do conteúdo não foi enviado.';
    header('Location: '.$base);
    exit;
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$tags = trim($_POST['tags'] ?? '');

$sql = "SELECT * FROM allposts WHERE id_name = :id_name AND archive = 'false'";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id_name', $id);
$stmt->execute();
$audio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$audio) {
    $_SESSION['warning'] = 'Áudio não encontrado.';
    header('Location: '.$base);
    exit;
}

// Atualiza arquivo de áudio se enviado
$audioFile = $audio['file'];
if (!empty($_FILES['audio_file']['name'])) {
    $ext = pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION);
    $newAudioName = uniqid('audio_').'.'.$ext;
    move_uploaded_file($_FILES['audio_file']['tmp_name'], __DIR__ . '/../public/storage/audios/'.$newAudioName);

    // Remove antigo
    if (!empty($audio['file']) && file_exists(__DIR__ . '/../public/storage/audios/'.$audio['file'])) {
        unlink(__DIR__ . '/../public/storage/audios/'.$audio['file']);
    }

    $audioFile = $newAudioName;
}

// Atualiza poster se enviado
$posterFile = $audio['poster'];
if (!empty($_FILES['poster_audio_file']['name'])) {
    $ext = pathinfo($_FILES['poster_audio_file']['name'], PATHINFO_EXTENSION);
    $newPosterName = uniqid('poster_').'.'.$ext;
    move_uploaded_file($_FILES['poster_audio_file']['tmp_name'], __DIR__ . '/../public/storage/posterAudio/'.$newPosterName);

    // Remove antigo
    if (!empty($audio['poster']) && file_exists(__DIR__ . '/../public/storage/posterAudio/'.$audio['poster'])) {
        unlink(__DIR__ . '/../public/storage/posterAudio/'.$audio['poster']);
    }

    $posterFile = $newPosterName;
}

$update = "UPDATE allposts SET title = :title, description = :description, tags = :tags, file = :file, poster = :poster WHERE id_name = :id_name";
$stmt = $pdo->prepare($update);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':description', $description);
$stmt->bindValue(':tags', $tags);
$stmt->bindValue(':file', $audioFile);
$stmt->bindValue(':poster', $posterFile);
$stmt->bindValue(':id_name', $id);
$stmt->execute();

header('Location: '.$base.'/watch/'.$id ); // ajuste para onde deve redirecionar após editar
exit;
?>
