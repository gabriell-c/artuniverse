<?php
require_once '../config.php';

function generateUniqueId() {
    return uniqid('', true);
}
$sourceWidth = 0;
$sourceHeight = 0;
$targetDir = "C:/xampp/htdocs/artuniverse/public/storage/posterPlaylist/";

$title = $_POST["title_playlist"];
$postId = $_POST["postId"];
$privacy = $_POST["privacy_playlist"];
$id_name = generateUniqueId().time();

function cropAndCenterImage($sourceImage, $targetImage) {
    list($sourceWidth, $sourceHeight) = getimagesize($sourceImage);
    $targetWidth = $targetHeight = 500;

    $sourceImageObj = imagecreatefromjpeg($sourceImage);

    $sourceAspectRatio = $sourceWidth / $sourceHeight;
    $targetAspectRatio = $targetWidth / $targetHeight;

    $sourceX = $sourceY = 0;
    $sourceCropWidth = $sourceWidth;
    $sourceCropHeight = $sourceHeight;

    if ($sourceAspectRatio > $targetAspectRatio) {
        $sourceCropWidth = $sourceHeight * $targetAspectRatio;
        $sourceX = ($sourceWidth - $sourceCropWidth) / 2;
    } else {
        $sourceCropHeight = $sourceWidth / $targetAspectRatio;
        $sourceY = ($sourceHeight - $sourceCropHeight) / 2;
    }

    $targetImageObj = imagecreatetruecolor($targetWidth, $targetHeight);
    imagecopyresampled($targetImageObj, $sourceImageObj, 0, 0, $sourceX, $sourceY, $targetWidth, $targetHeight, $sourceCropWidth, $sourceCropHeight);
    imagejpeg($targetImageObj, $targetImage, 100);

    imagedestroy($sourceImageObj);
    imagedestroy($targetImageObj);
}


// Verifica se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    

 
    // Verifica se uma imagem de capa foi enviada
    $coverImage = '';
    if (isset($_FILES["poster_playlist"]) && $_FILES["poster_playlist"]["error"] === UPLOAD_ERR_OK) {
        $imageFileType = pathinfo($_FILES["poster_playlist"]["name"], PATHINFO_EXTENSION);
        list($sourceWidth, $sourceHeight) = getimagesize($_FILES["poster_playlist"]["tmp_name"]);

        // Gera um ID único para o nome do arquivo
        $uniqueId = generateUniqueId();

        // Define o novo nome do arquivo com ID único e extensão JPG
        $newFileName = $targetDir . $uniqueId . ".jpg";


        try{


            // Converte a imagem PNG para JPG e salva no diretório de destino
            if ($imageFileType == "png") {
                
                // Carrega a imagem original em formato PNG
                $image = imagecreatefrompng($_FILES["poster_playlist"]["tmp_name"]);
            
                // Define o novo nome do arquivo com ID único e extensão JPG
                $newFileName = $targetDir . $uniqueId . ".jpg";
            
                // Cria uma nova imagem vazia no formato JPG com o mesmo tamanho da imagem original
                $targetImageObj = imagecreatetruecolor($sourceWidth, $sourceHeight);
            
                // Copia a imagem original para a imagem vazia, o que fará a conversão para JPG
                imagecopy($targetImageObj, $image, 0, 0, 0, 0, $sourceWidth, $sourceHeight);
            
                // Salva a imagem redimensionada em formato JPG
                imagejpeg($targetImageObj, $newFileName, 100);
            
                // Libera a memória da imagem original e da imagem vazia
                imagedestroy($image);
                imagedestroy($targetImageObj);
            
                // Salva o nome do arquivo da imagem para uso posterior
                
                $coverImage = $uniqueId . ".jpg";

                
            } elseif ($imageFileType == "jpg" || $imageFileType === "jpeg") {

                // Para imagens JPG, apenas move o arquivo para o destino final
                $targetFileName = $targetDir . $uniqueId . ".jpg";

                move_uploaded_file($_FILES["poster_playlist"]["tmp_name"], $targetFileName);
                $coverImage = $uniqueId . ".jpg";
                
            } else {
                $response = array(
                    "success" => false,
                    "message" => "Erro ao criar a playlist. Tente novamente mais tarde."
                );
                echo json_encode($response);
                exit;
            }
        } catch(PDOException $e) {
            // Em caso de exceção, retorna um erro
            $response = array(
                "success" => false,
                "message" => "Erro no banco de dados: " . $e->getMessage()
            );
            echo json_encode($response);
            exit;
        }


    }else{
        $coverImage = '';
    }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtPo = $pdo->prepare("SELECT * FROM allposts WHERE id = :id_post");
        $stmtPo->bindValue(':id_post', $postId, PDO::PARAM_INT);
        $stmtPo->execute();
        $postItem = $stmtPo->fetch(PDO::FETCH_ASSOC);


        if($postItem['type'] === 'video'){
        // Prepare e execute a consulta para inserir os dados na tabela de playlists
        $stmt = $pdo->prepare("INSERT INTO allplaylist (id_name, user_name, playlist_name, privacy, poster, type) VALUES (:id_name, :user_name, :title, :privacy, :coverImage, :type)");
        $stmt->bindValue(':id_name', $id_name);
        $stmt->bindValue(':user_name', $_SESSION['user']['user_name']);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':privacy', $privacy);
        $stmt->bindValue(':coverImage', $coverImage );
        $stmt->bindValue(':type', 'video');
        }elseif($postItem['type'] === 'audio'){
            $stmt = $pdo->prepare("INSERT INTO allplaylist (id_name, user_name, playlist_name, privacy, poster, type) VALUES (:id_name, :user_name, :title, :privacy, :coverImage, :type)");
            $stmt->bindValue(':id_name', $id_name);
            $stmt->bindValue(':user_name', $_SESSION['user']['user_name']);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':privacy', $privacy);
            $stmt->bindValue(':coverImage', $coverImage);
            $stmt->bindValue(':type', 'audio');
        }
        
        if ($stmt->execute()) {
            // A playlist foi criada com sucesso
            // Após a execução bem sucedida da inserção, obtenha o ID da playlist recém-criada
            $stmtP = $pdo->prepare("INSERT INTO playlist (id_post, id_user, user_name, file, type, title, description, tags, creation_date, poster, archive, id_name)
            VALUES (:id_post, :id_user, :user_name, :file, :type, :title, :description, :tags, :creation_date, :poster, :archive, :id_name) ");
            $stmtP->bindValue(':id_post', $postId);
            $stmtP->bindValue(':id_user', $postItem['id_user']);
            $stmtP->bindValue(':user_name', $postItem['user_name']);
            $stmtP->bindValue(':file', $postItem['file']);
            $stmtP->bindValue(':type', $postItem['type']);
            $stmtP->bindValue(':title', $postItem['title']);
            $stmtP->bindValue(':description', $postItem['description']);
            $stmtP->bindValue(':tags', $postItem['tags']);
            $stmtP->bindValue(':creation_date', $postItem['creation_date']);
            $stmtP->bindValue(':poster', $postItem['poster']);
            $stmtP->bindValue(':archive', $postItem['archive']);
            $stmtP->bindValue(':id_name', $id_name);

            if($stmtP->execute()){
                $response = array(
                    "success" => true,
                    "message" => "Playlist criada com sucesso!",
                );
    
                echo json_encode($response);
                exit;
            }else{
                $response = array(
                    "success" => false,
                    "message" => "Erro ao adicionar a playlist. Tente novamente mais tarde."
                );
                echo json_encode($response);
                exit;
            }

        } else {
            // Houve um erro ao inserir a playlist no banco de dados
            $response = array(
                "success" => false,
                "message" => "Erro ao criar a playlist. Tente novamente mais tarde."
            );

            echo json_encode($response);
            exit;
        }
    } 
    // Envia a resposta para o cliente em formato JSON
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;


// Caso ocorra algum erro ou a requisição não seja válida, retorna uma resposta de erro
$response = array(
    "success" => false,
    "message" => "Erro ao criar a playlist. Dados inválidos."
);

header("Content-Type: application/json");
echo json_encode($response);
exit;

?>
