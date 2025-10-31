<?php

    $base = "http://localhost/artuniverse";
    $URL = $_SERVER['REQUEST_URI'];
    session_start();
    // Verifica se a sessão do usuário está vazia, mas o cookie existe
    if (!isset($_SESSION['user']) && isset($_COOKIE['user_session'])) {
        $_SESSION['user'] = json_decode($_COOKIE['user_session'], true);
    }
    

    $ip = $_SERVER['REMOTE_ADDR']; // Obtém o endereço IP do usuário atual
    $ip = '131.100.134.78'; // Obtém o endereço IP do usuário atual
    $urlIP = "http://ipwho.is/".$ip; // Monta a URL da API
    $responseIP = file_get_contents($urlIP); // Faz a solicitação à API e obtém a resposta
    $dataIP = json_decode($responseIP, true); // Decodifica a resposta JSON para um array associativo
    $timezone = $dataIP['timezone']['id']; // Obtém o fuso horário da resposta
    date_default_timezone_set($timezone); // Define o fuso horário padrão

    
    // Configurações do banco de dados
    $dbHost = 'localhost'; // Endereço do banco de dados
    $dbName = 'artuniverse'; // Nome do banco de dados
    $dbUser = 'root'; // Nome de usuário do banco de dados
    $dbPass = ''; // Senha do banco de dados

    // Criar a conexão PDO
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
?>