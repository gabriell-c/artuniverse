<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $fullName = filter_var($_POST['full_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $userName = filter_var($_POST['user_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $bioUser = nl2br(filter_var($_POST['bio_user'], FILTER_SANITIZE_SPECIAL_CHARS));
    $phoneNumber = $_POST['phone_number'];
    $dateOfBirth = (preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['date_of_birth'] ?? '')) ? $_POST['date_of_birth'] : exit(header("Location: $base/seu_formulario"));
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
    $linkUser = "https://www.instagram.com/".filter_var($_POST['link_user'], FILTER_SANITIZE_URL);

    if ($fullName && $userName && $email && $phoneNumber && $dateOfBirth && $gender) {
        if (preg_match('/^[A-Za-z0-9._]+$/', $userName)) {
            if (isset($linkUser)) {
                function validateURL($url) {
                    $pattern = '/^(https?|ftp):\/\/([^\s\/$.?#].[^\s]*)$/i';
                    return preg_match($pattern, $url);
                }
                if (!validateURL($linkUser)) {
                    if (isset($_SERVER['HTTP_REFERER'])) {
                        $previousPage = $_SERVER['HTTP_REFERER'];
                    } else {
                        $_SESSION['warning'] = 'URL invalid!';
                        header('Location: '.$base.'/settings');
                        exit;
                    }
                }
            }

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            if ($email != $_SESSION['user']['email'] && $count > 0) {
                $_SESSION['warning'] = 'The provided email user is already in use. Please choose another one.';
                header("Location: ".$base."/setting/profile");
                exit;
            }

            $sql = "SELECT COUNT(*) FROM users WHERE user_name = :user_name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_name', $userName);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            if ($userName != $_SESSION['user']['user_name'] && $count > 0) {
                $_SESSION['warning'] = 'The provided user name is already in use. Please choose another one.';
                header("Location: ".$base."/setting/profile");
                exit;
            }

            $pdo->beginTransaction();

            // Atualiza o usuário na tabela users
            $stmt = "UPDATE users SET full_name = :full_name, user_name = :user_name, email = :email, bio_user = :bio_user, phone_number = :phone_number, date_of_birth = :date_of_birth, gender = :gender, link_user = :link_user WHERE id = :id";
            $sql = $pdo->prepare($stmt);
            $sql->bindValue(':full_name', $fullName);
            $sql->bindValue(':user_name', $userName);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':bio_user', $bioUser);
            $sql->bindValue(':phone_number', $phoneNumber);
            $sql->bindValue(':date_of_birth', $dateOfBirth);
            $sql->bindValue(':gender', $gender);
            $sql->bindValue(':link_user', $linkUser);
            $sql->bindValue(':id', $_SESSION['user']['id']);
            $sql->execute();

            // Atualiza user_name nas demais tabelas
            $tables = ['allplaylist', 'allposts', 'allsave', 'itemsave', 'playlist', 'post_comments'];
            foreach ($tables as $table) {
                $stmt = $pdo->prepare("UPDATE $table SET user_name = :new_user_name WHERE user_name = :old_user_name");
                $stmt->execute([':new_user_name' => $userName, ':old_user_name' => $_SESSION['user']['user_name']]);
            }

            $pdo->commit();

            $_SESSION['user']['full_name'] = $fullName;
            $_SESSION['user']['user_name'] = $userName;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['bio_user'] = $bioUser;
            $_SESSION['user']['phone_number'] = $phoneNumber;
            $_SESSION['user']['date_of_birth'] = $dateOfBirth;
            $_SESSION['user']['gender'] = $gender;
            $_SESSION['user']['link_user'] = $linkUser;
            
            header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
            exit;
        } else {
            $_SESSION['warning'] = 'The username is invalid. Only letters (a-z), numbers (0-9), period (.), or underscore (_) are allowed. Spaces are not permitted.';
            header('Location: '.$base.'/settings');
            exit;
        }
    } else {
        $_SESSION['warning'] = 'Fill in all the data!';
        header('Location: '.$base.'/settings');
        exit;
    }
}

header('Location: '.$base.'/settings');
exit;
?>
 