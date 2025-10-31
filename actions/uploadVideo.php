<?php

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = nl2br(filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS));
    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_SPECIAL_CHARS);
    $currentDateTime = date('Y-m-d H:i:s');

    // Receber o arquivo de vídeo
    if (isset($_FILES['video_file'])) {
        
        $tempFilePath = $_FILES['video_file']['tmp_name'];
        $originalFileName = $_FILES['video_file']['name'];
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Gerar um ID único para o vídeo
        $videoId = uniqid();

        // Definir o caminho de destino com o ID único do vídeo
        $destinationPath = 'C:/xampp/htdocs/artuniverse/public/storage/videos/'.$videoId;
        
        
        // Mover o arquivo para o local desejado
        $destinationFilePath = $destinationPath.'.'.$fileExtension;
        move_uploaded_file($tempFilePath, $destinationFilePath);

        // Faça o processamento necessário com o arquivo de vídeo


        //-----------------------//

        $posterFileName = ""; // Definir como vazio por padrão

        if(isset($_FILES['poster_video_file']) && $_FILES['poster_video_file']['error'] === 0 && $_FILES['poster_video_file']['size'] > 0) {
     
            $file_name = $_FILES['poster_video_file']['name'];
            $file_tmp = $_FILES['poster_video_file']['tmp_name'];
            $target_dir = "C:/xampp/htdocs/artuniverse/public/storage/posterVideo/"; // Insira o caminho do diretório de destino
            $target_file = $target_dir . $videoId;
            // Verifica a extensão do arquivo
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
                // Define o caminho final do arquivo
            $posterFileName = $videoId . '.jpg';
            $target_file = $target_dir . $posterFileName; 

            // Verifica se é PNG e converte para JPG
            if ($ext === 'png') {
                $image = imagecreatefrompng($file_tmp);
                imagejpeg($image, $target_file, 90);
                imagedestroy($image);
            } else {
                move_uploaded_file($file_tmp, $target_file);
            }
            
        }

        //-------------------//

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // --------------------------------------------------//

        $addPostTable = "INSERT INTO allposts (id_name, id_user, user_name, file, type, title, description, tags, creation_date, poster) VALUES (:id_name, :id_user, :user_name, :file, :type, :title, :description, :tags, :creation_date, :poster)";
        $sqlPost = $pdo->prepare($addPostTable);
        $sqlPost->bindValue(':id_name', $videoId);
        $sqlPost->bindValue(':id_user', $_SESSION['user']['id']);
        $sqlPost->bindValue(':user_name', $_SESSION['user']['user_name']);
        $sqlPost->bindValue(':file', $videoId.'.'.$fileExtension);
        $sqlPost->bindValue(':type', 'video');
        $sqlPost->bindValue(':title', $title);
        $sqlPost->bindValue(':description', $description);
        $sqlPost->bindValue(':tags', $tags);
        $sqlPost->bindValue(':creation_date', $currentDateTime);
        $sqlPost->bindValue(':poster', $posterFileName);
        $sqlPost->execute();


        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        exit;
            
    }
}
?>
