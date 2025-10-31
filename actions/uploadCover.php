<?php

require_once '../config.php';

if (isset($_FILES['profile-cover-file'])) {
    $diretorio = '../upload/profileCover/';
    $nomeArquivo = $_FILES['profile-cover-file']['name'];
    $tamanhoArquivo = $_FILES['profile-cover-file']['size'];
    $caminhoArquivo = $diretorio . $nomeArquivo;

    // Verifica o tamanho do arquivo (3MB = 3 * 1024 * 1024 bytes)
    $tamanhoMaximo = 3 * 1024 * 1024;

    if ($tamanhoArquivo > $tamanhoMaximo) {
        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        $_SESSION['warning'] = 'The file size exceeds the maximum allowed limit (2MB).';
        exit;
    }

    

    // Verifica a extensão do arquivo
    $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

    // Verifica se é PNG ou JPEG
    if ($extensao == 'png' || $extensao == 'jpeg' || $extensao == 'jpg') {
        // Converte para JPEG se for PNG ou JPEG
        if ($extensao == 'png') {
            $imagem = imagecreatefrompng($_FILES['profile-cover-file']['tmp_name']);
            $caminhoArquivo = $diretorio . uniqid() . '.jpg';

            // Redimensiona a imagem para 300x300 pixels
            $novaImagem = imagecreatetruecolor(900, 300);
            imagecopyresampled($novaImagem, $imagem, 0, 0, 0, 0, 900, 300, imagesx($imagem), imagesy($imagem));

            // Salva a nova imagem em um arquivo JPEG
            imagejpeg($novaImagem, $caminhoArquivo, 90);

            imagedestroy($imagem);
            imagedestroy($novaImagem);
        } elseif ($extensao == 'jpeg' || $extensao == 'jpg') {
            // Renomeia o arquivo para evitar duplicidade
            $caminhoArquivo = $diretorio . uniqid() . '.jpg';
        }

        if (move_uploaded_file($_FILES['profile-cover-file']['tmp_name'], $caminhoArquivo)) {
            // Exclui a foto de perfil anterior, se existir
            $idUsuario = $_SESSION['user']['id'];
            $sql = $pdo->prepare("SELECT cover_photo FROM users WHERE id = :idUsuario");
            $sql->bindValue(':idUsuario', $idUsuario);
            $sql->execute();
            $dadosUsuario = $sql->fetch(PDO::FETCH_ASSOC);

            if ($dadosUsuario !== false && isset($dadosUsuario['cover_photo']) && !empty($dadosUsuario['cover_photo'])) {
                $fotoAnterior = $dadosUsuario['cover_photo'];
                $caminhoFotoAnterior = $diretorio . $fotoAnterior;

                if (file_exists($caminhoFotoAnterior)) {
                    unlink($caminhoFotoAnterior);
                }
            }

            // Atualiza o nome da imagem no banco de dados
            $novoNomeArquivo = basename($caminhoArquivo);
            $sql = $pdo->prepare("UPDATE users SET cover_photo = :novoNomeArquivo WHERE id = :idUsuario");
            $sql->bindValue(':novoNomeArquivo', $novoNomeArquivo);
            $sql->bindValue(':idUsuario', $idUsuario);
            $resultado = $sql->execute();

            if ($resultado) {
                header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
                exit;
            } else {
                header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
                $_SESSION['warning'] = "Internal server error while updating/adding image.";
                exit;
            }
        } else {
            header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
            $_SESSION['warning'] = 'Error uploading image.';
            exit;
        }
    } else {
        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        $_SESSION['warning'] = 'Invalid file format. Only PNG, JPG and JPEG images are allowed.';
        exit;
    }
}
?>