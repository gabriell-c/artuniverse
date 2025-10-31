<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = filter_var($_POST['user_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $currentPassword = filter_var($_POST['current_password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $newPassword = filter_var($_POST['new_password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $againNewPassword = filter_var($_POST['repeat_new_password'], FILTER_SANITIZE_SPECIAL_CHARS);

    if($userName && $currentPassword && $newPassword && $againNewPassword){
        if($userName !== $_SESSION['user']['user_name']){
            $_SESSION['warning'] = 'Invalid username, enter your username correctly';
            header('Location: '.$base.'/setting/profile/');
            exit;
        }
        
        if(!password_verify($currentPassword, $_SESSION['user']['password'])){
            $_SESSION['warning'] = 'Current password invalid, enter your current password correctly';
            header('Location: '.$base.'/setting/profile/');
            exit;
        }

        if($newPassword !== $againNewPassword){
            $_SESSION['warning'] = 'repeat the new password correctly!';
            header('Location: '.$base.'/setting/profile/');
            exit;
        }

        if (preg_match('/\d/', $newPassword) && preg_match('/[a-zA-Z]/', $newPassword)) {
            
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "UPDATE users SET password = :new_password WHERE user_name = :user_name AND id = :id";
            $user = $pdo->prepare($sql);
            $user->bindValue(':user_name', $userName);
            $user->bindValue(':new_password', password_hash($newPassword, PASSWORD_DEFAULT));
            $user->bindValue(':id', $_SESSION['user']['id']);
            $user->execute();
            $newPasswordChanged = $user->fetch(PDO::FETCH_ASSOC);

            


            
            $_SESSION['user']['password'] = $newPassword;
            header('Location: '.$base.'/'.$_SESSION['user']['user_name']);
            exit;
        }else{
            $_SESSION['warning'] = 'The password does not contain any numbers or letters';
            header('Location: '.$base.'/setting/profile/');
            exit;
        }
        
    }else{
        $_SESSION['warning'] = 'Fill in all the data!';
        header('Location: '.$base.'/setting/profile/');
        exit;
    }
}
    header('Location: '.$base.'/setting/profile/');
    exit;
?>
