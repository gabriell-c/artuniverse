<?php

    require_once './config.php';
    require_once './partials/header.php';
    require_once './loadingElement.php';

	if(isset($_SESSION['warning'])){
		require_once 'modalAlert.php';
		unset($_SESSION['warning']);
	}

    if (isset($_GET['username'])) {
        
        $sql = "SELECT * FROM users WHERE user_name = :user_name";
        $stmt = $pdo->prepare($sql);
        $username = filter_input(INPUT_GET, 'username');
        $stmt->bindValue(':user_name', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            require_once './not_found.php';
            exit;     
        }


        //---------------------//

        $sqlP = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType";
        $stmt = $pdo->prepare($sqlP);
        $stmt->bindValue(':id_user', $user['id']);
        $stmt->bindValue(':archiveType', 'false');
        $stmt->execute();
    
        $postItem = $stmt->fetchAll(PDO::FETCH_ASSOC);


        //--------------------------------------//

        if(isset($_GET['username'])){
            $sqlP = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType";
            $stmt = $pdo->prepare($sqlP);
            $stmt->bindValue(':id_user', $user['id']);
            $stmt->bindValue(':archiveType', 'false');
            $stmt->execute();
        
            $postItemCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

       
    
        if(isset($_GET['view']) && $_GET['view'] === 'videos'){
            $sql = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType AND type = :typeFile";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id_user', $user['id']);
            $stmt->bindValue(':archiveType', 'false');
            $stmt->bindValue(':typeFile', 'video');
            $stmt->execute();
    
            $postItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else if(isset($_GET['view']) && $_GET['view'] === 'images'){
            $sql = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType AND type = :typeFile";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id_user', $user['id']);
            $stmt->bindValue(':archiveType', 'false');
            $stmt->bindValue(':typeFile', 'image');
            $stmt->execute();
    
            $postItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else if(isset($_GET['view']) && $_GET['view'] === 'musics'){
            $sql = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType AND type = :typeFile";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id_user', $user['id']);
            $stmt->bindValue(':archiveType', 'false');
            $stmt->bindValue(':typeFile', 'audio');
            $stmt->execute();
    
            $postItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        
        
    } else {
        header("Location: ".$base);
        exit();
    }

    $texto = $user['link_user'];
    if (strpos($texto, "https://www.") === 0) {
        $beautifulLink = substr($texto, 12);
    } else if (strpos($texto, "http://www.") === 0) {
        $beautifulLink = substr($texto, 11);
    }

    // Pega número de seguidores (quantas pessoas seguem este usuário)
    $sqlFollowers = "SELECT COUNT(*) as total_followers FROM followers WHERE followed_id = :user_id";
    $stmtFollowers = $pdo->prepare($sqlFollowers);
    $stmtFollowers->bindValue(':user_id', $user['id']);
    $stmtFollowers->execute();
    $followersCount = $stmtFollowers->fetch(PDO::FETCH_ASSOC)['total_followers'] ?? 0;

    // Pega número de pessoas que este usuário segue
    $sqlFollowing = "SELECT COUNT(*) as total_following FROM followers WHERE follower_id = :user_id";
    $stmtFollowing = $pdo->prepare($sqlFollowing);
    $stmtFollowing->bindValue(':user_id', $user['id']);
    $stmtFollowing->execute();
    $followingCount = $stmtFollowing->fetch(PDO::FETCH_ASSOC)['total_following'] ?? 0;


    if(isset($_SESSION['user'])){
        $sqlCheckFollow = "SELECT * FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $stmtCheckFollow = $pdo->prepare($sqlCheckFollow);
        $stmtCheckFollow->execute([
            'follower_id' => $_SESSION['user']['id'], 
            'followed_id' => $user['id']
        ]);
        
        $isFollowing = $stmtCheckFollow->fetch() ? true : false;
        
        // Define o texto do botão com base no status de seguimento
        $buttonText = $isFollowing ? "Unfollow" : "Follow";
    }




    // Pega número de seguidores (quantas pessoas seguem este usuário)
    $sqlFollowersList = "SELECT * FROM followers WHERE follower_id = :user_id";
    $stmtFollowersList = $pdo->prepare($sqlFollowersList);
    $stmtFollowersList->bindValue(':user_id', $user['id']);
    $stmtFollowersList->execute();
    $followersList = $stmtFollowersList->fetchAll(PDO::FETCH_ASSOC);

    //-----------------//



    
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const player = Array.from(document.querySelectorAll('.plyr__video')).map((p) => new Plyr(p));
    });



</script>

<style>
    .toast{
        background-color: var(--color1);
    }
    .option-list{
        background-color: var(--color3) !important;
        color: var(--color5);
        font-size: 14px;
    }

    .input-normal{
        background-color: transparent !important;
        color: var(--color5);
        border-radius: 10px;
        height: 40px;
        display: flex;
        align-items: center;
        font-size: 15px;
    }

    .option-list .active{
        background-color: var(--color1) !important;
        font-weight: 500 !important;
    }

    .option-list a{
        padding: 0 10px !important;
    }

    .option-list a:hover{
        color: var(--color5) !important;
    }
    .dropdown-toggle::before{
        border-color: var(--color5);
    }

    .drop-container div{
        display: none;
    }
    .dropdown-toggle{
        border-radius: 0 10px 10px 0;
        height: 100% !important;
    }
    .select-input{
        border-radius: 10px 0 0 10px;
        height: 100% !important;
    }
    .dropdown-toggle, .select-input{
        background: var(--color4);
    }
    .select{
        border: 0; 
        box-shadow: 3px 3px 8px #00000038, -3px -3px 8px #ffffff1e;
    }
    .select.focused {
        box-shadow: 3px 3px 8px #00000038, -3px -3px 8px #ffffff1e;
    }

</style>

<section class="bodyProfile">
<?php require_once './sidebar.php'?>

    <div class="mainProfile">
        <div class="profile-header">
        <form method="POST" action="./actions/uploadCover.php" enctype="multipart/form-data">
            <div class="profile-cover">
                <?php 
                    if (!empty($user['cover_photo'])) {
                        echo '<img src="' . $base . '/upload/profileCover/' . $user['cover_photo'] . '" alt="Imagem de Capa">';
                        echo '<input name="profile-cover-file" type="file" id="cover-picture-upload" class="hidden-input" accept="image/*" onchange="trocarFotoCapa(event)">';
                    } else {
                        echo '<img src="' . $base . '/public/img/saturn_bg.jpg" alt="Foto de Capa">';
                        echo '<input name="profile-cover-file" type="file" id="cover-picture-upload" class="hidden-input" accept="image/*" onchange="trocarFotoCapa(event)">';

                    }
                ?>

            </div>
        </form>
        <form method="POST" action="./actions/uploadPicture.php" enctype="multipart/form-data">
            <div id="profile-picture" class="profile-picture">
                <?php 
                    if ($user['profile_photo'] !== null) {
                        echo '<img src="' . $base . '/upload/profilePhoto/' . $user['profile_photo'] . '" alt="Foto de Perfil">';
                        echo '<input name="profile-picture-file" type="file" id="profile-picture-upload" class="hidden-input" accept="image/*" onchange="trocarFotoPerfil(event)">';
                    } else {
                        echo '<img src="' . $base . '/public/img/saturn_background.jpg" alt="Foto de Perfil">';
                        echo '<input name="profile-picture-file" type="file" id="profile-picture-upload" class="hidden-input" accept="image/*" onchange="trocarFotoPerfil(event)">';

                    }
                ?>

            </div>
        </form>
        </div>


        <div class="profile-body">
            <h1 class="profile-name"><?= $user['full_name'] ?></h1>
            <p class="profile-username">@<?= $user['user_name'] ?></p>
            <?php if(!empty($user['bio_user'])): ?>
                <div class="biography">
                    <pre><?= $user['bio_user']?> </pre>
                </div>
            <?php endif; ?>
            <?php

                if(isset($user['link_user'])){
                    echo '<div class="linkProfile">
                    <span>Link<i class="bi bi-link-45deg"></i></span>
                    <a href="'.$user['link_user'].'" target="_blank" rel="noopener noreferrer">'.$beautifulLink.'</a>
                    </div>';
                }

                if(isset($_SESSION['user']) && $_SESSION['user']['user_name'] == $user['user_name']){

                    echo '<a href="'.$base.'/settings'.'" class="buttonFollw">Edit profile</a>';

                }
                else{
                    echo '<button style="border: none" id="followBtn" class="buttonFollw">'.(isset($buttonText) ? $buttonText : 'Follow').'</button>';
                }
            ?>

            <div class="contBoxs">
                <div class="boxItem"><?= count($postItemCount) ?> <br> post</div>
                <div class="boxItem"><?= $followersCount ?> <br> followers</div>
                <div id="followingBox" data-toggle="modal" data-target="#exampleModalCenter" class="boxItem"><?= $followingCount ?> <br> following</div>
            </div>














            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-center modal-title" id="exampleModalLongTitle">Following</h5>
                    <i aria-hidden="true" data-dismiss="modal" aria-label="Close" class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">

                <?php for($i = 0; $i < count($followersList); $i++): ?> 

                    <?php
                        $sqlList2 = "SELECT * FROM users WHERE id = :id";
                        $stmtList2 = $pdo->prepare($sqlList2);
                        $stmtList2->bindValue(':id', $followersList[$i]['followed_id']);
                        $stmtList2->execute();
                        $userFollowed = $stmtList2->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <ul>
                        <li>
                            <div class="listFollowsProfile">
                                <div class="listFollowsLeft">
                                    <img src="<?=$base?>/upload/profilePhoto/<?= $userFollowed['profile_photo'] ?>" alt="Profile Photo">
                                </div>
                                <div class="listFollowsRight">
                                    <p><?= $userFollowed['full_name'] ?></p>
                                    <span>@<?= $userFollowed['user_name'] ?></span>
                                </div>
                            </div>
                        </li> 
                    </ul>
                <?php endfor ?>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
            </div>
            <!-- ////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\ -->

            
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['user_name'] == $user['user_name']): ?>

                <div class="containerIcons">
                    <p>Upload your art</p>
                    <div class="iconsContainer">
                        <a href="<?=$base?>/create/videos" type="button" class="upload-item">
                            <i class="bi bi-play-btn-fill"></i>
                        </a>
                        <a href="<?=$base?>/create/photo" type="button" class="upload-item">
                            <i class="bi bi-image"></i>
                        </a>
                        <a href="<?=$base?>/create/audio" type="button" class="upload-item">
                            <i class="bi bi-music-note-beamed"></i>
                        </a>
                    </div>
                </div>

            <?php endif?>


                <hr style="opacity: .3;">
            <div class="menuBar">
                <ul>
                    <li class="<?= $URL == $base.'/artuniverse/'.$_GET['username'] ? 'activeMenuBar' : ''; ?>" ><a href="<?=$base.'/'.$_GET['username']?>">All</a></li>
                    <li class="<?= $URL == $base.'/artuniverse/'.$_GET['username'].'/videos' ? 'activeMenuBar' : ''; ?>"><a href="<?=$base.'/'.$_GET['username']?>/videos"><i class="bi bi-play-btn-fill"></i>Videos</a></li>
                    <li class="<?= $URL == $base.'/artuniverse/'.$_GET['username'].'/images' ? 'activeMenuBar' : ''; ?>"><a href="<?=$base.'/'.$_GET['username']?>/images"><i class="bi bi-image"></i>Photos</a></li>
                    <li class="<?= $URL == $base.'/artuniverse/'.$_GET['username'].'/musics' ? 'activeMenuBar' : ''; ?>"><a href="<?=$base.'/'.$_GET['username']?>/musics"><i class="bi bi-music-note-beamed"></i>Musics</a></li>
                </ul>
            </div>


            <div class="postsProfileArea">
                <?php for ($i = 0; $i < count($postItem); $i++): ?>
                    <?= $itemPlaylist = null?>

                    <?php if(isset($_SESSION['user'])): ?>
                        <?php
                            $sqlAP = "SELECT * FROM allplaylist WHERE user_name = :user_name AND type = :type";
                            $stmtAP = $pdo->prepare($sqlAP);
                            $stmtAP->bindValue(':user_name', $_SESSION['user']['user_name']);
                            $stmtAP->bindValue(':type', $postItem[$i]['type'] === 'video' ? 'video' : ($postItem[$i]['type'] === 'audio' ? 'audio' : 'outro_valor'));
                            $stmtAP->execute();
                            $allPlaylist = $stmtAP->fetchAll(PDO::FETCH_ASSOC);

                        ?>


                        <div class="modal fade" id="addPlaylist<?=$postItem[$i]['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 style="color: var(--color5);" class="modal-title">Add playlist</h5>
                                        <button  style="color: var(--color5);" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div style="display: flex; flex-direction: column; padding: 2em;" class="modal-body">

                                    <?php for ($p = 0; $p < count($allPlaylist); $p++) : ?>
                                        <?php
                                            $sqlVP = "SELECT * FROM playlist WHERE id_post = :id_post AND id_name = :id_name ";
                                            $stmtVP = $pdo->prepare($sqlVP);
                                            $stmtVP->bindValue(':id_post', $postItem[$i]['id']);
                                            $stmtVP->bindValue(':id_name', $allPlaylist[$p]['id_name']);
                                            $stmtVP->execute();
                                            $itemPlaylist = $stmtVP->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <label onchange="addPlaylistButton(<?=$postItem[$i]['id']?>, <?=$allPlaylist[$p]['id']?>)" style="transform: scale(1.2); padding: 0 2.2em; display: flex;">
                                            <input <?=empty($itemPlaylist) ? '' : 'checked'?> id="addPlaylist<?=$postItem[$i]['id']?>item<?=$allPlaylist[$p]['id']?>" style="transform: scale(1.5); margin-right: 10px; accent-color: var(--color1);" type="checkbox" class="my-checkbox" />
                                            <span style='white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'><?=$allPlaylist[$p]['playlist_name']?></span>
                                        </label>
                                    <?php endfor ?>

                                        <button onclick="document.getElementById('footerModal<?=$postItem[$i]['id']?>').style.display = 'block';this.style.display = 'none' " class="btn w-100" style="display: flex; align-items: center; justify-content: center; background: var(--color1); color: var(--color5); margin: 2em 0;" ><span class="material-symbols-outlined" style="margin-right: 10px;">playlist_add</span>Criar nova playlist</button>

                                        <div id="footerModal<?=$postItem[$i]['id']?>" style="display: none;">
                                            <hr style="width: 100%; opacity: .2;" >
                                            <div class="js" >
                                                <div class="input-file-container-cover-playlist">  
                                                    <input  class="input-file-cover-playlist" id="my-file<?=$postItem[$i]['id']?>" type="file" name="poster_playlist<?=$postItem[$i]['id']?>" accept="image/*" >
                                                    <label id="input-file-trigger<?=$postItem[$i]['id']?>" tabindex="0" for="my-file" class="input-file-trigger">Select an image for the cover(optional)</label>
                                                </div>
                                                <p id="file-return<?=$postItem[$i]['id']?>" class="file-return"></p>
                                            </div>

                                            <div style="margin: 1em 0;">
                                                <label style="color: var(--color5);margin-right: 100%;" for="titleEdit">Title</label>
                                                <input placeholder="Title" id="titleEdit<?=$postItem[$i]['id']?>" name="title_playlist" class="inputEditPlaylist" required type="text" >
                                            </div>

                                            <div style="margin: 1em 0;">
                                                <select name="privacy_playlist<?=$postItem[$i]['id']?>" id="privacy_playlist<?=$postItem[$i]['id']?>" data-role="select">
                                                    <option value="private" >Private</option>
                                                    <option value="public" >Public</option>
                                                </select>
                                            </div>

                                            <div class="footerButtonAreaEditPlaylist">
                                                <button type="button" class="btn btn-danger ml-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                                                <button type="button" onclick="createPlaylist<?=$postItem[$i]['id']?>()" class="btn btn-success ml-3" >Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>


                <?php
                    if(isset($postItem[$i])){
                        $dataAtual = new DateTime();
                        $dataPassada = DateTime::createFromFormat('Y-m-d H:i:s', $postItem[$i]['creation_date']);
                        // Intervalo entre as duas datas
                        $intervalo = $dataPassada->diff($dataAtual);

                        // Exibindo o intervalo
                        if ($intervalo->y > 0) {
                            $timePost = $intervalo->y . " " . ($intervalo->y == 1 ? "year" : "years") . " ago";
                        } elseif ($intervalo->m > 0) {
                            $timePost = $intervalo->m . " " . ($intervalo->m == 1 ? "month" : "months") . " ago";
                        } elseif ($intervalo->d > 0) {
                            $timePost = $intervalo->d . " " . ($intervalo->d == 1 ? "day" : "days") . " ago";
                        } elseif ($intervalo->h > 0) {
                            $timePost = $intervalo->h . " " . ($intervalo->h == 1 ? "hour" : "hours") . " ago";
                        } elseif ($intervalo->i > 0) {
                            $timePost = $intervalo->i . " " . ($intervalo->i == 1 ? "minute" : "minutes") . " ago";
                        } else {
                            $timePost = "Recently";
                        }
                    }    

                    $sqlU = "SELECT * FROM users WHERE user_name = :user_name";
                    $stmtU = $pdo->prepare($sqlU);
                    $stmtU->bindValue(':user_name', $_GET['username']);
                    $stmtU->execute();
                    $dadosU = $stmtU->fetch(PDO::FETCH_ASSOC);

                    $sql = "SELECT * FROM post_likes WHERE id_post = :postId";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':postId', $postItem[$i]['id']);
                    $stmt->execute();
                    $resultPostItem = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $sqlC = "SELECT * FROM post_comments WHERE id_post = :id_post";
                    $stmtC = $pdo->prepare($sqlC);
                    $stmtC->bindValue(':id_post', $postItem[$i]['id']);
                    $stmtC->execute();
                    $comments = $stmtC->fetchAll(PDO::FETCH_ASSOC);

                    if(isset($_SESSION['user'])){
                        $sqlSP = "SELECT * FROM itemsave WHERE id_post = :id_post AND id_user = :id_user";
                        $stmtSP = $pdo->prepare($sqlSP);
                        $stmtSP->bindValue(':id_post', $postItem[$i]['id']);
                        $stmtSP->bindValue(':id_user', $_SESSION['user']['id']);
                        $stmtSP->execute();
                        $saveItem = $stmtSP->fetch(PDO::FETCH_ASSOC);
                    }

                    $sqlView = "SELECT * FROM video_views WHERE id_video = :id_video";
                    $sqlView = $pdo->prepare($sqlView);
                    $sqlView->bindValue(':id_video', $postItem[$i]['id']);
                    $sqlView->execute();
                    $allViews = $sqlView->fetchAll(PDO::FETCH_ASSOC);

                ?>

                <div class="postBox">
                <div class="postHeaderArea">
                    <div class="headerLeft">
                        <img src="<?= $user['profile_photo'] !== null ? ($base.'/upload/profilePhoto/'.$user['profile_photo']) : $base.'/public/img/saturn_background.jpg'?>">
                        <div class="userNameArea">
                            <h4><?=$postItem[$i]['user_name']?></h4>
                            <span><?=$timePost?></span>
                        </div>
                    </div>
                    <div class="split-button">
                                                

                        <i class="fa-solid fa-ellipsis" style="cursor: pointer;"></i>
                        <ul class="d-menu" data-role="dropdown">
                            <li  onclick="copyLink('<?=$base?>/watch/<?= substr($postItem[$i]['file'], 0 , -4)?>'); runToast('timeout')"><a disabled><i class="fa-solid fa-link"></i>Copy link</a></li>
                            <?php if(isset($_SESSION['user'])) :?>
                                <li onclick="triggerDownload('<?= $base ?>/download.php?f=<?= urlencode($postItem[$i]['file']) ?>')">
                                    <a><i class="fa-solid fa-cloud-arrow-down"></i>Download</a>
                                </li>
                            <?php else: ?>
                                <li onclick="hrefLogin()">
                                    <a><i class="fa-solid fa-cloud-arrow-down"></i>Download</a>
                                </li>
                            <?php endif ?>
                            <li><a href="#"><i class="fa-solid fa-triangle-exclamation"></i>Report</a></li>
                            <li class="divider"></li>
                            <?php if(isset($_SESSION['user']['id']) && $postItem[$i]['id_user'] == $_SESSION['user']['id']) :?>
                                <?php if($postItem[$i]['type'] === 'video'): ?>
                                    <li><a href="<?=$base?>/edit/video/<?=$postItem[$i]['id_name']?>"><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                <?php elseif($postItem[$i]['type'] === 'image'): ?>
                                    <li><a href="<?=$base?>/edit/photo/<?=$postItem[$i]['id_name']?>"><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                <?php elseif($postItem[$i]['type'] === 'audio'): ?>
                                    <li><a href="<?=$base?>/edit/audio/<?=$postItem[$i]['id_name']?>"><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                <?php endif; ?>
                                
                                <li id="archiveItemPost<?=$postItem[$i]['file']?>"><a><i class="fa-solid fa-folder-open"></i>Archive</a></li>
                                <li><a href="#"><i class="fa-solid fa-trash"></i>Delete</a></li>
                            <?php endif ?>
                        </ul>
                        <?php $filePath = $postItem[$i]['file']; ?>
                        <?php $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION); ?>

                        <?php if ($fileExtension == 'mp4'): ?>
                            <a class="openPostProfile" href="<?=$base?>/watch/<?=substr($postItem[$i]['file'], 0, -4)?>"><i class="fa-solid fa-up-right-from-square"></i></a>
                        <?php elseif ($fileExtension == 'jpg') : ?>
                            <a class="openPostProfile" href="<?=$base?>/view/<?=substr($postItem[$i]['file'], 0, -4)?>"><i class="fa-solid fa-up-right-from-square"></i></a>
                        <?php elseif ($fileExtension == 'mp3') : ?>
                            <a class="openPostProfile" href="<?=$base?>/watch/<?=substr($postItem[$i]['file'], 0, -4)?>"><i class="fa-solid fa-up-right-from-square"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $post = $postItem[$i]; ?>
                <?php if ($fileExtension == 'mp4'): ?>
                    <video loop id="videoPlayer<?=$postItem[$i]['id']?>" <?php echo $postItem[$i]['poster'] ? 'poster="/artuniverse/public/storage/posterVideo/'.$postItem[$i]['poster'].'"' : '' ?> class="itemPostBox plyr__video">
                        <source src="/artuniverse/public/storage/videos/<?=$post['file']?>" type="video/mp4">
                    </video>
                <?php elseif ($fileExtension == 'jpg') : ?>
                    <img class="itemPostBox" style="object-fit: contain;" src="/artuniverse/public/storage/photos/<?=$postItem[$i]['file'] ?>">
                <?php elseif ($fileExtension == 'mp3') : ?>
                    <div class="trackProfile">
                        <div id="postArea<?=$postItem[$i]['id']?>" class="posterArea" >
                            <audio style="visibility: hidden;" id="audio<?=$postItem[$i]['id']?>" src="<?=$base?>/public/storage/audios/<?=$postItem[$i]['file']?>"></audio>
                                <img id="playButtonIcon<?=$postItem[$i]['id']?>" class="iconPlayPostProfile" src="<?=$base?>/public/img/play-button.png" style="display: none;" >
                                <img class="posterImageProfile" src="<?= $postItem[$i]['poster'] !== '' ? $base.'/public/storage/posterAudio/'.$postItem[$i]['poster'] : $base.'/public/img/saturn_background.jpg'?>" id="playBtn<?=$postItem[$i]['id']?>" >
                            </div>
                        <div class="waveAudio" id="waveform<?=$postItem[$i]['id']?>"></div>
                    </div>

                    <div class="audioInfo">
                        <div id="iconVolumeProfile<?=$postItem[$i]['id']?>" class="volumeRangeProfile">
                            <i id="volumeIconProfile<?=$postItem[$i]['id']?>" class="bi bi-volume-up-fill"></i>
                            <input class="rangeInputProfile" type="range" id="volume-slider<?=$postItem[$i]['id']?>" min="0" max="1" step="0.01" value="1" />
                        </div>
                        <p id="duration<?=$postItem[$i]['id']?>"></p>
                    </div>
                <?php endif; ?>

                <div class="infoPostAerea">
                    <div class="infoPostItem">
                        <?php
                            if(isset($_SESSION['user'])){
                                if ($resultPostItem){
                                    if(isset($resultPostItem[0]['id_post']) == $postItem[$i]['id']){
                                        echo '<i id="likeButton'.$postItem[$i]['id'].'" data-post-id="'.$postItem[$i]['id'].'" data-user-id="'.$_SESSION['user']['id'].'" onclick="likePostAction('.$postItem[$i]['id'].', '.$_SESSION['user']['id'].')" class="fa-solid fa-heart likeButton liked"></i>';
                                    }
                                } else {
                                    echo '<i id="likeButton'.$postItem[$i]['id'].'" data-post-id="'.$postItem[$i]['id'].'" data-user-id="'.$_SESSION['user']['id'].'" onclick="likePostAction('.$postItem[$i]['id'].', '.$_SESSION['user']['id'].')" class="fa-regular fa-heart likeButton"></i>';
                                }
                            }else{
                                echo '<i id="likeButton'.$postItem[$i]['id'].'" onclick="hrefLogin()" class="fa-regular fa-heart likeButton"></i>';
                            }
                        ?>

                        <span id="spanLikeProfile<?=$postItem[$i]['id']?>"><?=count($resultPostItem)?></span>
                    </div>
                    <div class="infoPostItem">
                        <i class="fa-regular fa-message"></i>
                        <span><?=count($comments)?></span>
                    </div>
                    <div class="infoPostItem">
                        <?php if($postItem[$i]['type'] === 'audio'):?>
                            <i class="fa-solid fa-play"></i>
                            <?= count($allViews) ?>
                        <?php elseif($postItem[$i]['type'] === 'video'): ?>
                            <i class="fa-solid fa-eye" style="color: var(--color5); filter: none;"></i>
                            <?= count($allViews) ?>
                        <?php endif ?>
                    </div>

                    <div class="contContentInt">
                        
                    </div>

                    <?php if ($fileExtension == 'jpg'):?>

                        <?php if(isset($_SESSION['user'])) :?>
                            <i id="saveButton<?=$postItem[$i]['id']?>" onclick="savePost<?=$postItem[$i]['id']?>(<?=$postItem[$i]['id']?>, <?=$_SESSION['user']['id']?>, '<?=$_SESSION['user']['user_name']?>');" style="margin-left: auto; font-size: 23px;" class="<?=isset($saveItem['id_post']) && $saveItem['id_post'] == $postItem[$i]['id'] ? 'fa-solid' : 'fa-regular' ?> fa-bookmark"></i> 
                        <?php else: ?>
                            <i id="saveButton<?=$postItem[$i]['id']?>" onclick="hrefLogin()" style="margin-left: auto; font-size: 23px;" class="fa-regular fa-bookmark"></i> 
                        <?php endif?>

                    <?php else: ?>
                        <?php if(isset($_SESSION['user'])) :?>
                            <i  data-toggle="modal" data-target="#addPlaylist<?=$postItem[$i]['id']?>" style="cursor: pointer;margin-left: auto; font-size: 30px; color: <?= $itemPlaylist ? 'var(--color1)': 'var(--color5)'?>" id="addPlaylistBtn" class="material-symbols-outlined playlist_button"><?= $itemPlaylist ? 'playlist_add_check': 'playlist_add'?></i>
                        <?php else: ?>
                            <i onclick="hrefLogin()" style="cursor: pointer;margin-left: auto; font-size: 30px" id="addPlaylistBtn" class="material-symbols-outlined">playlist_add</i>
                        <?php endif ?>
                    <?php endif ?>
                </div>
                <div class="textPostArea">
                    <h3><?=$postItem[$i]['title']?></h3>
                    <pre id="desctTextProfile<?=$postItem[$i]['id']?>"><?=$postItem[$i]['description']?></pre>
                    <button id="btnMoreDesc<?=$postItem[$i]['id']?>">more<i class="fa-solid fa-chevron-down"></i></button>
                </div>
                </div>

                <?php endfor; ?>

                <script>

                

                    


                    <?php for ($i = 0; $i < count($postItem); $i++): ?>

                        

                        <?php if(isset($_SESSION['user'])) : ?>
                            document.querySelector( "#input-file-trigger<?=$postItem[$i]['id']?>" ).addEventListener( "keydown", function( event ) {  
                                if ( event.keyCode == 13 || event.keyCode == 32 ) {  
                                    document.querySelector( "#my-file<?=$postItem[$i]['id']?>" ).focus();  
                                }  
                            });
                            document.querySelector( "#input-file-trigger<?=$postItem[$i]['id']?>" ).addEventListener( "click", function( event ) {
                                document.querySelector( "#my-file<?=$postItem[$i]['id']?>" ).focus();
                                return false;
                            });  
                            document.querySelector( "#my-file<?=$postItem[$i]['id']?>" ).addEventListener("change", function(event) {
                                // Acesse o arquivo selecionado usando a propriedade `files`
                                if (event.target.files[0]) {
                                    // Faça algo com o arquivo selecionado, como exibir o nome
                                    document.getElementById("file-return<?=$postItem[$i]['id']?>").innerText = event.target.files[0].name;
                                } else {
                                    // Lide com o caso em que nenhum arquivo é selecionado
                                    document.getElementById("file-return<?=$postItem[$i]['id']?>").innerText = "Nenhum arquivo selecionado";
                                }
                            });
                            

                            //---------------------------------//

                            function runToastCreatePlaylist(mode) {
                                var toast = Metro.toast.create;
                                switch (mode) {
                                    case 'timeout': toast("Playlist created successfully", null, 1000); break;
                                }
                            }

                            //---------------------------------------//

                            function addplaylistToast(mode, playlist) {
                                var toast = Metro.toast.create;
                                switch (mode) {
                                    case 'timeout': toast("added to playlist", null, 2000); break;
                                }
                            }

                            //---------------------------------------//

                            function removeplaylistToast(mode, playlist) {
                                var toast = Metro.toast.create;
                                switch (mode) {
                                    case 'timeout': toast("removed to playlist", null, 2000); break;
                                }
                            }

                                //-----------------------------------------------//

                                function savePost<?=$postItem[$i]['id'] ?>(postId, userId, user_name){

                                    if(document.getElementById("saveButton"+postId).classList.contains("fa-solid")){
                                        fetch('/artuniverse/actions/unSavedPost.php', { 
                                        method: 'POST',
                                        body: new URLSearchParams({ postId, userId, user_name}),
                                        })
                                        .then((response) => response.text())
                                        .then((result) => {
                                            console.log(result);
                                            document.getElementById("saveButton"+postId).classList.add("fa-regular")
                                            document.getElementById("saveButton"+postId).classList.remove("fa-solid")
                                        })
                                    }else{
                                        fetch('/artuniverse/actions/savedPost.php', { 
                                        method: 'POST',
                                        body: new URLSearchParams({ postId, userId, user_name}),
                                        })
                                        .then((response) => response.text())
                                        .then((result) => {
                                            console.log(result);
                                            document.getElementById("saveButton"+postId).classList.remove("fa-regular")
                                            document.getElementById("saveButton"+postId).classList.add("fa-solid")
                                        })
                                    }


                                }

                            //-----------------------------------------------------//

                            <?php for($p = 0; $p < count($allPlaylist); $p++): ?>

                                function addPlaylistButton(postId, playlistId) {
                                    var checkbox = document.getElementById("addPlaylist" + postId + "item" + playlistId);
                                    var userId = <?=$_SESSION['user']['id']?>;
                                    if (checkbox.checked) {

                                        fetch('/artuniverse/actions/addPlaylist.php', { 
                                            method: 'POST',
                                            body: new URLSearchParams({ postId, userId, playlistId }),
                                        })
                                        .then((response) => response.text())
                                        .then((result) => {
                                            console.log(result);
                                            addplaylistToast('timeout')
                                        })
                                    } else {

                                        fetch('/artuniverse/actions/removePlaylist.php', {
                                            method: 'POST',
                                            body: new URLSearchParams({ postId, userId, playlistId }),
                                        })
                                        .then((response) => response.text())
                                        .then((result) => {
                                            console.log(result);
                                            removeplaylistToast('timeout')
                                        })
                                    }
                                }
                            <?php endfor ?>

                            <?php if($postItem[$i]['type'] == 'video'): ?>

                                document.addEventListener("DOMContentLoaded", function () {
                                    const video<?=$postItem[$i]['id']?> = document.getElementById("videoPlayer<?=$postItem[$i]['id']?>");
                                    let viewed<?=$postItem[$i]['id']?> = false; // Para garantir que a view só seja registrada uma vez

                                    video<?=$postItem[$i]['id']?>.addEventListener("timeupdate", function () {
                                        if (!viewed<?=$postItem[$i]['id']?> && video<?=$postItem[$i]['id']?>.currentTime >= 5) { // Só conta após 10s assistidos
                                            fetch("/artuniverse/actions/register_view.php?contentId=<?=$postItem[$i]['id']?>", { 
                                                method: "POST"
                                            });
                                            viewed<?=$postItem[$i]['id']?> = true; // Impede que a view seja registrada várias vezes
                                        }
                                    });
                                });


                                <?php elseif($postItem[$i]['type'] == 'audio'): ?>
                                document.addEventListener("DOMContentLoaded", function () {
                                    const audio<?=$postItem[$i]['id']?> = document.getElementById("audio<?=$postItem[$i]['id']?>"); // Agora o ID corresponde ao seu código
                                    let viewed<?=$postItem[$i]['id']?> = false; // Garante que a view só seja registrada uma vez

                                    if (audio<?=$postItem[$i]['id']?>) {
                                        let viewed<?=$postItem[$i]['id']?> = false; // Garante que a view só seja registrada uma vez

                                        wavesurfer<?=$postItem[$i]['id']?>.on("timeupdate", function () {  
                                            if (!viewed<?=$postItem[$i]['id']?> && wavesurfer<?=$postItem[$i]['id']?>.getCurrentTime() >= 5) {
                                                // Conta a view após 5s de reprodução
                                                fetch("/artuniverse/actions/register_view.php?contentId=<?=$postItem[$i]['id']?>", { 
                                                    method: "POST"
                                                });
                                                viewed<?=$postItem[$i]['id']?> = true; // Impede que a view seja registrada novamente
                                            }
                                        });

                                    }
                                });
                            <?php endif ?>

                            //-----------------------------------------------------//


                            function createPlaylist<?=$postItem[$i]['id']?>() {
                                var postId = <?=$postItem[$i]['id']?>;

                                // Obtém o título da playlist e adiciona ao formData
                                var title = document.getElementById("titleEdit<?=$postItem[$i]['id']?>").value;

                                // Obtém a privacidade selecionada e adiciona ao formData
                                var privacy = document.getElementById("privacy_playlist<?=$postItem[$i]['id']?>").value;

                                // Obtém o arquivo de imagem da capa e adiciona ao formData
                                var coverImage = document.getElementById("my-file<?=$postItem[$i]['id']?>").files[0];

                                var data = new FormData();
                                data.append('postId', postId);
                                data.append('title_playlist', title);
                                data.append('privacy_playlist', privacy);
                                data.append('poster_playlist', coverImage);

                                fetch('<?=$base?>/actions/createPlaylist.php', {
                                    method: 'POST',
                                    body: data
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data)
                                    console.log('sucesso')
                                    $('#addPlaylist<?=$postItem[$i]['id']?>').on('hidden.bs.modal', function () {
                                        // Remover o backdrop manualmente
                                        $('.modal-backdrop').remove();
                                    });

                                    $('#addPlaylist<?=$postItem[$i]['id']?>').modal('hide');
                                    runToastCreatePlaylist('timeout');
                                    console.log(JSON.stringify(data)); // Use JSON.stringify() para visualizar o objeto JSON                                // Aqui você pode manipular a resposta do servidor
                                })
                                .catch(error => {
                                    console.log('erro poha')
                                    console.log(error);
                                });
                            }

                            //-----------------------------//
                        <?php endif ?>

                    function copyLink(link) {
                        var tempInput = document.createElement('input');
                        tempInput.value = link;
                        document.body.appendChild(tempInput);
                        tempInput.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempInput);
                    }

                    <?php if(isset($_SESSION['user']) && $user['id'] == $_SESSION['user']['id'])  :?>

                        document.getElementById('archiveItemPost<?=$postItem[$i]['file']?>').addEventListener('click', function() {
                            window.location.href = "/artuniverse/actions/archivePost.php?file=<?=$postItem[$i]['file']?>";
                        });

                    <?php endif ?>

                        if ((document.getElementById("desctTextProfile<?=$postItem[$i]['id']?>").textContent).length > 100) {
                            document.getElementById("desctTextProfile<?=$postItem[$i]['id']?>").classList.add("overflowHiddenTextProfile");

                            document.getElementById('btnMoreDesc<?=$postItem[$i]['id']?>').addEventListener('click', function() {
                                document.getElementById("desctTextProfile<?=$postItem[$i]['id']?>").classList.remove("overflowHiddenTextProfile");
                                document.getElementById('btnMoreDesc<?=$postItem[$i]['id']?>').style.display = 'none';
                            });
                        } else {
                            document.getElementById('btnMoreDesc<?=$postItem[$i]['id']?>').style.display = 'none';
                        }

                        
                    <?php if(isset($_SESSION)) :?>

                        function likePostAction(postId, userId) {
                            fetch('/artuniverse/actions/update_like_post.php', {
                                method: 'POST',
                                body: new URLSearchParams({ postId, userId }),
                            })
                                .then((response) => response.text())
                                .then((result) => {
                                    console.log(result);
                                    // Verifica se a ação foi realizada com sucesso
                                    if (result.includes('curtido com sucesso')) {
                                        // Atualize a interface para exibir o botão "Descurtir"
                                        document.getElementById('likeButton' + postId).classList.add('liked');
                                        document.getElementById('likeButton' + postId).classList.add('fa-solid');
                                        document.getElementById('likeButton' + postId).classList.remove('fa-regular');
                                        document.getElementById('spanLikeProfile' + postId).textContent = parseInt(document.getElementById('spanLikeProfile' + postId).textContent) + 1;
                                    }
                                    if (result.includes('descurtido com sucesso')){
                                        // Atualize a interface para exibir o botão "Curtir"
                                        document.getElementById('likeButton' + postId).classList.remove('liked');
                                        document.getElementById('likeButton' + postId).classList.remove('fa-solid');
                                        document.getElementById('likeButton' + postId).classList.add('fa-regular');
                                        document.getElementById('spanLikeProfile' + postId).textContent = parseInt(document.getElementById('spanLikeProfile' + postId).textContent) - 2;

                                    } 

                                })
                                .catch((error) => {
                                    console.error('Erro ao enviar solicitação AJAX:', error);
                                });
                        }

                        function unlikePost(postId, userId) {
                            fetch('/artuniverse/actions/update_like_post.php', {
                                method: 'POST',
                                body: new URLSearchParams({ postId, userId })
                            })
                            .then(response => response.text())
                            .then(result => {
                                console.log(result);
                                // Atualize a interface do usuário conforme necessário
                                document.getElementById('likeButton' + postId).classList.remove('liked');
                                document.getElementById('likeButton' + postId).textContent = 'Like';
                            })
                            .catch(error => {
                                console.error('Erro ao enviar solicitação AJAX:', error);
                            });
                        }    


                        //---------------------------------//
                    <?php endif ?>

                        <?php if ($postItem[$i]['type'] === 'audio'): ?>
                            const wavesurfer<?=$postItem[$i]['id']?> = WaveSurfer.create({
                                container: '#waveform<?=$postItem[$i]['id']?>',
                                waveColor: '#555',
                                progressColor: '#9673ff',
                                barWidth: 1,
                                resposive: true,
                                height: 140,
                                barRadius: 4
                            });

                            wavesurfer<?=$postItem[$i]['id']?>.on('ready', function() {
                                document.getElementById("duration<?=$postItem[$i]['id']?>").innerText = Math.floor(wavesurfer<?=$postItem[$i]['id']?>.getDuration() / 60) + ':' + (Math.floor(wavesurfer<?=$postItem[$i]['id']?>.getDuration() % 60) < 10 ? '0' : '') + Math.floor(wavesurfer<?=$postItem[$i]['id']?>.getDuration() % 60);
                            });

                            wavesurfer<?=$postItem[$i]['id']?>.load('<?=$base?>/public/storage/audios/<?=$postItem[$i]['file']?>');

                            document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').onclick = function(){
                                wavesurfer<?=$postItem[$i]['id']?>.playPause();
                                if(document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').src.includes("<?=$base?>/public/img/play-button.png")){
                                    document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').src = "<?=$base?>/public/img/pause.png";
                                } else {
                                    document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').src = "<?=$base?>/public/img/play-button.png";
                                }
                            };

                            wavesurfer<?=$postItem[$i]['id']?>.on('pause', function () {
                                console.log("A mídia foi pausada!");
                                document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').src = "<?=$base?>/public/img/play-button.png";
                            });

                            wavesurfer<?=$postItem[$i]['id']?>.on('play', function () {
                                console.log("A mídia foi pausada!");
                                document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').src = "<?=$base?>/public/img/pause.png";
                            });

                            wavesurfer<?=$postItem[$i]['id']?>.on('finish', function () {
                                document.getElementById('playButtonIcon<?=$postItem[$i]['id']?>').src = "<?=$base?>/public/img/play-button.png";
                            });

                            document.getElementById("postArea<?=$postItem[$i]['id']?>").addEventListener('mouseenter', function() {
                                document.getElementById("playButtonIcon<?=$postItem[$i]['id']?>").style.display = 'block'
                            });
                            document.getElementById("postArea<?=$postItem[$i]['id']?>").addEventListener('mouseleave', function() {
                                document.getElementById("playButtonIcon<?=$postItem[$i]['id']?>").style.display = 'none'
                            });

                            document.getElementById('volume-slider<?=$postItem[$i]['id']?>').addEventListener('input', function() {
                                wavesurfer<?=$postItem[$i]['id']?>.setVolume(document.getElementById('volume-slider<?=$postItem[$i]['id']?>').value);
                            });


                            document.getElementById("iconVolumeProfile<?=$postItem[$i]['id']?>").addEventListener('mouseenter', function() {
                                document.getElementById("volume-slider<?=$postItem[$i]['id']?>").style.display = 'block'
                            });
                            document.getElementById("iconVolumeProfile<?=$postItem[$i]['id']?>").addEventListener('mouseleave', function() {
                                document.getElementById("volume-slider<?=$postItem[$i]['id']?>").style.display = 'none'
                            });

                            document.getElementById("volumeIconProfile<?=$postItem[$i]['id']?>").onclick = function() {
                                if (wavesurfer<?=$postItem[$i]['id']?>.getMuted()) {
                                    wavesurfer<?=$postItem[$i]['id']?>.setMuted(false);
                                    document.getElementById("volumeIconProfile<?=$postItem[$i]['id']?>").classList.add("bi-volume-up-fill")
                                    document.getElementById("volumeIconProfile<?=$postItem[$i]['id']?>").classList.remove("bi-volume-mute-fill")
                                } else {
                                    wavesurfer<?=$postItem[$i]['id']?>.setMuted(true);
                                    document.getElementById("volumeIconProfile<?=$postItem[$i]['id']?>").classList.remove("bi-volume-up-fill")
                                    document.getElementById("volumeIconProfile<?=$postItem[$i]['id']?>").classList.add("bi-volume-mute-fill")
                                }
                            };



                            
                        <?php endif; ?>
                        
                    <?php endfor; ?>

                    document.title = "<?=explode(' ',$user['full_name'])[0]?> (@<?=$user['user_name']?>) | Artuniverse";

                    function runToast(mode) {
                        var toast = Metro.toast.create;
                        switch (mode) {
                            case 'timeout': toast("copied link", null, 1000); break;
                        }
                    }

                    <?php if(isset($_SESSION['user'])) : ?>

                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementsByClassName('likeButton').addEventListener('click', function(event) {
                                likePostAction(event.target.getAttribute('data-post-id'), event.target.getAttribute('data-user-id'));
                            });
                        });

                    <?php endif ?>
                    

                    hrefLogin=()=>{
                        window.location.href = "<?=$base?>/login"
                    }

                    document.addEventListener('DOMContentLoaded', () => {

                        const profilePictureDiv = document.getElementById('profile-picture');
                        const profilePictureInput = document.getElementById('profile-picture-upload');
                        profilePictureDiv.onclick = () => {
                            profilePictureInput.click();
                        };
                        profilePictureInput.addEventListener('change', function() {
                            const form = this.closest('form');
                            form.submit();
                        });

                


                        const profileCovereDiv = document.querySelector('.profile-cover');
                        const profileCoverInput = document.getElementById('cover-picture-upload');
                        profileCovereDiv.addEventListener('click', () => {
                            profileCoverInput.click();
                        });
                        profileCoverInput.addEventListener('change', function() {
                            const form = this.closest('form');
                            form.submit();
                        });    
                    })    


                    <?php if(isset($_SESSION['user'])): ?>

                        <?php if($_SESSION['user']['id'] !== $user['id']): ?>

                            document.addEventListener("DOMContentLoaded", function () {
                                const followBtn = document.getElementById("followBtn");

                                if (followBtn) {
                                    followBtn.addEventListener("click", function () {
                                        if (followBtn.textContent === "Follow") {
                                            followBtn.textContent = "Unfollow";
                                        } else {
                                            followBtn.textContent = "Follow";
                                        }
                                    });
                                }
                            });


                            document.getElementById("followBtn").addEventListener("click", function() {
                                let followedId = <?= $user['id'] ?>; // ID do usuário do perfil exibido
                                let followerId = <?= $_SESSION['user']['id'] ?>; // ID do usuário logado

                                followUser(followerId, followedId);
                            });

                            function followUser(followerId, followedId) {
                                fetch('/artuniverse/actions/follow.php', {
                                    method: 'POST',
                                    body: JSON.stringify({ follower_id: followerId, followed_id: followedId }),
                                    headers: { 'Content-Type': 'application/json' }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert("Agora você está seguindo este usuário!");
                                    } else {
                                        alert("Erro ao seguir.");
                                    }
                                });
                            }
                            <?php endif ?>
                    <?php else: ?>

                        document.getElementById("followBtn").addEventListener("click", function() {
                            window.location.href = "/artuniverse/login"; // Redireciona para a página de login
                        });
                    <?php endif ?>


                    function triggerDownload(url) {
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', '');
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    }



                    document.getElementById("followingBox").addEventListener("click", function() {

                    });
                

            
                </script>
                
            </div>

        </div>
        
    </div>

</section>

<?php

    require_once './partials/footer.php';

?>