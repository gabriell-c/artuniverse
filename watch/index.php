<?php

    require_once '../config.php';
    require_once '../partials/header.php';
    require_once '../loadingelement.php';

    $sqlP = "SELECT * FROM allposts WHERE archive = :archiveType AND id_name = :id_content";
    $stmt = $pdo->prepare($sqlP);
    $stmt->bindValue(':archiveType', 'false');
    $stmt->bindValue(':id_content', $_GET['id_content']);
    $stmt->execute();

    $content = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$content) {
        require_once './not_found.php';
        exit;     
    }

    //-----------------//

    $sql = "SELECT * FROM users WHERE id = :id_user";
    $stmtU = $pdo->prepare($sql);
    $stmtU->bindValue(':id_user', $content['id_user']);
    $stmtU->execute();

    $userI = $stmtU->fetch(PDO::FETCH_ASSOC);

    //-----------------//


    $sqlAll = "SELECT * FROM users";
    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute();
    
    $Alluser = $stmtAll->fetchAll(PDO::FETCH_ASSOC);


    //-----------------//


    $sqlView = "SELECT * FROM video_views WHERE id_video = :id_video";
    $sqlView = $pdo->prepare($sqlView);
    $sqlView->bindValue(':id_video', $content['id']);
    $sqlView->execute();
    
    $allViews = $sqlView->fetchAll(PDO::FETCH_ASSOC);


    //-----------------//



    if(isset($_SESSION['user'])){
        $sqlAP = "SELECT * FROM allplaylist WHERE user_name = :user_name AND type = :type";
        $stmtAP = $pdo->prepare($sqlAP);
        $stmtAP->bindValue(':user_name', $_SESSION['user']['user_name']);
        $stmtAP->bindValue(':type', $content['type'] === 'video' ? 'video' : ($content['type'] === 'audio' ? 'audio' : ''));
        $stmtAP->execute();
        $allPlaylist = $stmtAP->fetchAll(PDO::FETCH_ASSOC);

        //////////////////////// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

        $sqlPlay = "SELECT * FROM playlist WHERE id_post = :id_post AND id_user = :id_user";
        $stmtUPlay = $pdo->prepare($sqlPlay);
        $stmtUPlay->bindValue(':id_post', $content['id']);
        $stmtUPlay->bindValue(':id_user', $_SESSION['user']['id']);
        $stmtUPlay->execute();

        $playlistItem = $stmtUPlay->fetchAll(PDO::FETCH_ASSOC);
    }

    //-------------//
    
    if(isset($userI)){
        $sqlP = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType";
        $stmt = $pdo->prepare($sqlP);
        $stmt->bindValue(':id_user', $userI['id']);
        $stmt->bindValue(':archiveType', 'false');
        $stmt->execute();
    
        $postItemCount = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $sqlC = "SELECT * FROM post_comments WHERE id_post = :post_id";
    $stmtC = $pdo->prepare($sqlC);
    $stmtC->bindValue(':post_id', $content['id'], PDO::PARAM_INT);
    $stmtC->execute();
    $comments = $stmtC->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM post_likes WHERE id_post = :postId AND id_user = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':postId', $content['id'], PDO::PARAM_INT);
    $stmt->bindValue(':userId', $userI['id']);
    $stmt->execute();
    $resultPostItem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sqlFollowers = "SELECT COUNT(*) as total_followers FROM followers WHERE followed_id = :user_id";
    $stmtFollowers = $pdo->prepare($sqlFollowers);
    $stmtFollowers->bindValue(':user_id', $userI['id']);
    $stmtFollowers->execute();
    $followersCount = $stmtFollowers->fetch(PDO::FETCH_ASSOC)['total_followers'] ?? 0;

    if(isset($_SESSION['user'])){

        $sqlCheckFollow = "SELECT * FROM followers WHERE follower_id = :follower_id AND followed_id = :followed_id";
        $stmtCheckFollow = $pdo->prepare($sqlCheckFollow);
        $stmtCheckFollow->execute([
            'follower_id' => $_SESSION['user']['id'], 
            'followed_id' => $userI['id']
        ]);
        
        $isFollowing = $stmtCheckFollow->fetch() ? true : false;
        $buttonText = $isFollowing ? "Unfollow" : "Follow";
    }else{
                $buttonText = "Follow";

    }



?>

<style>
    .plyr__video{
        max-height: 500px !important;
        aspect-ratio: 16/9;
        object-fit: contain;
    }

    .drop-container div{
        display: none;
    }

    .toast{
        background-color: var(--color1);
    }

    .commentItemArea i {
        display: none;
    }

    .commentItemArea:hover i {
        display: inline-block;
    }

    .commentItemArea i:hover  {
        color: #ff0033;
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
        height: 30px;
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

    .option-list li:hover{

    }

    .dropdown-toggle::before{
        border-color: var(--color5);
    }
</style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../sidebar.php'?>
    <div class="bodyContent">
        <?php if ($content['type'] == 'video'): ?>

            <video id="videoPlayer" 
                loop 
                <?= $content['poster'] ? 'poster="/artuniverse/public/storage/posterVideo/' . $content['poster'] . '"' : '' ?> 
                class="videobody plyr__video"
                controls>
                <source src="/artuniverse/public/storage/videos/<?= $content['file'] ?>">
            </video>

        <?php elseif ($content['type'] == 'audio'):?>

            <div class="trackProfile" style=" width: 100%; margin: 0 0 .5em 0; display: block; ">
                <div id="postArea" class="posterArea" style="width: 400px; height: 400px; margin: 0 auto 1em auto; z-index: 1; box-shadow: 0 0 20px var(--color2);" >
                    <audio style="visibility: hidden;" id="audio" src="<?=$base?>/public/storage/audios/<?=$content['file']?>"></audio>
                    <img style="width: 20%;" id="playButtonIcon" class="iconPlayPostProfile" src="<?=$base?>/public/img/play-button.png" style="display: none;" >
                    <img class="posterImageProfile" src="<?= $content['poster'] !== '' ? $base.'/public/storage/posterAudio/'.$content['poster'] : $base.'/public/img/saturn_background.jpg'?>" id="playBtn" >
                </div>
                <img class="posterBackground" src="<?= $content['poster'] !== '' ? $base.'/public/storage/posterAudio/'.$content['poster'] : $base.'/public/img/saturn_background.jpg'?>"  style="opacity: .3; width: 100%; position: absolute; top: -1em;" alt="">

                <div class="waveAudio" id="waveform" style="margin: 3em 0 .5em 0;"></div>
            </div>
            <div class="audioInfo" style="width: 100%; color: var(--color1);">
                <div id="iconVolumeProfile" class="volumeRangeProfile">
                    <i id="volumeIconProfile" class="bi bi-volume-up-fill"></i>
                    <input class="rangeInputProfile" type="range" id="volume-slider" min="0" max="1" step="0.01" value="1" />
                </div>
                <p id="duration"></p>
            </div>

        <?php endif; ?>
        <h1 class="titleContent"><?=$content['title']?></h1>
        <div class="areaInterContent">
            <div class="areaInterContentLeft">
                <?php
                    if(isset($_SESSION['user'])){
                        if ($resultPostItem){
                            if($resultPostItem[0]['id_post'] == $content['id']){
                                echo '<i id="likeButton'.$content['id'].'" data-post-id="'.$content['id'].'" class="fa-solid fa-heart likeButton liked" onclick="likePostAction('.$content['id'].', '.$_SESSION['user']['id'].')"></i>';
                            }else{
                                echo '<i id="likeButton'.$content['id'].'" data-post-id="'.$content['id'].'" data-user-id="'.$_SESSION['user']['id'].'" onclick="likePostAction('.$content['id'].', '.$_SESSION['user']['id'].')" class="fa-regular fa-heart likeButton"></i>';
                            }
                        }else{
                            echo '<i id="likeButton'.$content['id'].'" data-post-id="'.$content['id'].'" data-user-id="'.$_SESSION['user']['id'].'" onclick="likePostAction('.$content['id'].', '.$_SESSION['user']['id'].')" class="fa-regular fa-heart likeButton"></i>';
                        }
                    }else{
                        echo '<i id="likeButton'.$content['id'].'" onclick="hrefLogin()" class="fa-regular fa-heart likeButton"></i>';
                    }
                ?>
                <i style="cursor: pointer;" class="fa-solid fa-share" data-video="share" data-toggle="modal" data-target="#sahreModal"></i>
                <i style="cursor: pointer;" class="fa-solid fa-link" onclick="copyLink('<?=$base?>/watch/<?=$_GET['id_content']?>'); runToast('timeout')"></i>
                <?php if(isset($_SESSION['user'])) :?>
                    <i style="cursor: pointer;" id="addPlaylistBtn" data-toggle="modal" data-target="#addPlaylist" class="material-symbols-outlined"><?= $playlistItem ? 'playlist_add_check': 'playlist_add'?></i>
                    <i class="fa-solid fa-pen-to-square" id="editContent" ></i>
                <?php else: ?>
                    <i style="cursor: pointer;" id="addPlaylistBtn" onclick="hrefLogin()" class="material-symbols-outlined ">playlist_add</i>
                <?php endif ?>
            </div>
            <div class="areaInterContentRigth">
                <div class="contContentInt">
                    <?php if($content['type'] === 'audio'):?>
                        <i class="fa-solid fa-play" style="color: var(--color1 );"></i>
                        <?= count($allViews) ?>
                    <?php else: ?>
                        <i class="fa-solid fa-eye" style="color: var(--color1 ); filter: none;"></i>
                        <?= count($allViews) ?>
                    <?php endif ?>
                </div>
                <div class="contContentInt">
                    <i class="fa-solid fa-heart" style="color:#ff0033;"></i>
                    <span id="spanLikeProfile<?=$content['id']?>"><?=count($resultPostItem)?></span>
                </div>
            </div>
        </div>
        
                    <!-- /////////////////////////////// -->


        <?php if(isset($_SESSION['user'])): ?>


            <div class="modal fade" id="addPlaylist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    $stmtVP->bindValue(':id_post', $content['id']);
                                    $stmtVP->bindValue(':id_name', $allPlaylist[$p]['id_name']);
                                    $stmtVP->execute();
                                    $itemPlaylist = $stmtVP->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <label onchange="addPlaylistButton(<?=$content['id']?>, <?=$allPlaylist[$p]['id']?>)" style="transform: scale(1.2); padding: 0 2.2em; display: flex;">
                                    <input <?=empty($itemPlaylist) ? '' : 'checked'?> id="addPlaylistitem" style="transform: scale(1.5); margin-right: 10px; accent-color: var(--color1);" type="checkbox" class="my-checkbox" />
                                    <span style='color:var(--color5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'><?=$allPlaylist[$p]['playlist_name']?></span>
                                </label>
                            <?php endfor ?>

                            <?php if(isset($_SESSION['user'])): ?>


                                <button onclick="document.getElementById('footerModal').style.display = 'block';this.style.display = 'none' " class="btn w-100" style="display: flex; align-items: center; justify-content: center; background: var(--color1); color: var(--color5); margin: 2em 0;" ><span class="material-symbols-outlined" style="margin-right: 10px;">playlist_add</span>Criar nova playlist</button>

                                <div id="footerModal" style="display: none;">
                                    <hr style="width: 100%; opacity: .2;" >
                                    <div class="js" >
                                        <div class="input-file-container-cover-playlist">  
                                            <input  class="input-file-cover-playlist" id="my-file" type="file" name="poster_playlist" accept="image/*" >
                                            <label id="input-file-trigger" tabindex="0" for="my-file" class="input-file-trigger">Select an image for the cover(optional)</label>
                                        </div>
                                        <p id="file-return" class="file-return"></p>
                                    </div>

                                    <div style="margin: 1em 0;">
                                        <label style="color: var(--color5);margin-right: 100%;" for="titleEdit">Title</label>
                                        <input placeholder="Title" id="titleEdit" name="title_playlist" class="inputEditPlaylist" required type="text" >
                                    </div>

                                    <div style="margin: 1em 0;">
                                        <select name="privacy_playlist" id="privacy_playlist" data-role="select">
                                            <option value="private" >Private</option>
                                            <option value="public" >Public</option>
                                        </select>
                                    </div>

                                    <div class="footerButtonAreaEditPlaylist">
                                        <button type="button" class="btn btn-danger ml-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                                        <button type="button" onclick="createPlaylist()" class="btn btn-success ml-3" >Save</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <!-- ////////////////////////////////////////////// -->

        <div class="modal fade" id="sahreModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color: var(--color5);" class="modal-title">Share</h5>
                    <button  style="color: var(--color5);" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div style="display: flex; flex-direction: column;" class="modal-body">
                    <div class="contentAreaShare">
                        Share this link via
                        <div class="iconsAreaShare" >
                            <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fyoutu.be%2FOVzpznzDXJM&amp;src=sdkpreparse" target="_blank" ><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?=$base?>/<?= $content['type'] === 'video' || 'audio' ? 'watch' : 'views'?>/<?=$_GET['id_content']?>" target="_blank" ><i class="fa-brands fa-twitter"></i></a>
                            <a href="https://api.whatsapp.com/send/?text=<?=$base?>/<?= $content['type'] === 'video' || 'audio' ? 'watch' : 'views'?>/<?=$_GET['id_content']?>" target="_blank" ><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="https://telegram.me/share/url?url=<?=$base?>/<?= $content['type'] === 'video' || 'audio' ? 'watch' : 'views'?>/<?=$_GET['id_content']?>" target="_blank" ><i class="fab fa-telegram-plane"></i></a>
                            <a href="http://pinterest.com/pin-builder/?description=<?=$content['description']?>&is_video=true&media=<?= $content['type'] === 'video' ? $content['poster'] : $content['file']?>&method=button&url=<?=$base?>/<?= $content['type'] === 'video' || 'audio' ? 'watch' : 'views'?>/<?=$_GET['id_content']?>" target="_blank" ><i class="fa-brands fa-pinterest-p"></i></a>
                            <a href="https://www.reddit.com/submit?title=<?=$content['title']?>&text=<?=$content['description']?>&url=<?=$base?>/<?= $content['type'] === 'video' || 'audio' ? 'watch' : 'views'?>/<?=$_GET['id_content']?>" target="_blank" ><i class="fa-brands fa-reddit-alien"></i></a>
                        </div>
                        Or copy link
                        <div class="areaCopyLink">
                            <i class="fa-solid fa-link"></i>
                            <span id="textLinkCopy">
                                <?php
                                    if (strpos($base.'/watch/'.$_GET['id_content'], "https://") === 0) {
                                        echo substr($base.'/watch/'.$_GET['id_content'], 8);
                                    } else if (strpos($base.'/watch/'.$_GET['id_content'], "http://") === 0) {
                                        echo substr($base.'/watch/'.$_GET['id_content'], 7);
                                    }
                                ?>
                                <!-- <?=$base.'/watch/'.$_GET['id_content']?> -->
                            </span>
                            <button id="copyBtn" onclick="copyLink2('<?=$base?>/watch/<?=$_GET['id_content']?>'); runToast('timeout')">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="descArea">
            <div class="descAreaLeft">
                <img id="profileAvatar" src="<?= $userI['profile_photo'] !== null ? ($base.'/upload/profilePhoto/'.$userI['profile_photo']) : $base.'/public/img/saturn_background.jpg'?>">
                <h5><?=$userI['user_name']?></h5>
                <div><i class="fa-solid fa-user-group"></i> <?= $followersCount ?></div>
                <div><i class="fa-solid fa-file"></i><?=count($postItemCount)?></div>
                <?php
                    if(isset($_SESSION['user']) && $_SESSION['user']['user_name'] == $userI['user_name']){
    
                    }
                    else{
                        echo '<button style="border: none" id="followBtn" class="buttonFollw2">'.(isset($buttonText) ? $buttonText : 'Follow').'</button>';
                    }
                ?>
            </div>
            <div class="descAreaRight">
                <pre id="descContent" class="textDescContent"><?=$content['description']?></pre>
                <button id="buttonShowDesc" class="showDescButton" >show more<i id="iconShowDesc" class="fa-solid fa-angle-down"></i></button>
                <hr style="opacity: .1; width: 100%;">
                <div class="sendCommentArea" style="width: 100%;">
                    <form id="commentForm" action="actions/addComment.php" method="POST">
                        <i onclick="addComment()" class="bi bi-send-fill"></i>
                        <textarea placeholder="add a comment..." id="comment" type="text" class="inputAddComment"></textarea>
                    </form>
                    
                </div>
                <div class="headerComentsArea">
                    <div class="headerComentsAreaLeft">
                        <i class="fa-solid fa-message"></i>
                        <?=count($comments)?> comments
                    </div>
                    <div class="headerComentsAreaRight">

                        <select data-role="select">
                            <option value="1" >Last</option>
                            <option value="2" >Older</option>
                        </select>
                    </div>
                </div>
                    <hr style="opacity: .1; width: 100%;">

                <?php if($content): ?>

                    <!-- Container de comentários -->
                    <div id="commentsContainer"></div>


                <?php endif ?>
            </div>
        </div>


    </div>
</section>

<script>

    document.getElementById('profileAvatar').onclick=()=>{
        window.location.href = "<?=$base?>/<?=$userI['user_name']?>"
    }
    

    var allUsers = <?= json_encode($Alluser) ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const plyrPlayer = new Plyr('.plyr__video');

        // var video = document.querySelector('video');

        // if(video){{
        //     video.muted = true;
        //     video.play();
        // }}
        
        
        loadComments()
    });

    const preElement = document.getElementById("descContent");
    const lineHeight = parseFloat(getComputedStyle(preElement).lineHeight);
    const height = preElement.clientHeight;
    const numberOfLines = Math.ceil(height / lineHeight);

    if (numberOfLines >= 7) {
        preElement.classList.add("overflowHiddenTextContent")

        document.getElementById('buttonShowDesc').addEventListener('click', function() {
            if(preElement.classList.contains("overflowHiddenTextContent")){
                preElement.classList.remove("overflowHiddenTextContent")
                document.getElementById('buttonShowDesc').innerHTML = "show less<i id='iconShowDesc' class='fa-solid fa-angle-up'></i>"
                document.getElementById("iconShowDesc").classList.add("fa-angle-up")
                document.getElementById("iconShowDesc").classList.remove("fa-angle-down")
            }else{
                preElement.classList.add("overflowHiddenTextContent")
                document.getElementById('buttonShowDesc').innerHTML = "show more<i id='iconShowDesc' class='fa-solid fa-angle-down'></i>"
                document.getElementById("iconShowDesc").classList.remove("fa-angle-up")
                document.getElementById("iconShowDesc").classList.add("fa-angle-down")
            }
        });
    } else {
        document.getElementById('buttonShowDesc').style.display = 'none';
    }



    function loadComments() {
        var commentsContainer = document.getElementById("commentsContainer");
        var newCommentsContainer = document.createElement("div"); // Elemento temporário para novos comentários

        $.ajax({
            url: '/artuniverse/actions/getComments.php',
            type: 'POST',
            data: { postId: <?= $content['id'] ?> },
            dataType: 'json',
            success: function (data) {
                var commentsContainer = document.getElementById("commentsContainer");
                commentsContainer.innerHTML = ""; // Limpa comentários anteriores

                data.forEach(comment => {
                    // Buscar a foto do usuário pelo id_user no objeto allUsers
                    var user = allUsers.find(u => u.id == comment.id_user);
                    var profilePhoto = user && user.profile_photo ? `/artuniverse/upload/profilePhoto/${user.profile_photo}` : '/artuniverse/public/img/saturn_background.jpg';

                    var newCommentArea = document.createElement("div");
                    newCommentArea.classList.add("commentItemArea");

                    var tempoPostagem = calculateTimeAgo(comment.comment_date);

                    newCommentArea.innerHTML = `
                        <img src="${profilePhoto}" alt="profile photo">
                        <div id="areaOver${comment.id}" class="BodyCommmentArea">
                            <div class="headerCommment">
                                <p>@${comment.user_name}</p>
                                <div style="display: flex;">
                                    <?= isset($_SESSION["user"]) ? '<i style="margin-right: 10px; cursor: pointer;" class="fa-solid fa-trash" onclick="deleteComment(${comment.id})"></i>' : '' ?>
                                    <p>${tempoPostagem}</p>
                                </div>
                            </div>
                            <pre class="textComment">${comment.comment}</pre>
                        </div>
                    `;

                    commentsContainer.appendChild(newCommentArea);
                });
            },
            error: function () {
                console.log("Erro ao carregar os comentários.");
            }
        });


        <?php
            // Obtém a data atual no formato UTC
            $dataAtualUTC = date('Y-m-d\TH:i:s');
            ?>

        function calculateTimeAgo(commentDate) {
            var agoraT = '<?=$dataAtualUTC?>';
            var dataPassada = new Date(commentDate);
            var agora = new Date(agoraT);
            var intervalo = agora - dataPassada;
            var segundos = Math.floor(intervalo / 1000);
            var minutos = Math.floor(segundos / 60);
            var horas = Math.floor(minutos / 60);
            var dias = Math.floor(horas / 24);
            var meses = Math.floor(dias / 30);
            var anos = Math.floor(meses / 12);

            if (anos > 0) {
                return anos + " " + (anos == 1 ? "year ago" : "years ago");
            } else if (meses > 0) {
                return meses + " " + (meses == 1 ? "month ago" : "months ago");
            } else if (dias > 0) {
                return dias + " " + (dias == 1 ? "day ago" : "days ago");
            } else if (horas > 0) {
                return horas + " " + (horas == 1 ? "hour ago" : "hours ago");
            } else if (minutos > 0) {
                return minutos + " " + (minutos == 1 ? "minute ago" : "minutes ago");
            } else {
                return "recently";
            }
        }
    }

    //=========================================================//

    // Função para enviar o novo comentário com AJAX
    function addComment() {
        var comment = document.getElementById('comment').value;
        var postId = <?= $content['id'] ?>; // Obtém o ID do post atual

        fetch('/artuniverse/actions/addComment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'content=' + encodeURIComponent(comment) + '&post_id=' + encodeURIComponent(postId) // Adiciona o ID do post no corpo do fetch
        })
        .then(response => response.text())
        .then(data => {
            // Limpar o campo de comentário após o envio bem-sucedido
            document.getElementById('comment').value = '';

            // Recarrega a lista de comentários após o novo comentário ser adicionado
            loadComments();
        })
        .catch(error => {
            console.log('Erro ao enviar o comentário.');
        });
    }

    document.getElementById("comment").addEventListener("keydown", function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault(); // Impede a quebra de linha
            addComment(); // Chama a função para enviar o comentário
        }
    });


    function deleteComment(commentId) {
        // Remove o comentário da lista local no cliente (no navegador)
        var commentArea = document.getElementById("areaOver" + commentId);
        if (commentArea) {
            commentArea.remove();
        }

        // Envia a solicitação para excluir o comentário no servidor usando AJAX
        fetch('/artuniverse/actions/deleteComment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'comment_id=' + encodeURIComponent(commentId)
        })
        .then(response => response.text())
        .then(data => {
            loadComments();
        })
        .catch(error => {
            console.log('Erro ao excluir o comentário: ' + error);
        });
    }


    function copyLink(link) {
        var tempInput = document.createElement('input');
        tempInput.value = link;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
    }

    function copyLink2(link) {
        navigator.clipboard.writeText(link)
    }


    //-----------------//

    function runToast(mode) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("copied link", null, 1000); break;
        }
    }

    function runToastAddPlaylist(mode) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("saved to playlist", null, 1000); break;
        }
    }

    function runToastRemovePlaylist(mode) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("removed to playlist", null, 1000); break;
        }
    }

    //--------------------//


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

    function colorIcon(){
        if(document.getElementById("addPlaylistBtn").textContent === "playlist_add_check"){
            document.getElementById("addPlaylistBtn").style.color = "var(--color1)"
        }else{
            document.getElementById("addPlaylistBtn").style.color = "var(--color5)"
        }
    }

    colorIcon()


    //---------------------------//

    <?php if ($content['type'] == 'audio'): ?>
        const wavesurfer = WaveSurfer.create({
            container: '#waveform',
            waveColor: '#555',
            progressColor: '#9673ff',
            barWidth: 1,
            resposive: true,
            height: 140,
            barRadius: 4,
            autostart: true
        });

        wavesurfer.load('../public/storage/audios/<?=$content['file']?>');

        wavesurfer.on('ready', function() {
            document.getElementById("duration").innerText = Math.floor(wavesurfer.getDuration() / 60) + ':' + (Math.floor(wavesurfer.getDuration() % 60) < 10 ? '0' : '') + Math.floor(wavesurfer.getDuration() % 60);
        });

        document.getElementById('playButtonIcon').onclick = function(){
            wavesurfer.playPause();
            if(document.getElementById('playButtonIcon').src.includes("<?=$base?>/public/img/play-button.png")){
                document.getElementById('playButtonIcon').src = "<?=$base?>/public/img/pause.png";
            } else {
                document.getElementById('playButtonIcon').src = "<?=$base?>/public/img/play-button.png";
            }
        };

        wavesurfer.on('finish', function () {
            document.getElementById('playButtonIcon').src = "<?=$base?>/public/img/play-button.png";
        });

        document.getElementById("postArea").addEventListener('mouseenter', function() {
            document.getElementById("playButtonIcon").style.display = 'block'
        });
        document.getElementById("postArea").addEventListener('mouseleave', function() {
            document.getElementById("playButtonIcon").style.display = 'none'
        });

        document.getElementById('volume-slider').addEventListener('input', function() {
            wavesurfer.setVolume(document.getElementById('volume-slider').value);
        });


        document.getElementById("iconVolumeProfile").addEventListener('mouseenter', function() {
            document.getElementById("volume-slider").style.display = 'block'
        });
        document.getElementById("iconVolumeProfile").addEventListener('mouseleave', function() {
            document.getElementById("volume-slider").style.display = 'none'
        });

        document.getElementById("volumeIconProfile").onclick = function() {
            if (wavesurfer.getMuted()) {
                wavesurfer.setMuted(false);
                document.getElementById("volumeIconProfile").classList.add("bi-volume-up-fill")
                document.getElementById("volumeIconProfile").classList.remove("bi-volume-mute-fill")
            } else {
                wavesurfer.setMuted(true);
                document.getElementById("volumeIconProfile").classList.remove("bi-volume-up-fill")
                document.getElementById("volumeIconProfile").classList.add("bi-volume-mute-fill")
            }
        };


        document.addEventListener("DOMContentLoaded", function () {
            const audio = document.getElementById("audio"); // Agora o ID corresponde ao seu código
            let viewed = false; // Garante que a view só seja registrada uma vez

            if (audio) {
                let viewed = false; // Garante que a view só seja registrada uma vez

                wavesurfer.on("timeupdate", function () {  
                    if (!viewed && wavesurfer.getCurrentTime() >= 5) {
                        // Conta a view após 5s de reprodução
                        fetch("/artuniverse/actions/register_view.php?contentId=<?= $content['id'] ?>", { 
                            method: "POST"
                        });
                        viewed = true; // Impede que a view seja registrada novamente
                    }
                });

            }

            <?php if(isset($_SESSION['user'])): ?>
                 
                document.getElementById("editContent").addEventListener("click", function() {
                    window.location.href = "<?=$base?>/edit/audio/<?=$content['id_name']?>";
                });
            <?php endif ?>
        });

    <?php endif; ?>
    
    
    <?php if(isset($_SESSION['user'])): ?>

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


        <?php if(isset($_SESSION['user']) && $_SESSION['user']['id'] !== $userI['id']): ?>
            document.getElementById("followBtn").addEventListener("click", function() {
                followUser(<?= $_SESSION['user']['id'] ?>, <?= $userI['id'] ?>);
            });
        <?php endif ?>

        function followUser(followerId, followedId) {
            fetch('/artuniverse/actions/follow.php', {
                method: 'POST',
                body: JSON.stringify({ follower_id: followerId, followed_id: followedId }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Agora você está seguindo este usuário!");
                } else {
                    console.log("Erro ao seguir.");
                }
            });
        }
        <?php else: ?>

        document.getElementById("followBtn").addEventListener("click", function() {
            window.location.href = "/artuniverse/login"; // Redireciona para a página de login
        });
    <?php endif ?>

    <?php if(isset($_SESSION['user'])) :?>
        function addPlaylistButton(postId, playlistId) {
            var checkbox = document.getElementById("addPlaylistitem");
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
    <?php endif ?>


    function createPlaylist() {
        var postId = <?=$content['id']?>;

        // Obtém o título da playlist e adiciona ao formData
        var title = document.getElementById("titleEdit").value;

        // Obtém a privacidade selecionada e adiciona ao formData
        var privacy = document.getElementById("privacy_playlist").value;

        // Obtém o arquivo de imagem da capa e adiciona ao formData
        var coverImage = document.getElementById("my-file").files[0];

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
            $('#addPlaylist').on('hidden.bs.modal', function () {
                // Remover o backdrop manualmente
                $('.modal-backdrop').remove();
            });

            $('#addPlaylist').modal('hide');
            runToastCreatePlaylist('timeout');
            console.log(JSON.stringify(data)); // Use JSON.stringify() para visualizar o objeto JSON                                // Aqui você pode manipular a resposta do servidor
        })
        .catch(error => {
            console.log(error);
        });
    }

    

    function runToastCreatePlaylist(mode) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("Playlist created successfully", null, 3000); break;
        }
    }

    //---------------------------------------//

    function addplaylistToast(mode, playlist) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("added to playlist", null, 3000); break;
        }
    }

    //---------------------------------------//

    function removeplaylistToast(mode, playlist) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("removed to playlist", null, 3000); break;
        }
    }

     <?php if(isset($_SESSION['user'])): ?>
        document.querySelector( "#my-file" ).addEventListener("change", function(event) {
            // Acesse o arquivo selecionado usando a propriedade `files`
            if (event.target.files[0]) {
                // Faça algo com o arquivo selecionado, como exibir o nome
                document.getElementById("file-return").innerText = event.target.files[0].name;
            } else {
                // Lide com o caso em que nenhum arquivo é selecionado
                document.getElementById("file-return").innerText = "Nenhum arquivo selecionado";
            }
        });
    <?php endif; ?>

    <?php if($content['type'] == 'video'): ?>

        document.addEventListener("DOMContentLoaded", function () {
            const video = document.getElementById("videoPlayer");
            let viewed = false; // Para garantir que a view só seja registrada uma vez

            video.addEventListener("timeupdate", function () {
                if (!viewed && video.currentTime >= 5) { // Só conta após 10s assistidos
                    fetch("/artuniverse/actions/register_view.php?contentId=<?= $content['id'] ?>", { 
                        method: "POST"
                    });
                    viewed = true; // Impede que a view seja registrada várias vezes
                }
            });

            <?php if(isset($_SESSION['user'])): ?>
                 
                document.getElementById("editContent").addEventListener("click", function() {
                    window.location.href = "<?=$base?>/edit/video/<?=$content['id_name']?>";
                });
            <?php endif ?>
        });


    <?php endif ?>

    hrefLogin=()=>{
        window.location.href = "<?=$base?>/login"
    }

</script>

<?php

    require_once '../partials/footer.php'

?>