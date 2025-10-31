<?php

require_once '../config.php';

if (isset($_FILES['profile-picture-file'])) {
    $diretorio = '../upload/profilePhoto/';
    $nomeArquivo = $_FILES['profile-picture-file']['name'];
    $tamanhoArquivo = $_FILES['profile-picture-file']['size'];
    $caminhoArquivo = $diretorio . $nomeArquivo;

    // Verifica o tamanho do arquivo (2MB = 2 * 1024 * 1024 bytes)
    $tamanhoMaximo = 2 * 1024 * 1024;

    if ($tamanhoArquivo > $tamanhoMaximo) {
        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        $_SESSION['warning'] = 'O tamanho do arquivo excede o limite máximo permitido (5MB).';
        exit;
    }

    // Verifica a extensão do arquivo
    $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

    // Verifica se é PNG ou JPEG
    if ($extensao == 'png' || $extensao == 'jpeg' || $extensao == 'jpg') {
        // Converte para JPEG se for PNG ou JPEG
        if ($extensao == 'png') {
            $imagem = imagecreatefrompng($_FILES['profile-picture-file']['tmp_name']);
            $caminhoArquivo = $diretorio . uniqid() . '.jpg';

            // Redimensiona a imagem para 300x300 pixels
            $novaImagem = imagecreatetruecolor(300, 300);
            imagecopyresampled($novaImagem, $imagem, 0, 0, 0, 0, 300, 300, imagesx($imagem), imagesy($imagem));

            // Salva a nova imagem em um arquivo JPEG
            imagejpeg($novaImagem, $caminhoArquivo, 90);

            imagedestroy($imagem);
            imagedestroy($novaImagem);
        } elseif ($extensao == 'jpeg' || $extensao == 'jpg') {
            // Renomeia o arquivo para evitar duplicidade
            $caminhoArquivo = $diretorio . uniqid() . '.jpg';
        }

        if (move_uploaded_file($_FILES['profile-picture-file']['tmp_name'], $caminhoArquivo)) {
            // Exclui a foto de perfil anterior, se existir
            $idUsuario = $_SESSION['user']['id'];
            $sql = $pdo->prepare("SELECT profile_photo FROM users WHERE id = :idUsuario");
            $sql->bindValue(':idUsuario', $idUsuario);
            $sql->execute();
            $dadosUsuario = $sql->fetch(PDO::FETCH_ASSOC);

            if ($dadosUsuario !== false && isset($dadosUsuario['profile_photo']) && !empty($dadosUsuario['profile_photo'])) {
                $fotoAnterior = $dadosUsuario['profile_photo'];
                $caminhoFotoAnterior = $diretorio . $fotoAnterior;

                if (file_exists($caminhoFotoAnterior)) {
                    unlink($caminhoFotoAnterior);
                }
            }

            // Atualiza o nome da imagem no banco de dados
            $novoNomeArquivo = basename($caminhoArquivo);
            $sql = $pdo->prepare("UPDATE users SET profile_photo = :novoNomeArquivo WHERE id = :idUsuario");
            $sql->bindValue(':novoNomeArquivo', $novoNomeArquivo);
            $sql->bindValue(':idUsuario', $idUsuario);
            $resultado = $sql->execute();

            if ($resultado) {
                $_SESSION['user']['profile_photo'] = $novoNomeArquivo;
                header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
                exit;
            } else {
                header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
                $_SESSION['warning'] = "Erro interno do servidor ao atualizar a imagem.";
                exit;
            }
        } else {
            header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
            $_SESSION['warning'] = 'Erro ao fazer upload da imagem.';
            exit;
        }
    } else {
        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        $_SESSION['warning'] = 'Formato de arquivo inválido. Apenas imagens PNG, JPG e JPEG são permitidas.';
        exit;
    }
}
?>