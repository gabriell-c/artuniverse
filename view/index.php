<?php

    require_once '../config.php';
    require_once '../partials/header.php';
    require_once '../loadingelement.php';


    if (!isset($_GET['id_content'])) {
        require_once '../not_found.php';
        exit;     
    }

    $sqlP = "SELECT * FROM allposts WHERE archive = :archiveType AND id_name = :id_name";
    $stmt = $pdo->prepare($sqlP);
    $stmt->bindValue(':archiveType', 'false');
    $stmt->bindValue(':id_name', $_GET['id_content']);
    $stmt->execute();

    $content = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$content) {
        require_once '../not_found.php';
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
    
    // Define o texto do botão com base no status de seguimento



    if(isset($_SESSION['user'])){
        $sqlSP = "SELECT * FROM itemsave WHERE id_post = :id_post AND id_user = :id_user";
        $stmtSP = $pdo->prepare($sqlSP);
        $stmtSP->bindValue(':id_post', $content['id']);
        $stmtSP->bindValue(':id_user', $_SESSION['user']['id']);
        $stmtSP->execute();
        $saveItem = $stmtSP->fetch(PDO::FETCH_ASSOC);
    }
?>

<style>

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


    .dropdown-toggle::before{
        border-color: var(--color5);
    }
</style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../sidebar.php'?>
    <div class="bodyContent">

        <div class="headerView">
            <img class="imgContent" src="/artuniverse/public/storage/photos/<?=$content['file'] ?>">
            <img class="imgContentBack" src="/artuniverse/public/storage/photos/<?=$content['file'] ?>">

            <h1 class="titleContent"><?=$content['title']?></h1>
        </div>

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
                <i style="cursor: pointer;" class="fa-solid fa-share"  data-video="share" data-toggle="modal" data-target="#sahreModal"></i>
                <i style="cursor: pointer;" class="fa-solid fa-link" onclick="copyLink('<?=$base?>/view/<?=$_GET['id_content']?>'); runToast('timeout')"></i>
                <?php if(isset($_SESSION['user'])) :?>
                    <i id="saveButton" onclick="savePost(<?=$content['id']?>, <?=$_SESSION['user']['id']?>, '<?=$_SESSION['user']['user_name']?>');" class="<?=isset($saveItem['id_post']) && $saveItem['id_post'] == $content['id'] ? 'fa-solid' : 'fa-regular' ?> fa-bookmark"></i> 
                    <i style="cursor: pointer;" onclick="triggerDownload('<?= $base ?>/download.php?f=<?= urlencode($content['file']) ?>')" class="fa-solid fa-download"></i>
                <?php else: ?>
                    <i id="saveButton" onclick="hrefLogin()" class="fa-regular fa-bookmark"></i> 
                    <i style="cursor: pointer;" onclick="hrefLogin()" class="fa-solid fa-download"></i>
                <?php endif?>

            </div>
            <div class="areaInterContentRigth">
                <div class="contContentInt">
                    <i class="fa-solid fa-heart" style="color:#ff0033;"></i>
                    <span id="spanLikeProfile<?=$content['id']?>"><?=count($resultPostItem)?></span>
                </div>
            </div>
        </div> 

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
                    <?php if(isset($_SESSION['user'])) :?>
                        <form id="commentForm" action="actions/addComment.php" method="POST">
                            <i onclick="addComment()" class="bi bi-send-fill"></i>
                            <textarea placeholder="add a comment..." id="comment" type="text"  class="inputAddComment"></textarea>
                        </form>
                    <?php else: ?>
                        <div id="commentForm" >
                            <i onclick="hrefLogin()" class="bi bi-send-fill"></i>
                            <textarea placeholder="add a comment..." id="comment" type="text"  class="inputAddComment"></textarea>
                        </div>
                    <?php endif; ?>
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

    var allUsers = <?= json_encode($Alluser) ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const plyrPlayer = new Plyr('.plyr__video');
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
            console.log("Resposta do servidor:", data); // Ver resposta

            // Limpar o campo de comentário após o envio bem-sucedido
            document.getElementById('comment').value = '';

            // Recarrega a lista de comentários após o novo comentário ser adicionado
            loadComments();

        })
        .catch(error => {
            console.log('Erro ao enviar o comentário.');
        })
    }

    <?php if(isset($_SESSION['user'])) :?>

        document.getElementById("comment").addEventListener("keydown", function(event) {
            if (event.key === "Enter" && !event.shiftKey) {
                event.preventDefault(); // Impede a quebra de linha
                addComment(); // Chama a função para enviar o comentário
            }
        });
    <?php endif; ?>


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


    //---------------------------//

    <?php if ($content['type'] == 'audio'): ?>
        const wavesurfer = WaveSurfer.create({
            container: '#waveform',
            waveColor: '#555',
            progressColor: '#9673ff',
            barWidth: 1,
            resposive: true,
            height: 140,
            barRadius: 4
        });

        wavesurfer.load('../public/storage/audios/64b47dc3e5e3c.mp3');

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
        
    <?php endif; ?>
    
    <?php if(isset($_SESSION['user'])): ?>




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
            

    <?php else: ?>

        document.getElementById("followBtn").addEventListener("click", function() {
            window.location.href = "/artuniverse/login"; // Redireciona para a página de login
        });
    <?php endif ?>

    function savePost(postId, userId, user_name){

        if(document.getElementById("saveButton").classList.contains("fa-solid")){
            fetch('/artuniverse/actions/unSavedPost.php', { 
            method: 'POST',
            body: new URLSearchParams({ postId, userId, user_name}),
            })
            .then((response) => response.text())
            .then((result) => {
                console.log(result);
                document.getElementById("saveButton").classList.add("fa-regular")
                document.getElementById("saveButton").classList.remove("fa-solid")
            })
        }else{
            fetch('/artuniverse/actions/savedPost.php', { 
            method: 'POST',
            body: new URLSearchParams({ postId, userId, user_name}),
            })
            .then((response) => response.text())
            .then((result) => {
                console.log(result);
                document.getElementById("saveButton").classList.remove("fa-regular")
                document.getElementById("saveButton").classList.add("fa-solid")
            })
        }


    }

    hrefLogin=()=>{
        window.location.href = "<?=$base?>/login"
    }

    function triggerDownload(url) {
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', '');
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
                
</script>

<?php

    require_once '../partials/footer.php'

?>