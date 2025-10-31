<?php

    require_once '../../config.php';
    if(!isset($_SESSION['user'])){
        header('Location: ').$base;
        exit;
    }
    require_once '../../partials/header.php';

    $sqlP = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType";
    $stmt = $pdo->prepare($sqlP);
    $stmt->bindValue(':id_user', $_SESSION['user']['id']);
    $stmt->bindValue(':archiveType', 'true');
    $stmt->execute();
    $postItem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //-------------------//

    $sqlV = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType AND type = :type";
    $stmtV = $pdo->prepare($sqlV);
    $stmtV->bindValue(':id_user', $_SESSION['user']['id']);
    $stmtV->bindValue(':archiveType', 'true');
    $stmtV->bindValue(':type', 'video');
    $stmtV->execute();
    $postItemVideo = $stmtV->fetchAll(PDO::FETCH_ASSOC);

    //-----------------------------------//

    $sqlI = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType AND type = :type";
    $stmtI = $pdo->prepare($sqlI);
    $stmtI->bindValue(':id_user', $_SESSION['user']['id']);
    $stmtI->bindValue(':archiveType', 'true');
    $stmtI->bindValue(':type', 'image');
    $stmtI->execute();
    $postItemImage = $stmtI->fetchAll(PDO::FETCH_ASSOC);

    //---------------------------//


    $sqlS = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType AND type = :type";
    $stmtS = $pdo->prepare($sqlS);
    $stmtS->bindValue(':id_user', $_SESSION['user']['id']);
    $stmtS->bindValue(':archiveType', 'true');
    $stmtS->bindValue(':type', 'audio');
    $stmtS->execute();
    $postItemSong = $stmtS->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .plyr{
        max-height: 80vh !important;
    }
</style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../../sidebar.php' ?>
    <div class="archivedPostsMain">
        <h1>Archived</h1>
        <div class="buttonsSelectYpeArchiveArea">
            <?php if($URL != '/artuniverse/settings/archive/') :?>
                <button id="All">All <i class="fa-solid fa-grid-2"></i></button>
            <?php endif ?>
            <?php if($URL != '/artuniverse/settings/archive/videos') : ?>
                <button id="Videos">Videos <i class="fa-solid fa-play"></i></button>
            <?php endif ?>
            <?php if($URL != '/artuniverse/settings/archive/photos') : ?>
                <button id="Photos">Photos <i class="fa-solid fa-image"></i></button>
            <?php endif ?>
            <?php if($URL != '/artuniverse/settings/archive/song') : ?>
                <button id="Song">Song <i class="fa-solid fa-music"></i></button>
            <?php endif ?>
        </div>
        <div class="gridItemsArchived" style="<?=count($postItem) === 0 ? 'display: flex; align-items: center; justify-content: center;' : ''?>">
            <div class="emptyArchived">
                <?php if(count($postItem) === 0):?>
                   <img src="../../public/img/folder.svg" alt="empty folder">   
                   <p>You have not archived any posts yet.</p>  
                <?php endif?>
            </div>

            <?php if($URL == '/artuniverse/settings/archive/') :?>
                <?php for($i = 0; $i < count($postItem); $i++) :?>
                    <?php if($postItem[$i]['type'] === 'video') :?>
                        <div data-toggle="modal" data-target="#modalArchivedContent<?=$i?>" class="itemArchived" style="background-image: url(<?=$postItem[$i]['poster'] == '' ? $base.'/public/img/saturn_background.jpg' :  $base.'/public/storage/posterVideo/'.$postItemVideo[$i]['poster']?>);" ></div>
                    <?php elseif($postItem[$i]['type'] === 'audio') : ?>
                        <div data-toggle="modal" data-target="#modalArchivedContent<?=$i?>" class="itemArchived" style="background-image: url(<?=$postItem[$i]['poster'] == '' ? $base.'/public/img/saturn_background.jpg' :  $base.'/public/storage/posterAudio/'.$postItemSong[$i]['poster']?>);" ></div>
                    <?php elseif($postItem[$i]['type'] === 'image') : ?>
                        <div data-toggle="modal" data-target="#modalArchivedContent<?=$i?>" class="itemArchived" style="background-image: url(<?=$base.'/public/storage/photos/'.$postItem[$i]['file']?>);" ></div>
                    <?php endif ?>
                    <div class="modal fade" id="modalArchivedContent<?=$i?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 700px; overflow: hidden; height: 100%; max-height: 95vh; margin: 1rem auto;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 style="color: var(--color5);" class="modal-title"><?=$postItem[$i]['title']?></h5>
                                <button  style="color: var(--color5);" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div style="display: flex; flex-direction: column;" class="modal-body">
                            <?php if($postItem[$i]['type'] === 'video') :?>
                                <video loop <?= $postItem[$i]['poster'] ? 'poster="/artuniverse/public/storage/posterVideo/' . $postItem[$i]['poster'] . '"' : '' ?> class="plyr__video">
                                    <source src="/artuniverse/public/storage/videos/<?= $postItem[$i]['file'] ?>">
                                </video>
                            <?php elseif($postItem[$i]['type'] === 'audio') : ?>
                            <?php elseif($postItem[$i]['type'] === 'image') : ?>
                                <img style="object-fit: contain; max-height: 80vh;" src="/artuniverse/public/storage/photos/<?=$postItem[$i]['file'] ?>">
                            <?php endif ?>
                            </div>
                        </div>
                        </div>
                    </div>
                    
                <?php endfor ?>
            <?php elseif($URL == '/artuniverse/settings/archive/videos') :?>
                <?php for($i = 0; $i < count($postItemVideo); $i++) :?>
                    <div class="itemArchived" style="background-image: url(<?=$postItem[$i]['poster'] === '' ? $base.'/public/img/saturn_background.jpg' :  $base.'/public/storage/posterVideo/'.$postItemVideo[$i]['poster']?>);" ></div>
                <?php endfor ?>
            <?php elseif($URL == '/artuniverse/settings/archive/photos') :?>
                <?php for($i = 0; $i < count($postItemImage); $i++) :?>
                    <div class="itemArchived" style="background-image: url(<?=$base.'/public/storage/photos/'.$postItemImage[$i]['file']?>);" ></div>
                <?php endfor ?>
            <?php elseif($URL == '/artuniverse/settings/archive/song') :?>
                <?php for($i = 0; $i < count($postItemSong); $i++) :?>
                    <?php if (count(array_filter($postItem, function($item) { return $item['type'] === 'audio'; })) === 0) : ?>
                        <h6>No archived music :/</h6>
                    <?php else :?>
                        <div class="itemArchived" style="background-image: url(<?=$postItem[$i]['poster'] === '' ? $base.'/public/img/saturn_background.jpg' :  $base.'/public/storage/posterAudio/'.$postItemSong[$i]['poster']?>);" ></div>
                    <?php endif ?>
                <?php endfor ?>
            <?php endif ?>
        </div>


    </div>
</section>

<script>

    const plyr = new Plyr('.plyr__video');

    <?php for($i = 0; $i < count($postItem); $i++) :?>
        <?php if($postItem[$i]['type'] === 'video') :?>
            $('#modalArchivedContent<?=$i?>').on('shown.bs.modal', function () {
                plyr.play()
            })

            $('#modalArchivedContent<?=$i?>').on('hidden.bs.modal', function () {
                plyr.stop()
            })
        <?php endif ?>
    <?php endfor ?>

    <?php if($URL != '/artuniverse/settings/archive/') :?>
        document.getElementById("All").onclick = () =>{
            window.location.href = "<?=$base?>/settings/archive"
        }
        document.title = 'Archived | Artuniverse'
    <?php endif ?>
    <?php if($URL != '/artuniverse/settings/archive/videos') : ?>
        document.getElementById("Videos").onclick = () =>{
            window.location.href = "<?=$base?>/settings/archive/videos"
        }
        document.title = 'Archived videos | Artuniverse'
    <?php endif ?>
    <?php if($URL != '/artuniverse/settings/archive/photos') : ?>
        document.getElementById("Photos").onclick = () =>{
            window.location.href = "<?=$base?>/settings/archive/photos"
        }
        document.title = 'Archived photos | Artuniverse'
    <?php endif ?>
    <?php if($URL != '/artuniverse/settings/archive/song') : ?>
        document.getElementById("Song").onclick = () =>{
            window.location.href = "<?=$base?>/settings/archive/song"
        }
        document.title = 'Archived song | Artuniverse'
    <?php endif ?>


</script>

<?php require_once '../../partials/footer.php' ?>