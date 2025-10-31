<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Certifique-se de ter instalado o PHPMailer via Composer
require_once '../config.php'; // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipientEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['warning'] = "❌ Invalid email!";
        header('Location: '.$base);
        exit;
    }

    // Verifica se o e-mail existe no banco
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$recipientEmail]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['warning'] = "❌ Email not found in the system.";
        header('Location: '.$base);
        exit;
    }

    // Gerar token seguro
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expira em 1 hora

    // Apaga tokens antigos desse e-mail
    $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$recipientEmail]);

    // Salva o token no banco
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$recipientEmail, $token, $expiresAt]);

    // Criar link com o token
    $resetLink = "http://localhost/artuniverse/reset_password/$token";

    // Enviar o e-mail com PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP do Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'xxxsadlife07@gmail.com'; // Seu e-mail Gmail
        $mail->Password   = 'nbnazvrfummqtsoz'; // Senha de app do Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Configuração do e-mail de envio
        $mail->setFrom('xxxsadlife07@gmail.com', 'Artuniverse');
        $mail->addAddress($recipientEmail);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Recuperação de Senha - Artuniverse';
        $mail->Body = "Olá, <br><br> Clique no link abaixo para redefinir sua senha (válido por 1 hora):<br>
        <a href='$resetLink'>$resetLink</a><br><br> Se você não solicitou isso, ignore este e-mail.";

        $mail->send();
        $_SESSION['success'] = "✅ Email sent successfully! Check your inbox.";
        header('Location: '.$base);
        exit;
    } catch (Exception $e) {
        $_SESSION['warning'] = "❌ Error sending email: {$mail->ErrorInfo}";
        header('Location: '.$base);
        exit;
    }
}
?>
