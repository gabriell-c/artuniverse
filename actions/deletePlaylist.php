<?php
require_once '../config.php';
// Verifica se o ID da playlist foi fornecido na URL
if (isset($_GET['id_name'])) {
    $playlistId = $_GET['id_name'];

    $sql = "SELECT * FROM allplaylist WHERE id_name = :id_name";
    $stmtS = $pdo->prepare($sql);
    $stmtS->bindValue(':id_name', $_GET['id_name']);
    $stmtS->execute();
    $playlsitItem = $stmtS->fetch(PDO::FETCH_ASSOC);

    unlink('C:/xampp/htdocs/artuniverse/public/storage/posterPlaylist/'.$playlsitItem['poster']);

    // Prepare e execute a consulta para excluir a playlist com o ID fornecido
    $stmt = $pdo->prepare("DELETE FROM allplaylist WHERE id_name = :playlistId");
    $stmt->bindValue(':playlistId', $playlsitItem['id_name']);


    if ($stmt->execute()) {

        $stmtP = $pdo->prepare("DELETE FROM playlist WHERE id_name = :playlistId");
        $stmtP->bindValue(':playlistId', $playlsitItem['id_name']);

        if($stmtP->execute()){
            // A playlist foi excluída com sucesso
            // Aqui você pode redirecionar o usuário para a página ou lista de playlists após a exclusão
            header("Location: ".$base.'/playlist');
            exit;
        }
    } else {
        // Houve um erro ao excluir a playlist
        // Aqui você pode redirecionar o usuário para uma página de erro ou lidar com o erro de alguma outra forma
        $_SESSION['warning'] = 'There was an error deleting the playlist, please try again later';
        header("Location: ".$base.'/playlist');
        exit;
    }
} else {
    // Se o ID da playlist não foi fornecido, redirecione o usuário para uma página de erro ou página inicial
    $_SESSION['warning'] = 'There was an error deleting the playlist, please try again later';
    header("Location: ".$base.'/playlist');
    exit;
}
?>
