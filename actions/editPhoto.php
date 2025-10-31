<?php
    require_once '../config.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_name = $_POST['id_name'] ?? null;
        $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_SPECIAL_CHARS);
        $currentDateTime = date('Y-m-d H:i:s');

        if (!$id_name) {
            $_SESSION['warning'] = "Invalid ID.";
            header('Location: ' . $base . '/' . $_SESSION['user']['user_name']);
            exit;
        }

        // Verifica se veio imagem nova
        $hasNewImage = isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK;

        if ($hasNewImage) {
            $imageFileName = $_FILES['image_file']['name'];
            $imageTmpPath = $_FILES['image_file']['tmp_name'];
            $imageFileExtension = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));

            $newFileName = $id_name . '.jpg';
            $targetDir = "C:/xampp/htdocs/artuniverse/public/storage/photos/";
            $newFilePath = $targetDir . $newFileName;

            if ($imageFileExtension === 'png') {
                $image = imagecreatefrompng($imageTmpPath);
                imagejpeg($image, $newFilePath, 90);
                imagedestroy($image);
            } elseif ($imageFileExtension === 'jpeg' || $imageFileExtension === 'jpg') {
                move_uploaded_file($imageTmpPath, $newFilePath);
            } else {
                $_SESSION['warning'] = "Only PNG and JPEG/JPG files are supported.";
                header('Location: ' . $base . '/' . $_SESSION['user']['user_name']);
                exit;
            }
        }

        // Atualiza os campos no banco
        $sql = "UPDATE allposts SET title = :title, description = :description, tags = :tags, creation_date = :creation_date WHERE id_name = :id_name AND type = 'image'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':tags', $tags);
        $stmt->bindValue(':creation_date', $currentDateTime);
        $stmt->bindValue(':id_name', $id_name);
        $stmt->execute();

        header('Location: ' . $base . '/view/'.$id_name);
        exit;
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
            $previousPage = $_SERVER['HTTP_REFERER'];
        } else {
            // Tratamento caso a variável não esteja definida ou vazia
            $previousPage = $base;
        }
    exit;

?>