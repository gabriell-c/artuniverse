<?php

    require_once './config.php';

    // Remove a sessão do usuário
    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    }

    // Remove o cookie de sessão do usuário
    if (isset($_COOKIE['user_session'])) {
        setcookie('user_session', '', time() - 3600, "/"); // Define o cookie para expirar no passado
    }

    header('Location: ' . $base);
    exit;

?>
