<?php
require_once '../config.php'; // Inclua o arquivo config.php com a conexão ao banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $userName = filter_var($_POST['UserNameLogin'], FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'passwordLogin', FILTER_SANITIZE_SPECIAL_CHARS);

    // Verifique se todos os campos foram preenchidos
    if (!empty($userName) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE user_name = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $userName);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['warning'] = 'Invalid username or password.';
            header("Location: ".$base."/login");
            exit();
        } else {
            $_SESSION['user'] = $user;
    
            // Se "Lembrar-me" estiver marcado, armazenar no cookie
            if (isset($_POST['rememberMe'])) {
                $cookieData = json_encode($user); // Converte para JSON
                setcookie("user_session", $cookieData, time() + (30 * 24 * 60 * 60), "/"); // Expira em 30 dias
            }
    
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                header("Location: ".$base); // fallback
                exit();
            }

        }
    } else {
        // Preencha todos os campos
        $_SESSION['warning'] = 'Please fill in all fields.';
        header("Location: ".$base."/login");
        exit();
    }
}
?>
