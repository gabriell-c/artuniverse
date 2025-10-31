<?php
    require_once "./partials/header.php";

    if(isset($_SESSION['warning'])){
        require_once './modalAlert.php';
        unset($_SESSION['warning']);
    }

    $limit = 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    try {
    $stmt = $pdo->prepare("SELECT id, id_name, id_user, user_name, file, type, title, description, tags, creation_date, poster 
        FROM allposts 
        WHERE archive = 'false' 
        ORDER BY creation_date DESC 
            LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao buscar os posts: " . $e->getMessage());
    }

    $sqlAll = "SELECT * FROM users";
    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute();
    
    $Alluser = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
.modal-dialog {
    max-width: 90%;
    width: auto;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden; /* Evita que o modal todo role */
}

.modal-body {
    /* flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center; */
    overflow: auto;
}

.modal-body img,
.modal-body video {
    max-width: 100%;
    max-height: 70vh; /* Ocupa a maior altura possível */
    object-fit: contain; /* Garante que não corte nada */
}

    oast{
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


</style>

<main >
    <?php require_once './sidebar.php'; ?> 

    <section class="sectionVideosHome">
        <div class="itemsVideoHome" id="itemsGrid">
            <?php foreach ($data as $item): ?>
                <?php 

                    $sqlLike = "SELECT * FROM post_likes WHERE id_post = :postId";
                    $stmtLike = $pdo->prepare($sqlLike);
                    $stmtLike->bindValue(':postId', $item['id'], PDO::PARAM_INT);
                    $stmtLike->execute();
                    $resultPostItemLike = $stmtLike->fetchAll(PDO::FETCH_ASSOC);

                    $sql = "SELECT * FROM users WHERE id = :id_user";
                    $stmtU = $pdo->prepare($sql);
                    $stmtU->bindValue(':id_user', $item['id_user']);
                    $stmtU->execute();
                    $userI = $stmtU->fetch(PDO::FETCH_ASSOC);

                    $sqlFollowers = "SELECT COUNT(*) as total_followers FROM followers WHERE followed_id = :user_id";
                    $stmtFollowers = $pdo->prepare($sqlFollowers);
                    $stmtFollowers->bindValue(':user_id', $item['id_user']);
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
                        
                        // Define o texto do botão com base no status de seguimento
                        $buttonText = $isFollowing ? "Unfollow" : "Follow";



                    
                        $sqlSP = "SELECT * FROM itemsave WHERE id_post = :id_post AND id_user = :id_user";
                        $stmtSP = $pdo->prepare($sqlSP);
                        $stmtSP->bindValue(':id_post', $item['id']);
                        $stmtSP->bindValue(':id_user', $_SESSION['user']['id']);
                        $stmtSP->execute();
                        $saveItem = $stmtSP->fetch(PDO::FETCH_ASSOC);
                    }else{
                        $buttonText = "Follow";
                    }
    
                    $sqAllPosts = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType";
                    $stmtAllPosts = $pdo->prepare($sqAllPosts);
                    $stmtAllPosts->bindValue(':id_user', $userI['id']);
                    $stmtAllPosts->bindValue(':archiveType', 'false');
                    $stmtAllPosts->execute();

                    
                    $sqlC = "SELECT * FROM post_comments WHERE id_post = :post_id";
                    $stmtC = $pdo->prepare($sqlC);
                    $stmtC->bindValue(':post_id', $item['id'], PDO::PARAM_INT);
                    $stmtC->execute();
                    $comments = $stmtC->fetchAll(PDO::FETCH_ASSOC);
                
                    $postItemCount = $stmtAllPosts->fetchAll(PDO::FETCH_ASSOC);

                    $filePath = ($item['type'] === 'video') ? "./public/storage/videos/{$item['file']}" :
                                (($item['type'] === 'audio') ? "./public/storage/audios/{$item['file']}" : 
                                "./public/storage/photos/{$item['file']}");
                    $poster = !empty($item['poster']) ? "./public/storage/poster{$item['type']}/{$item['poster']}" : "./public/img/saturn_background.jpg";
                ?>
                <div class="itemVideoHome <?= $item['type'] ?>" data-id="<?= $item['id']; ?>">
                    <img class="gridItem open-modal" 
                        src="<?= $item['type'] === 'image' ? $filePath : $poster; ?>" 
                        data-target="#<?= $item['type']; ?>Modal<?= $item['id']; ?>">
                        
                        <?php if ($item['type'] === 'video' || $item['type'] === 'audio'): ?>
                            <i class="fa-solid fa-circle-play playIcon"></i>
                        <?php endif; ?>
                        
                        <!-- <div class="blackFilter"></div> -->
                    </img>


                    <h4><a href="<?= $base . '/' . $item['user_name'] ?>">@<?= $item['user_name'] ?></a></h4>
                    <h3><?= $item['title'] ?></h3>
                </div>

                <!-- Modais -->
                <div class="modal fade 	.modal-xl" id="<?= $item['type']; ?>Modal<?= $item['id']; ?>" tabindex="-1" role="dialog" data-file="<?= $item['file']; ?>">
                    <div class="modal-dialog<?= $item['type'] === 'video' ? ' modal-lg' : '' ?>">
                        <div class="modal-content">
                            

                            <div class="modal-body text-center">
                                <div class="headerModalIndex">
                                    <h3 id="titleModal"><?= $item['title'] ?></h3>
                                    <i class="fa-solid fa-xmark close" data-dismiss="modal"></i>
                                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                                </div>
                                <?php if ($item['type'] === 'video'): ?>
                                    <video style="width: auto; margin: 0 auto;" loop controls class="video-player plyr__video">
                                        <source src="<?= $filePath; ?>" type="video/mp4">
                                    </video>
                                <?php elseif ($item['type'] === 'audio'): ?>
                                    <div class="posterHomeAudioArea">
                                        <img  src="<?= $poster; ?>" class="img-fluid mb-2 posterHomeAudio" alt="Capa do áudio">
                                        <button id="playButton-<?= $item['id']; ?>">
                                            <img id="playButtonIcon-<?= $item['id']; ?>" src="./public/img/play-button.png">
                                        </button>
                                    </div>
                                    <div id="waveform-<?= $item['id']; ?>"></div>
                                <?php elseif ($item['type'] === 'image'): ?>
                                    <img style="width: auto; margin: 0 auto;"  src="<?= $filePath; ?>" alt="<?= $item['title']; ?>" class="img-fluid">
                                <?php endif; ?>

                                <div class="areaInterContent" style="margin-top: 15px">
                                    <div class="areaInterContentLeft">
                                        <?php
                                        // print_r($resultPostItemLike);
                                                if(isset($_SESSION['user']) && isset($resultPostItemLike)){
                                                    if(isset($resultPostItemLike[0]['id_post']) == $item['id']){
                                                        echo '<i id="likeButton'.$item['id'].'" data-post-id="'.$item['id'].'" data-user-id="'.$_SESSION['user']['id'].'" onclick="likePostAction('.$item['id'].', '.$_SESSION['user']['id'].')" class="fa-solid fa-heart likeButton liked"></i>';
                                                    }else {
                                                        echo '<i id="likeButton'.$item['id'].'" data-post-id="'.$item['id'].'" data-user-id="'.$_SESSION['user']['id'].'" onclick="likePostAction('.$item['id'].', '.$_SESSION['user']['id'].')" class="fa-regular fa-heart likeButton"></i>';
                                                    }
                                                }
                                                
                                            else{
                                                echo '<i id="likeButton'.$item['id'].'" onclick="hrefLogin()" class="fa-regular fa-heart likeButton"></i>';
                                            }   
                                        ?>
                                        <i style="cursor: pointer;" class="fa-solid fa-share"  data-video="share" data-toggle="modal" data-target="#sahreModal"></i>
                                        <i style="cursor: pointer;" class="fa-solid fa-link" onclick="copyLink('<?=$base?>/watch/<?=$item['id_name']?>'); runToast('timeout')"></i>
                                        <?php if(isset($_SESSION['user'])) :?>
                                            <!-- <i id="saveButton" onclick="savePost(<?=$item['id']?>, <?=$_SESSION['user']['id']?>, '<?=$_SESSION['user']['user_name']?>');" class="<?=isset($saveItem['id_post']) && $saveItem['id_post'] == $item['id'] ? 'fa-solid' : 'fa-regular' ?> fa-bookmark"></i>  -->
                                        <?php else: ?>
                                            <i id="saveButton" onclick="hrefLogin()" class="fa-regular fa-bookmark"></i> 
                                        <?php endif?>
                                    </div>
                                    <div class="areaInterContentRigth">
                                        <div class="contContentInt">
                                            <i class="fa-solid fa-heart" style="color:#ff0033;"></i>
                                            <span id="spanLikeProfile<?=$item['id']?>"><?=count($resultPostItemLike)?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="descArea">
                                    <div class="descAreaLeft">
                                    <img style="object-fit: cover" id="profileAvatar" src="<?= $userI['profile_photo'] !== null ? ($base.'/upload/profilePhoto/'.$userI['profile_photo']) : $base.'/public/img/saturn_background.jpg'?>">
                                        <h5><?=$userI['user_name']?></h5>
                                        <div><i class="fa-solid fa-user-group"></i> <?= $followersCount ?></div>
                                        <div><i class="fa-solid fa-file"></i><?=count($postItemCount)?></div>
                                        <?php
                                            if(isset($_SESSION['user']) && $_SESSION['user']['user_name'] != $userI['user_name']){
                                                echo '<button style="border: none" id="followBtn'.$item['id'].'" class="buttonFollw2">'.$buttonText.'</button>';
                                            }
                                        ?>
                                    </div>
                                    <div class="descAreaRight">
                                        <pre id="descContent<?= $item['id'] ?>" class="textDescContent"><?=$item['description']?></pre>
                                        <button id="buttonShowDesc<?= $item['id'] ?>" class="showDescButton" >show more<i id="iconShowDesc<?= $item['id'] ?>" class="fa-solid fa-angle-down"></i></button>
                                        <hr style="opacity: .1; width: 100%;">
                                        <div class="sendCommentArea" style="width: 100%;">
                                            <form id="commentForm" action="actions/addComment.php" method="POST">
                                                <i onclick="addComment(<?=$item['id']?>)" class="bi bi-send-fill"></i>
                                                <textarea placeholder="add a comment..." id="comment<?=$item['id']?>" type="text"  class="inputAddComment"></textarea>
                                            </form>
                                        </div>
                                        <div class="headerComentsArea">
                                            <div class="headerComentsAreaLeft">
                                                <i class="fa-solid fa-message"></i>
                                                <?=count($comments)?> comments
                                            </div>
                                            <div class="headerComentsAreaRight">

                                            </div>
                                        </div>
                                            <hr style="opacity: .1; width: 100%;">

                                        <?php if($item): ?>

                                            <!-- Container de comentários -->
                                            <div style="width: 100%" id="commentsContainer<?=$item['id']?>"></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>


<script>
    const allUsers = <?= json_encode($Alluser) ?>;

    function loadComments(postId) {
        const commentsContainer = document.getElementById(`commentsContainer${postId}`);
        commentsContainer.innerHTML = ""; // Limpa comentários anteriores

        $.ajax({
            url: '/artuniverse/actions/getComments.php',
            type: 'POST',
            data: { postId: postId },
            dataType: 'json',
            success: function(data) {
                data.forEach(comment => {
                    const user = allUsers.find(u => u.id == comment.id_user);
                    const profilePhoto = user && user.profile_photo ? `/artuniverse/upload/profilePhoto/${user.profile_photo}` : '/artuniverse/public/img/saturn_background.jpg';
                    const tempoPostagem = calculateTimeAgo(comment.comment_date);
                    const deleteButton = (<?= isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?> == comment.id_user) 
                    ? `<i style="margin-right: 10px; cursor: pointer;" class="fa-solid fa-trash" onclick="deleteComment(${comment.id}, ${postId})"></i>` 
                    : '';

                    const newCommentArea = document.createElement("div");
                    newCommentArea.classList.add("commentItemArea");
                    newCommentArea.innerHTML = `
                        <img style="object-fit: cover;" src="${profilePhoto}" alt="profile photo">
                        <div id="areaOver${comment.id}" class="BodyCommmentArea">
                            <div class="headerCommment">
                                <p>@${comment.user_name}</p>
                                <div style="display: flex;">
                                        ${deleteButton}
                                    <p>${tempoPostagem}</p>
                                </div>
                            </div>
                            <pre class="textComment">${comment.comment}</pre>
                        </div>
                    `;
                    commentsContainer.appendChild(newCommentArea);
                });

                document.getElementById(`comment${postId}`).addEventListener("keydown", function(event) {
                    if (event.key === "Enter" && !event.shiftKey) {
                        event.preventDefault(); // Impede a quebra de linha
                        addComment(postId); // Chama a função para enviar o comentário
                    }
                })
            
            },
            error: function() {
                console.log("Erro ao carregar os comentários.");
            }
        });
    }
    const audioPlayers = new Map();

    document.addEventListener("DOMContentLoaded", function() {

        function loadComments(postId) {
            const commentsContainer = document.getElementById(`commentsContainer${postId}`);
            commentsContainer.innerHTML = ""; // Limpa comentários anteriores

            $.ajax({
                url: '/artuniverse/actions/getComments.php',
                type: 'POST',
                data: { postId: postId },
                dataType: 'json',
                success: function(data) {
                    data.forEach(comment => {
                        const user = allUsers.find(u => u.id == comment.id_user);
                        const profilePhoto = user && user.profile_photo ? `/artuniverse/upload/profilePhoto/${user.profile_photo}` : '/artuniverse/public/img/saturn_background.jpg';
                        const tempoPostagem = calculateTimeAgo(comment.comment_date);
                        const deleteButton = (<?= isset($_SESSION['user']) && isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'null' ?> == comment.id_user) 
    ? `<i style="margin-right: 10px; cursor: pointer;" class="fa-solid fa-trash" onclick="deleteComment(${comment.id}, ${postId})"></i>` 
    : '';



                        const newCommentArea = document.createElement("div");
                        newCommentArea.classList.add("commentItemArea");
                        newCommentArea.innerHTML = `
                            <img style="object-fit: cover;" src="${profilePhoto}" alt="profile photo">
                            <div id="areaOver${comment.id}" class="BodyCommmentArea">
                                <div class="headerCommment">
                                    <p>@${comment.user_name}</p>
                                    <div style="display: flex;">
                                            ${deleteButton}
                                        <p>${tempoPostagem}</p>
                                    </div>
                                </div>
                                <pre class="textComment">${comment.comment}</pre>
                            </div>
                        `;
                        commentsContainer.appendChild(newCommentArea);
                    });
                },
                error: function() {
                    console.log("Erro ao carregar os comentários.");
                }
            });
        }


        document.querySelectorAll(".open-modal").forEach(item => {
            item.addEventListener("click", function() {
                const targetId = this.getAttribute("data-target");
                $(targetId).modal("show");

                const videoElement = document.querySelector(`${targetId} video`);
                if (videoElement) {
                    const plyrPlayer = new Plyr(videoElement);
                    plyrPlayer.play();
                }

                <?php foreach ($data as $item): ?>
                    loadComments(<?= $item['id'] ?>);

                    const followBtn<?= $item['id'] ?> = document.getElementById("followBtn<?= $item['id'] ?>");

                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['id'] !== $item['id_user']): ?>
                            // Adiciona o evento de clique para seguir
                            if (followBtn<?= $item['id'] ?>) {
                                followBtn<?= $item['id'] ?>.addEventListener("click", function() {
                                    followUser(<?= $_SESSION['user']['id'] ?>, <?= $item['id_user'] ?>);
                                    if (followBtn<?= $item['id'] ?>.textContent === "Follow") {
                                    followBtn<?= $item['id'] ?>.textContent = "Unfollow";
                                } else {
                                    followBtn<?= $item['id'] ?>.textContent = "Follow";
                                }
                                });
                            }     
                        
                        <?php endif ?>
                    <?php else: ?>
                        // Redireciona para a página de login se o usuário não estiver logado
                        if (followBtn<?= $item['id'] ?>) {
                            followBtn<?= $item['id'] ?>.addEventListener("click", function() {
                                window.location.href = "/artuniverse/login"; // Redireciona para a página de login
                            });
                        }
                    <?php endif ?>


                    const preElement<?= $item['id'] ?> = document.getElementById("descContent<?= $item['id'] ?>");
                    const lineHeight<?= $item['id'] ?> = parseFloat(getComputedStyle(preElement<?= $item['id'] ?>).lineHeight<?= $item['id'] ?>);
                    const height<?= $item['id'] ?> = preElement<?= $item['id'] ?>.clientHeight<?= $item['id'] ?>;
                    const numberOfLines<?= $item['id'] ?> = Math.ceil(height<?= $item['id'] ?> / lineHeight<?= $item['id'] ?>);

                    if (numberOfLines<?= $item['id'] ?> >= 7) {
                        preElement<?= $item['id'] ?>.classList.add("overflowHiddenTextContent")

                        document.getElementById('buttonShowDesc<?= $item['id'] ?>').addEventListener('click', function() {
                            if(preElement<?= $item['id'] ?>.classList.contains("overflowHiddenTextContent")){
                                preElement<?= $item['id'] ?>.classList.remove("overflowHiddenTextContent")
                                document.getElementById('buttonShowDesc<?= $item['id'] ?>').innerHTML = "show less<i id='iconShowDesc' class='fa-solid fa-angle-up'></i>"
                                document.getElementById("iconShowDesc<?= $item['id'] ?>").classList.add("fa-angle-up")
                                document.getElementById("iconShowDesc<?= $item['id'] ?>").classList.remove("fa-angle-down")
                            }else{
                                preElement<?= $item['id'] ?>.classList.add("overflowHiddenTextContent")
                                document.getElementById('buttonShowDesc<?= $item['id'] ?>').innerHTML = "show more<i id='iconShowDesc' class='fa-solid fa-angle-down'></i>"
                                document.getElementById("iconShowDesc<?= $item['id'] ?>").classList.remove("fa-angle-up")
                                document.getElementById("iconShowDesc<?= $item['id'] ?>").classList.add("fa-angle-down")
                            }
                        });
                    } else {
                        document.getElementById('buttonShowDesc<?= $item['id'] ?>').style.display = 'none';
                    }
                <?php endforeach; ?>


                // Defina a função followUser fora do loop
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
                            // Mude o texto do botão aqui com base no ID do post
                        } else {
                            console.log("Erro ao seguir.");
                        }
                    });
                }




                // Configuração para tocar áudio com WaveSurfer
                if (targetId.includes("audioModal")) {
                    const audioId = targetId.replace("#audioModal", "");
                    const audioFile = document.querySelector(targetId).dataset.file;

                    if (!audioPlayers.has(audioId)) {
                        const wavesurfer = WaveSurfer.create({
                            container: `#waveform-${audioId}`,
                            waveColor: "#555",
                            progressColor: "#9673ff",
                            barWidth: 2,
                            responsive: true,
                            height: 120,
                            barRadius: 4
                        });

                        wavesurfer.load(`./public/storage/audios/${audioFile}`);
                        audioPlayers.set(audioId, wavesurfer);

                        document.getElementById(`playButton-${audioId}`).addEventListener("click", () => {
                            wavesurfer.playPause();
                            const playIcon = document.getElementById(`playButtonIcon-${audioId}`);
                            playIcon.src = wavesurfer.isPlaying() ? "./public/img/pause.png" : "./public/img/play-button.png";
                        });
                    }
                }
            });
        });

      


          // Função para enviar o novo comentário com AJAX
        


    });

    function addComment(postId) {

        var comment = document.getElementById(`comment${postId}`).value;
        var postId = postId; // Obtém o ID do post atual



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
            document.getElementById(`comment${postId}`).value = '';

            // Recarrega a lista de comentários após o novo comentário ser adicionado
            loadComments(postId);

        })
        .catch(error => {
            console.log('Erro ao enviar o comentário.');
        })
    }

    function deleteComment(commentId, postId) {
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
            console.log("Resposta do servidor ao excluir:", data); // Log da resposta do servidor
            // Recarrega a lista de comentários após a exclusão
            loadComments(postId); // Passa o postId para recarregar os comentários corretamente
        })
        .catch(error => {
            console.log('Erro ao excluir o comentário: ' + error);
        });
    }


        <?php foreach ($data as $item): ?>
            loadComments(<?= $item['id'] ?>);
        <?php endforeach; ?>

        // Parar vídeo e áudio ao fechar qualquer modal
        $(document).on("hidden.bs.modal", ".modal", function() {
            const videoElement = this.querySelector("video");
            if (videoElement) {
                videoElement.pause();
                videoElement.currentTime = 0;
            }

            const audioId = this.id.replace("audioModal", "");
            if (audioPlayers.has(audioId)) {
                const wavesurfer = audioPlayers.get(audioId);
                wavesurfer.stop();
                const playIcon = document.getElementById(`playButtonIcon-${audioId}`);
                if (playIcon) {
                    playIcon.src = "./public/img/play-button.png";
                }
            }
        });

        function calculateTimeAgo(commentDate) {
            const agora = new Date();
            const dataPassada = new Date(commentDate);
            const intervalo = agora - dataPassada;
            const segundos = Math.floor(intervalo / 1000);
            const minutos = Math.floor(segundos / 60);
            const horas = Math.floor(minutos / 60);
            const dias = Math.floor(horas / 24);
            const meses = Math.floor(dias / 30);
            const anos = Math.floor(meses / 12);

            if (anos > 0) return `${anos} ${anos === 1 ? "year ago" : "years ago"}`;
            if (meses > 0) return `${meses} ${meses === 1 ? "month ago" : "months ago"}`;
            if (dias > 0) return `${dias} ${dias === 1 ? "day ago" : "days ago"}`;
            if (horas > 0) return `${horas} ${horas === 1 ? "hour ago" : "hours ago"}`;
            if (minutos > 0) return `${minutos} ${minutos === 1 ? "minute ago" : "minutes ago"}`;
            return "recently";
        }

        


    function likePostAction(postId, userId) {
            fetch('/artuniverse/actions/update_like_post.php', {
                method: 'POST',
                body: new URLSearchParams({ postId, userId }),
            })
            .then(response => response.text())
            .then(result => {
                console.log(result);
                const likeButton = document.getElementById('likeButton' + postId);
                if (result.includes('curtido com sucesso')) {
                    likeButton.classList.add('liked', 'fa-solid');
                    likeButton.classList.remove('fa-regular');
                    document.getElementById('spanLikeProfile' + postId).textContent = parseInt(document.getElementById('spanLikeProfile' + postId).textContent) + 1;
                } 
                if (result.includes('descurtido com sucesso')) {
                    document.getElementById('likeButton' + postId).classList.remove('liked');
                    document.getElementById('likeButton' + postId).classList.remove('fa-solid');
                    document.getElementById('likeButton' + postId).classList.add('fa-regular');
                    document.getElementById('spanLikeProfile' + postId).textContent = parseInt(document.getElementById('spanLikeProfile' + postId).textContent) - 2;
                }
            });
        }


    function copyLink(link) {
        const tempInput = document.createElement('input');
        tempInput.value = link;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        runToast('timeout');
    }

    function runToast(mode) {
        const toast = Metro.toast.create;
        if (mode === 'timeout') toast("copied link", null, 1000);
    }


</script>




<?php require_once "./partials/footer.php"; ?>
