<?php
    require_once '../config.php';

    if (isset($_GET['file'])) {
        $file = $_GET['file'];

        $stmt = $pdo->prepare("SELECT type FROM allposts WHERE file = :file");
        $stmt->bindValue(':file', $file);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Obtém o resultado da consulta
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = $pdo->prepare("UPDATE allposts SET archive = :newArchive WHERE file = :file AND id_user = :id_user");
            $stmt->bindValue(':newArchive', 'true');
            $stmt->bindValue(':file', $file);
            $stmt->bindValue(':id_user', $_SESSION['user']['id']);
            $stmt->execute();

            $stmt = $pdo->prepare("UPDATE itemsave SET archive = :newArchive WHERE file = :file AND id_user = :id_user");
            $stmt->bindValue(':newArchive', 'true');
            $stmt->bindValue(':file', $file);
            $stmt->bindValue(':id_user', $_SESSION['user']['id']);
            $stmt->execute();

            $stmt = $pdo->prepare("UPDATE playlist SET archive = :newArchive WHERE file = :file AND id_user = :id_user");
            $stmt->bindValue(':newArchive', 'true');
            $stmt->bindValue(':file', $file);
            $stmt->bindValue(':id_user', $_SESSION['user']['id']);
            $stmt->execute();
        }

        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        exit;
        
    } else {
        header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
        exit;
    }

?>