<?php

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = nl2br(filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_SPECIAL_CHARS);
    $currentDateTime = date('Y-m-d H:i:s');
    // Processamento do arquivo de áudio
    if (isset($_FILES['audio_file'])) {
        
        $tempFilePath = $_FILES['audio_file']['tmp_name'];
        
        $originalFileName = $_FILES['audio_file']['name'];
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Gerar um ID único para o áudio
        $audioId = uniqid();
        

        // Definir o caminho de destino para o áudio
        $destinationPath = 'C:/xampp/htdocs/artuniverse/public/storage/audios/' . $audioId;
        $destinationFilePath = $destinationPath . '.' . $fileExtension;

        // Mover o arquivo para o diretório correto
        move_uploaded_file($tempFilePath, $destinationFilePath);

        // Variável para armazenar o nome do poster (caso exista)
        $posterFileName = "";



        // Processamento do upload do poster
        if (isset($_FILES['poster_audio_file']) && $_FILES['poster_audio_file']['error'] === 0 && $_FILES['poster_audio_file']['size'] > 0) {
            $file_name = $_FILES['poster_audio_file']['name'];
            $file_tmp = $_FILES['poster_audio_file']['tmp_name'];
            $target_dir = "C:/xampp/htdocs/artuniverse/public/storage/posterAudio/"; // Diretório para os posters
            $posterFileName = $audioId . '.jpg'; // Nome do arquivo do poster
            $target_file = $target_dir . $posterFileName;

            // Verifica a extensão do arquivo
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Se for PNG, converte para JPG
            if ($ext === 'png') {
                $image = imagecreatefrompng($file_tmp);
                imagejpeg($image, $target_file, 90);
                imagedestroy($image);
            } else {
                move_uploaded_file($file_tmp, $target_file);
            }
        }

        // Salvar os dados no banco de dados
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $addPostTable = "INSERT INTO allposts (id_name, id_user, user_name, file, type, title, description, tags, creation_date, poster) 
                         VALUES (:id_name, :id_user, :user_name, :file, :type, :title, :description, :tags, :creation_date, :poster)";
        $sqlPost = $pdo->prepare($addPostTable);
        $sqlPost->bindValue(':id_name', $audioId);
        $sqlPost->bindValue(':id_user', $_SESSION['user']['id']);
        $sqlPost->bindValue(':user_name', $_SESSION['user']['user_name']);
        $sqlPost->bindValue(':file', $audioId . '.' . $fileExtension);
        $sqlPost->bindValue(':type', 'audio');
        $sqlPost->bindValue(':title', $title);
        $sqlPost->bindValue(':description', $description);
        $sqlPost->bindValue(':tags', $tags);
        $sqlPost->bindValue(':creation_date', $currentDateTime);
        $sqlPost->bindValue(':poster', $posterFileName);
        $sqlPost->execute();

        // Redireciona o usuário após o upload
        header('Location: ' . $base . '/' . $_SESSION['user']['user_name']);
        exit;
    }
}
?>
