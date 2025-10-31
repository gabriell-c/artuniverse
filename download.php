<?php

    require_once __DIR__ . '/config.php';
    $basePath = __DIR__ . '/public/storage/';
    $filename = isset($_GET['f']) ? basename($_GET['f']) : null;

    if (!$filename) {
        http_response_code(400);
        exit('Arquivo não especificado.');
    }

    // Descobre a extensão
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Define o caminho com base na extensão
    $folderMap = [
        'mp4' => 'videos/',
        'mp3' => 'audios/',
        'jpg' => 'photos/',
        'jpeg' => 'photos/',
        'png' => 'photos/',
        // adicione outros tipos se precisar
    ];

    if (!isset($folderMap[$ext])) {
        http_response_code(400);
        exit('Tipo de arquivo não suportado.');
    }

    $fullPath = $basePath . $folderMap[$ext] . $filename;

    // Verifica existência
    if (!file_exists($fullPath)) {
        http_response_code(404);
        exit('Arquivo não encontrado.');
    }

    // Força o download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fullPath));
    ob_clean();
    flush();
    readfile($fullPath);
    exit;
?>