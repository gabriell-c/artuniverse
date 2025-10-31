<?php
    ob_start();
    require_once '../partials/header.php';  // Mantenha só esta linha
    require_once '../loadingelement.php';

    if(!isset($_SESSION['user'])){
        require_once './notSaved.php';
        exit;
        
    }

    $sql = "SELECT * FROM allsave WHERE user_name = :user_name ";
    $sql = $pdo->prepare($sql);
    $sql->bindValue(':user_name', $_SESSION['user']['user_name']);
    $sql->execute();
    $saved = $sql->fetchAll(PDO::FETCH_ASSOC);

    $sqli = "SELECT * FROM itemsave WHERE user_name = :user_name ";
    $sqli = $pdo->prepare($sqli);
    $sqli->bindValue(':user_name', $_SESSION['user']['user_name']);
    $sqli->execute();
    $savedItem = $sqli->fetchAll(PDO::FETCH_ASSOC);

    if(count($saved) == 0   || count($savedItem) == 0){
        require_once './notSaved.php';
        exit;
    }else if(count($saved) == 1){
        header('Location: '.$base.'/saved/'.$saved[0]['id_name']);
        exit;
    }
?>

<section style="display: flex; width: 100%;" >
    <?php require_once '../sidebar.php' ?>
    <div class="selectSaveFolder">
        <h1>Saved</h1>
    </div>
</section>

<?php require_once '../partials/footer.php' ?>
