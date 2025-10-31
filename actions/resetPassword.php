<?php
    require_once '../config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['token'], $_POST['new_password'], $_POST['confirm_password'])) {
            $_SESSION['warning'] ="❌ Invalid data.";
            header('Location: '.$base);
            exit;
        }

        $token = $_POST['token'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Verifica se as senhas coincidem
        if ($newPassword !== $confirmPassword) {
            $_SESSION['warning'] ="❌ Passwords do not match.";
            header('Location: '.$base);
            exit;
        }

        // Hash seguro da nova senha
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Verifica se o token é válido e não expirou
        $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['warning'] ="❌ Invalid or expired token.";
            header('Location: '.$base);
            exit;
        }

        $email = $user['email'];

        // Atualiza a senha do usuário no banco de dados
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        if ($stmt->execute([$hashedPassword, $email])) {
            // Remove o token usado
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            $_SESSION['success'] ="✅ Password set successfully!";
            header('Location: '.$base."/login");
            exit;
        } else {
            $_SESSION['warning'] ="❌ Error resetting password.";
            header('Location: '.$base);
            exit;
        }
    }
?>
