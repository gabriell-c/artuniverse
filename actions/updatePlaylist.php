<?php

    require_once '../config.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o campo "poster_playlist" foi enviado
    if (isset($_FILES['poster_playlist']) && $_FILES['poster_playlist']['error'] === UPLOAD_ERR_OK) {
        
        $sql = "SELECT * FROM allplaylist WHERE id_name = :id_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id_name', $_POST['id_name_playlist']);
        $stmt->execute();
        $playlist = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se já existe uma imagem anterior associada à playlist
        if ($playlist['poster'] !== '' && file_exists('../public/storage/posterPlaylist/' . $playlist['poster'])) {
            // Apaga a imagem anterior
            unlink('../public/storage/posterPlaylist/' . $playlist['poster']);
        }

        $posterTmpPath = $_FILES['poster_playlist']['tmp_name']; // Caminho temporário do arquivo
        $posterFileName = $_FILES['poster_playlist']['name']; // Nome original do arquivo
        $posterFileType = $_FILES['poster_playlist']['type']; // Tipo MIME do arquivo

        // Verifica se o arquivo é uma imagem JPG ou PNG
        if ($posterFileType === 'image/jpeg' || $posterFileType === 'image/png') {
            // Redimensiona a imagem para 500x500 pixels
            $posterImage = imagecreatefromstring(file_get_contents($posterTmpPath));
            $newWidth = 500;
            $newHeight = 500;
            $resizedImage = imagescale($posterImage, $newWidth, $newHeight);

            // Gera um nome único para a imagem
            $posterUniqueName = uniqid('', true) . '.jpg'; // Nome único com extensão JPG
            $posterDestPath = '../public/storage/posterPlaylist/' . $posterUniqueName;

            // Salva a imagem redimensionada na pasta de destino
            imagejpeg($resizedImage, $posterDestPath);

            // Libera a memória utilizada pelas imagens temporárias
            imagedestroy($posterImage);
            imagedestroy($resizedImage);

            // Agora, você pode utilizar o caminho da imagem armazenado em $posterDestPath para atualizar o banco de dados ou fazer outras operações necessárias.
            // Lembre-se de fazer as validações adequadas antes de armazenar o caminho no banco de dados para evitar problemas de segurança.
            // Por exemplo, utilize prepared statements ao lidar com dados do usuário no banco de dados.

            // Exemplo de como atualizar o título e privacidade no banco de dados
            $title = $_POST['title_playlist'];
            $privacy = $_POST['privacy_playlist'];
            $id_name = $_POST['id_name_playlist'];

            // Código para atualizar os dados no banco de dados usando prepared statements
            // Substitua o exemplo de conexão com o banco de dados e prepare a declaração com os campos reais do seu banco de dados
            $sql = "UPDATE allplaylist SET playlist_name = :title, privacy = :privacy, poster = :poster WHERE id_name = :id_name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':privacy', $privacy);
            $stmt->bindValue(':poster', $posterUniqueName);
            $stmt->bindValue(':id_name', $id_name); // 🔥 Correção feita aqui!
            $stmt->execute();
            
            if (isset($_SERVER['HTTP_REFERER'])) {
                $previousPage = $_SERVER['HTTP_REFERER'];
            } else {
                // Tratamento caso a variável não esteja definida ou vazia
                $previousPage = $base.'/playlist/'.$id_name;
            }
    
            header('Location: '.$previousPage);
            exit;
        } else {

            $_SESSION['warning'] = 'only jpg/jpeg or png image is accepted';
            if (isset($_SERVER['HTTP_REFERER'])) {
                $previousPage = $_SERVER['HTTP_REFERER'];
            } else {
                // Tratamento caso a variável não esteja definida ou vazia
                $previousPage = $base.'/playlist/'.$id_name;
            }
    
            header('Location: '.$previousPage);
            exit;
        }
    } else {
        $title = $_POST['title_playlist'];
        $privacy = $_POST['privacy_playlist'];
        $id_name = $_POST['id_name_playlist'];

        // Código para atualizar os dados no banco de dados usando prepared statements
        // Substitua o exemplo de conexão com o banco de dados e prepare a declaração com os campos reais do seu banco de dados
        $sql = "UPDATE allplaylist SET playlist_name = :title, privacy = :privacy WHERE id_name = :id_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':privacy', $privacy);
        $stmt->bindValue(':id_name', $id_name);
        $stmt->execute();

        if (isset($_SERVER['HTTP_REFERER'])) {
            $previousPage = $_SERVER['HTTP_REFERER'];
        } else {
            // Tratamento caso a variável não esteja definida ou vazia
            $previousPage = $base.'/playlist/'.$id_name;
        }

        header('Location: '.$previousPage);
        exit;
    }
}

    $_SESSION['warning'] = 'fill in all fields of the form';
    if (isset($_SERVER['HTTP_REFERER'])) {
        $previousPage = $_SERVER['HTTP_REFERER'];
    } else {
        // Tratamento caso a variável não esteja definida ou vazia
        $previousPage = $base.'/playlist/'.$id_name;
    }

    header('Location: '.$previousPage);
    exit;

?>
