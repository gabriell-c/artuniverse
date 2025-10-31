<?php

require_once '../config.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os valores dos campos do formulário
    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $tags = filter_var($_POST['tags'], FILTER_SANITIZE_SPECIAL_CHARS);
    $currentDateTime = date('Y-m-d H:i:s');

    // Recupera o nome e o caminho temporário do arquivo de imagem
    $imageFileName = $_FILES['image_file']['name'];
    $imageTmpPath = $_FILES['image_file']['tmp_name'];

    // Define o diretório de destino para o arquivo
    $targetDir = "C:/xampp/htdocs/artuniverse/public/storage/photos/";

    // Recupera a extensão do arquivo
    $imageFileExtension = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));

    // Gera um ID único para renomear o arquivo
    $idFileName = uniqid();
    $newFileName =  $idFileName. '.jpg';

    // Define o novo caminho e nome do arquivo
    $newFilePath = $targetDir . $newFileName;

    // Verifica se é PNG ou JPEG
    if ($imageFileExtension === 'png') {
        // Converte a imagem PNG para JPG
        $image = imagecreatefrompng($imageTmpPath);
        imagejpeg($image, $newFilePath, 90);
        imagedestroy($image);
    } elseif ($imageFileExtension === 'jpeg' || $imageFileExtension === 'jpg') {
        // Move o arquivo original para o novo caminho com o novo nome
        move_uploaded_file($imageTmpPath, $newFilePath);
    } else {
        // A extensão do arquivo não é suportada
        $_SESSION['warning'] = "Only PNG and JPEG/JPG files are supported.";
        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        exit;
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    //-------------------//


    $addPostTable = "INSERT INTO allposts (id_name, id_user, user_name, file, type, title, description, tags, creation_date, poster) VALUES (:id_name, :id_user, :user_name, :file, :type, :title, :description, :tags, :creation_date, :poster)";
    $sqlPost = $pdo->prepare($addPostTable);
    $sqlPost->bindValue(':id_name', $idFileName);
    $sqlPost->bindValue(':id_user', $_SESSION['user']['id']);
    $sqlPost->bindValue(':user_name', $_SESSION['user']['user_name']);
    $sqlPost->bindValue(':file', $newFileName);
    $sqlPost->bindValue(':type', 'image');
    $sqlPost->bindValue(':title', $title);
    $sqlPost->bindValue(':description', $description);
    $sqlPost->bindValue(':tags', $tags);
    $sqlPost->bindValue(':creation_date', $currentDateTime);
    $sqlPost->bindValue(':poster', '');
    $sqlPost->execute();

}
    header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
    exit;
?>
