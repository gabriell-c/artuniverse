<?php

require_once '../config.php';
require_once '../partials/header.php';
require_once '../loadingelement.php';

$sql = "SELECT * FROM allplaylist WHERE id_name = :id_name ";
$sql = $pdo->prepare($sql);
$sql->bindValue(':id_name', $_GET['id_name']);
$sql->execute();
$playlist = $sql->fetch(PDO::FETCH_ASSOC);


if (empty($playlist)) {
    require_once '../not_found.php';
    exit;
}

if(isset($_SESSION['warning'])){
    require_once 'modalAlert.php';
    unset($_SESSION['warning']);
}

$isPrivate = $playlist['privacy'] === 'private';
$isLogged = isset($_SESSION['user']);
$isOwner = $isLogged && $playlist['user_name'] === $_SESSION['user']['user_name'];

if ($isPrivate && (!$isLogged || !$isOwner)) {
    require_once '../403.php';
    exit;
}





//--------------------------------------//

$sqlU = "SELECT * FROM users WHERE user_name = :user_name";
$sqlU = $pdo->prepare($sqlU);
$sqlU->bindValue(':user_name', $playlist['user_name']);
$sqlU->bindValue(':user_name', $playlist['user_name']);
$sqlU->execute();
$userPlaylist = $sqlU->fetch(PDO::FETCH_ASSOC);


//------------------------------------//

$sqlAP = "SELECT * FROM playlist WHERE id_name = :id_name AND type = :type";
$sqlAP = $pdo->prepare($sqlAP);
$sqlAP->bindValue(':id_name', $_GET['id_name']);
$sqlAP->bindValue(':type', $playlist['type'] === 'video' ? 'video' : 'audio');
$sqlAP->execute();
$itemPlaylist = $sqlAP->fetchAll(PDO::FETCH_ASSOC);

//--------------------------------------//


if(isset($_SESSION['warning'])){
    require_once '../modalAlert.php';
    unset($_SESSION['warning']);
}

?>

<style>
    
    .app{
        display: flex;
        margin: 0;
    }
    <?php if($playlist['type'] === 'video') :?>
        .bodyHeaderPlaylistItem{
            overflow: visible;
        }
        article {
            position: relative;
            margin: 5px;
            float: left;
            border: 2px solid var(--color1);
            box-sizing: border-box;
            border-radius: 50px;
            padding: 0.4em 1.5em;
            transition: ease-in-out .2s;
        }

        article div {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 25px;
            transition: .2s ease;
            margin: 0;
        }

        article:hover {
            scale: 1.05;
            filter: brightness(1.2);
            transition: ease-in-out .2s;
        }

        article input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 1;
        }

        article span i {
            margin-right: 10px;
        }

        .checked{
            background-color: var(--color1);
        }
    <?php endif ?>

    .d-menu{
        right: 0;
    }

    .d-menu li{
        color: var(--color5);
        background: var(--color4);
        text-align: left;
    }
    .d-menu i{
        margin-right: 10px;
        font-size: 16px;
    }

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
    .plyr__controls__item .plyr__control,
    .plyr__controls .plyr__controls__item.plyr__time,
    .plyr__volume,
    .plyr__controls .plyr__controls__item:first-child{
        /* display: none !important; */
        /* background: var(--color1) !important ; */
    }
    .plyr--audio .plyr__controls {
        background: transparent;
    }

    @keyframes rot {
        0%{
            transform: rotate(0deg);
        }
        100%{
            transform: rotate(360deg);
        }
    }

    <?php if($playlist['type'] === 'audio') :?>
        .plyr{
            height: auto !important ;
            display: none;
        }

        .plyr--audio .plyr__controls {
            padding: 0;
        }

        article {
            position: relative;
            box-sizing: border-box;
            border-radius: 50px;
        }

        article div {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 25px;
            transition: .2s ease;
            margin: 0;
        }

        article:hover {
            scale: 1.05;
            filter: brightness(1.2);
            transition: ease-in-out .2s;
        }

        article input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 1;
        }

        article span i {
            margin-right: 10px;
        }

        .checked{
            color: white !important;
            background: #ffffff20;
            height: 40px;
            border-radius: 50%;
        }
        
        .d-menu {
            top: 100%;
        }
    <?php endif ?>

</style>

<section style="display: flex; width: 100%;">
    <?php require_once '../sidebar.php'?>
    <div class="mainPlaylistArea">

        <?php if($playlist['type'] === 'video') :?>
            <div style="display: none;" class="viewAreaPlaylist">
                <video id="playlistPlayer" class="videobody">
                    <!-- O vídeo será carregado dinamicamente aqui -->
                </video>
                <h1 style="cursor: pointer" id="titleVideo" class="titleContent"></h1>
                <div class="areaInterContent">
                    <div class="areaInterContentRigth" style="width: 100%; justify-content: space-between;">
                        <div class="contContentInt">
                            <i class="fa-solid fa-heart" style="color:#ff0033;"></i>
                            <span id="countLikePost"></span>
                        </div>
                        <div class="contContentInt">
                            <i class="fa-regular fa-message"></i>
                            <span id="countCommentPost"></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <div style="<?=$playlist['type'] === 'video' ? 'max-height: 250px;' : 'max-height: 350px;'?>" class="headerPlaylistItemArea">
            <img id="image" <?= $playlist['type'] === 'audio' ? 'style="border-radius: 50%;"' : '' ?> src="<?= $playlist['poster'] !== '' ? $base.'/public/storage/posterPlaylist/'.$playlist['poster'] : $base.'/public/img/saturn_background.jpg'?>">
            <div class="bodyHeaderPlaylistItem">
                <div class="bodyHeaderPlaylistItem_div">
                    <h2 id="playlist_name"><?=$playlist['playlist_name']?></h2>
                    <span>
                        <img style="box-shadow: none;" src="<?=$base?>/upload/profilePhoto/<?=$userPlaylist['profile_photo']?>">
                        <strong id="userArea" style="margin-right: 10px; cursor: pointer;"><?=$userPlaylist['user_name']?></strong>
                        | <?=count($itemPlaylist)?> <?=$playlist['type'] === 'video' ? (count($itemPlaylist) > 1 ? 'Videos' : 'Video') : (count($itemPlaylist) > 1 ? 'Songs' : 'Music')?> <?=$playlist['privacy'] === 'private' ? '<i class="fa-solid fa-lock" style="margin-left: 10px; font-size: 14px;"></i>' : '<i class="fa-solid fa-earth-americas" style="margin-left: 10px; font-size: 14px;"></i>'?>
                    </span>
                </div>
                
                <div class="headerPlaylistItemmButtonsArea" style="margin: 1em 0 0 0;">

                <?php if($playlist['type'] === 'audio') :?>
                    <div class="audioPlayercontrolArea">
                        <article class="feature2" style="margin-right: auto;" >
                            <input type="checkbox" id="feature2"/>
                            <div>
                                <span style="font-size: 20px; margin: 0;">
                                    <i style="font-size: 20px;" class="fa-solid fa-shuffle"></i>
                                </span>
                            </div>
                        </article>
                        <i id="playBeforeAudio"  <?= count($itemPlaylist) < 2 ? 'style="opacity: .5;"' : '' ?> class="fa-solid fa-backward-step"></i>
                        <i id="playAudioButton" style="font-size: 40px;" class="bi bi-play-circle-fill"></i>
                        <i id="pauseAudioButton" style="font-size: 40px; display: none;" class="bi bi-pause-circle-fill"></i>
                        <i id="playNextVideo" <?= count($itemPlaylist) < 2 ? 'style="opacity: .5;"' : '' ?> class="fa-solid fa-forward-step"></i>
                        <article class="feature1" style="margin-left: auto;" >
                            <input type="checkbox" id="feature1" />
                            <div>
                                <span class="iconRepeat" style="font-size: 20px; margin: 0;">
                                    <i  style="font-size: 20px;" class="fa-solid fa-repeat"></i>
                                </span>
                            </div>
                        </article>
                        
                    </div>
                    

                <?php else :?>

                    <button class="playButtonPlaylist"  onclick="loadVideo(0); this.style.display = 'none'; document.querySelector('.feature1').style.display = 'inline-block'"><i class="fa-solid fa-play"></i>Play</button>

                    <section class="app">
                        <article class="feature1" style="display: none;">
                            <input type="checkbox" id="feature1" />
                            <div>
                                <span>
                                    <i class="fa-solid fa-repeat"></i> Repeat
                                </span>
                            </div>
                        </article>
                        
                        <article class="feature2" onclick="document.querySelector('.feature1').style.display = 'inline-block'" >
                            <input type="checkbox" id="feature2"/>
                            <div>
                                <span>
                                    <i class="fa-solid fa-shuffle"></i>Random
                                </span>
                            </div>
                        </article>
                    </section>
                    <?php endif ?>

                    <div style="margin-left: auto; font-size: 24px; display: flex;">
                        <div class="wrapperAudio">
                        <i class="fa-solid fa-volume-high" id="icon"></i>
                            <input id="inputVolume" type="range" min="0" step="1" max="100" value="100" />
                        </div>
                        <i class="fa-solid fa-ellipsis-vertical" style="cursor: pointer; margin-left: auto; font-size: 22px; padding: 4px;"></i>
                        <ul class="d-menu" data-role="dropdown">
                            <li onclick="copyLink('<?=$base?>/playlist/<?=$playlist['id_name']?>'); runToast('timeout')" ><a disabled><i class="fa-solid fa-link"></i>Copy link</a></li>
                            <li class="divider"></li>
                            <?php if(isset($_SESSION['user']) && ($_SESSION['user']['id']) == $userPlaylist['id']): ?>
                                <li data-video="edit" data-toggle="modal" data-target="#edit"><a disabled><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                <li id="deletePlaylist" ><a disabled><i class="fa-solid fa-trash"></i>Delete</a></li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
                <div id="areaAudio" style="overflow: hidden; width: 100%;">
                    <h2 style="max-width: 500px;" id="titleVideo" class="titleContentAudio" ></h2>
                    <div class="timeArea">
                        <span id="currentTimeAudio">00:00</span>
                        <span id="durationMaxAudio" ></span>
                    </div>
                    <audio id="playlistPlayer"></audio>
                </div>
            </div>
        </div>

        <div class="videoPlaylistArea">
            <?php for($i = 0; $i < count($itemPlaylist); $i++): ?>
                <div class="playlistItem">
                    <div class="playlistItemAreaLeft" onclick="loadVideo(<?= $i ?>)">
                        <span id="numberPlaylistItem<?=$itemPlaylist[$i]['id_post']?>"><?=$i+1?></span>
                        <img src="<?= $itemPlaylist[$i]['poster'] ? $base.'/public/storage/posterVideo/'.$itemPlaylist[$i]['poster'] : $base.'/public/img/saturn_background.jpg' ?>">
                        <div class="infoItemPlaylist">
                            <h4 id="titlePlaylistItem<?=$itemPlaylist[$i]['id_post']?>" ><?=$itemPlaylist[$i]['title']?></h4>
                            <div class="infoBottomPlaylistItem">
                                <?=$itemPlaylist[$i]['user_name']?> - <span id="durationVideoItem<?=$itemPlaylist[$i]['id_post']?>">2:08</span>
                            </div>
                        </div>
                    </div>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </div>
            <?php endfor ?>
        </div>
    </div>


    <form method="post" enctype="multipart/form-data" action="../actions/updatePlaylist.php" class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color: var(--color5);" class="modal-title">Edit playlist</h5>
                    <button  style="color: var(--color5);" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div style="display: flex; flex-direction: column; padding: 2em;" class="modal-body">
                    <div class="js" >
                        <div class="input-file-container-cover-playlist">  
                            <input  class="input-file-cover-playlist" id="my-file" type="file" name="poster_playlist" accept="image/*" >
                            <label tabindex="0" for="my-file" class="input-file-trigger">Select a file...</label>
                        </div>
                        <p id="file-return" class="file-return"><?=$playlist['poster'] != '' ? $playlist['poster'] : ''?></p>
                    </div>

                    <input type="hidden" name="id_name_playlist" value="<?=$playlist['id_name']?>">

                    <div style="margin: 1em 0;">
                        <label style="color: var(--color5);" for="titleEdit">Title</label>
                        <input placeholder="Edit title" id="titleEdit" name="title_playlist" class="inputEditPlaylist" required type="text" <?=$playlist['playlist_name'] ? 'value="'.$playlist['playlist_name'].'"' : ''?>>
                    </div>

                    <div style="margin: 1em 0;">
                        <?php if($playlist['privacy'] == 'private') :?>
                            <select name="privacy_playlist" data-role="select">
                                <option value="private" >Private</option>
                                <option value="public" >Public</option>
                            </select>
                        <?php else :?>
                            <select name="privacy_playlist" data-role="select">
                                <option value="public" >Public</option>
                                <option value="private" >Private</option>
                            </select>
                        <?php endif ?>
                    </div>

                    <div class="footerButtonAreaEditPlaylist">
                        <button type="button" class="btn btn-danger ml-3" data-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-success ml-3" >Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>


<script> 

    document.title = " <?=$playlist['playlist_name']?> - playlist | Artuniverse"

    //-----------------------------//

    function rgbToHex(r, g, b) {
        return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
    }

    const img = document.getElementById('image');
        window.onload = () => {
        const colorThief = new ColorThief();
        const dominantColor = colorThief.getColor(img);
        const hexColor = rgbToHex(dominantColor[0], dominantColor[1], dominantColor[2]);

        document.querySelector(".headerPlaylistItemArea").style.background = "linear-gradient(to left, var(--color3) 30%, " + hexColor + ')'
    };


    //-----------------------------//

    
    var fileInput  = document.querySelector( ".input-file-cover-playlist" ),  
        button     = document.querySelector( ".input-file-trigger" ),
        the_return = document.getElementById("file-return");
        
    button.addEventListener( "keydown", function( event ) {  
        if ( event.keyCode == 13 || event.keyCode == 32 ) {  
            fileInput.focus();  
        }  
    });
    button.addEventListener( "click", function( event ) {
        fileInput.focus();
        return false;
    });  
    fileInput.addEventListener("change", function(event) {
        // Acesse o arquivo selecionado usando a propriedade `files`
        const selectedFile = event.target.files[0];
        if (selectedFile) {
            // Faça algo com o arquivo selecionado, como exibir o nome
            the_return.innerHTML = selectedFile.name;
        } else {
            // Lide com o caso em que nenhum arquivo é selecionado
            the_return.innerHTML = "Nenhum arquivo selecionado";
        }
    });

    //------------------------//

    let player;
    let playlistVideos = <?= json_encode($itemPlaylist); ?>;
    let currentIndex = 0; // Índice do vídeo atual na playlist
    let loopActivated = false; // Variável para controlar se o loop está ativado

    function copyLink(link) {
        var tempInput = document.createElement('input');
        tempInput.value = link;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
    }


    //-----------------//

    function runToast(mode) {
        var toast = Metro.toast.create;
        switch (mode) {
            case 'timeout': toast("copied link", null, 1000); break;
        }
    }

    function getVideoDuration(videoFile) {
        return fetch(`/artuniverse/public/storage/<?=$playlist['type'] === 'video' ? 'videos' : 'audios'?>/${videoFile}`)
        .then((response) => response.blob())
        .then((videoBlob) => {
            const videoElement = document.createElement('video');
            videoElement.src = URL.createObjectURL(videoBlob);

            return new Promise((resolve) => {
                videoElement.addEventListener('loadedmetadata', () => {
                    const duration = videoElement.duration;
                    videoElement.remove();
                    resolve(duration);
                });
            });
        });
    }

    // Função para formatar o tempo em minutos:segundos
    function formatTime(timeInSeconds) {
        const minutes = Math.floor(timeInSeconds / 60);
        const seconds = Math.floor(timeInSeconds % 60);
        const formattedMinutes = String(minutes).padStart(2, '0');
        const formattedSeconds = String(seconds).padStart(2, '0');
        return `${formattedMinutes}:${formattedSeconds}`;
    }

    document.addEventListener('DOMContentLoaded', async () => {
        for (let i = 0; i < playlistVideos.length; i++) {
            const video = playlistVideos[i];
            const duration = await getVideoDuration(video.file);
            playlistVideos[i].duration = formatTime(duration); // Formata a duração em minutos:segundos
            document.getElementById("durationVideoItem" + playlistVideos[i].id_post).innerHTML = playlistVideos[i].duration
        }
    });

    document.getElementById("userArea").onclick = () =>{
        window.location.href = "<?=$base?>/<?=$userPlaylist['user_name']?>"
    }

    function initPlayer() {
        player = new Plyr('#playlistPlayer');
        player.stop()
        <?php if($playlist['type'] === 'video') :?>
            chekedButton();
        <?php endif ?>
        player.on('ended', playNextVideo);
        
        

        <?php if($playlist['type'] === 'audio') :?>

                player.on('pause', ()=>{
                    document.getElementById("image").classList.remove("rotateImage")
                    document.getElementById("playAudioButton").style.display = 'flex';
                    document.getElementById("pauseAudioButton").style.display = 'none';
                })

                player.on('stop', ()=>{
                    document.getElementById("image").classList.remove("rotateImage")
                })

            
        <?php endif ?>

        player.on('play', function () {

            <?php if($playlist['type'] === 'audio') :?>
                const volumeRange = document.getElementById('inputVolume');

                volumeRange.addEventListener('input', () => {
                    const volumeValue = parseFloat(volumeRange.value) / 100; // Normaliza o valor entre 0 e 1
                    player.volume = volumeValue;
                });
                myFunc();
                document.getElementById("playAudioButton").style.display = 'none';
                document.getElementById("pauseAudioButton").style.display = 'flex';
            <?php endif ?>
            
            const currentVideo = playlistVideos[currentIndex];

            function playPreviousVideo() {
                // Obtemos o índice do vídeo anterior na playlist
                currentIndex = getPreviousIndex();

                if (currentIndex >= 0 && currentIndex < playlistVideos.length) {
                    // Carregar e reproduzir o vídeo anterior se ainda houver vídeos na playlist
                    loadVideo(currentIndex);
                } else {
                    // Se não há mais vídeos anteriores na playlist, pausamos o player e definimos currentIndex como 0
                    player.stop();
                    currentIndex = 0;
                }
            }

            function getPreviousIndex() {
                // Se o índice atual for 0 e o loop não estiver ativado, não há vídeo anterior, então retornamos 0
                if (currentIndex === 0 && !loopActivated) {
                    return 0;
                } else if (currentIndex === 0 && loopActivated) {
                    // Se o índice atual for 0 e o loop estiver ativado, retornamos o índice do último vídeo na playlist
                    return playlistVideos.length - 1;
                } else {
                    // Caso contrário, retornamos o índice do vídeo anterior
                    return currentIndex - 1;
                }
            }

            

            <?php if($playlist['type'] === 'audio') :?>
                document.getElementById("image").classList.add("rotateImage")
                
                document.getElementById("playNextVideo").onclick = () => {
                    if (currentIndex+1 == playlistVideos.length) {
                        player.stop()
                    }else{
                        playNextVideo()
                    }
                }

                document.getElementById("playBeforeAudio").onclick = () => {
                    if (currentIndex == 0) {
                        player.stop()
                    }else{
                        playPreviousVideo()
                    }
                }
                
            <?php endif ?>

            
  
            
            document.getElementById("titleVideo").onclick = () => {
                window.location.href = "<?=$base?>/watch/" + currentVideo.file.slice(0, -4);
            }

            <?php if($playlist['type'] === 'video') :?>
                document.querySelector(".areaInterContent").onclick = () => {
                    window.location.href = "<?=$base?>/watch/" + currentVideo.file.slice(0, -4);
                }
            <?php endif ?>

            document.getElementById("titleVideo").innerText = playlistVideos[currentIndex].title;

            var elementIdTitle = "titlePlaylistItem" + playlistVideos[currentIndex].id_post;
            var elementIdNumber = "numberPlaylistItem" + playlistVideos[currentIndex].id_post;
            var elementTitle = document.getElementById(elementIdTitle);
            var elementNumber = document.getElementById(elementIdNumber);


            if (elementTitle && elementNumber) {
                // Define a cor verde para o título do vídeo ativo
                elementTitle.style.color = 'var(--color1)';
                elementNumber.style.color = 'var(--color1)';
            }

            <?php if($playlist['type'] === 'audio') :?>
                
                const audioElements = document.getElementsByTagName('audio');
                if (audioElements.length > 0) {
                    const audioElement = audioElements[0];
                    document.getElementById("areaAudio").style.display = 'block';
                    document.querySelector(".plyr").style.display = 'block'

                    audioElement.addEventListener('loadedmetadata', () => {
                        const duration = audioElement.duration;
                        const formattedDuration = formatTime(duration);
                        document.getElementById('durationMaxAudio').innerText = formattedDuration;
                    });
                

                    audioElement.addEventListener('timeupdate', () => {
                        const currentTime = audioElement.currentTime;
                        const formattedCurrentTime = formatTime(currentTime);
                        document.getElementById('currentTimeAudio').innerText = formattedCurrentTime;
                    });

                    if(document.getElementById('currentTimeAudio').textContent !== ''){
                        document.querySelector(".headerPlaylistItemmButtonsArea").style.margin = '1em 0'
                    }

                }

            <?php endif ?>

            // Remove a cor dos títulos dos vídeos não ativos
            for (let i = 0; i < playlistVideos.length; i++) {
                if (i !== currentIndex) {
                    var inactiveElementIdTitle = "titlePlaylistItem" + playlistVideos[i].id_post;
                    var inactiveElementIdNumber = "numberPlaylistItem" + playlistVideos[i].id_post;
                    var inactiveElementTitle = document.getElementById(inactiveElementIdTitle);
                    var inactiveElementNumber = document.getElementById(inactiveElementIdNumber);

                    if (inactiveElementTitle && inactiveElementNumber) {
                        inactiveElementTitle.style.color = ''; // Remove a cor do texto
                        inactiveElementNumber.style.color = ''; // Remove a cor do texto
                    }
                }
            }

            <?php if($playlist['type']==='video') :?>

                $.ajax({
                    url: '/artuniverse/actions/getComments.php',
                    type: 'POST',
                    data: {'postId': currentVideo.id_post}, // Enviando o ID do post atual
                    dataType: 'json',
                    success: function(data) {
                        document.getElementById("countCommentPost").innerText = data.length + ' comments';
                    },
                    error: function() {
                        alert("error");
                    }
                });

                //------------------------//

                $.ajax({
                    url: '/artuniverse/actions/getLikes.php',
                    type: 'POST',
                    data: {'postId': currentVideo.id_post}, // Enviando o ID do post atual
                    dataType: 'json',
                    success: function(data) {
                        document.getElementById("countLikePost").innerText = data.length + ' likes';
                    },
                    error: function() {
                        alert("error");
                    }
                });

            <?php endif ?>
        });
    }



    document.addEventListener('DOMContentLoaded', () => {
        initPlayer();
        <?php if($playlist['type'] === 'video') :?>
            chekedButton();
        <?php endif ?>

    });

    function getNextIndex() {
        // Se chegamos ao fim da playlist e o loop está ativado, reiniciamos do começo
        if (currentIndex === playlistVideos.length - 1 && loopActivated) {
            return 0;
        } else {
            // Caso contrário, avançamos para o próximo vídeo
            return currentIndex + 1;
        }
    }

    <?php if($playlist['type'] === 'video') :?>
        function chekedButton() {
            const article = document.querySelector(".feature1");
            if (document.getElementById('feature1').checked) {
                article.classList.add("checked");
                loopActivated = true; // Ativa o loop quando o checkbox está marcado
                player.on('ended', playNextVideo);
            } else {
                article.classList.remove("checked");
                loopActivated = false; // Desativa o loop quando o checkbox não está marcado
            }
        }
    <?php else: ?>
        function chekedButton() {
            const article = document.querySelector(".iconRepeat");
            if (document.getElementById('feature1').checked) {
                article.classList.add("checked");
                loopActivated = true; // Ativa o loop quando o checkbox está marcado
                player.on('ended', playNextVideo);
            } else {
                article.classList.remove("checked");
                loopActivated = false; // Desativa o loop quando o checkbox não está marcado
            }
        }
    <?php endif ?>

    function randomizePlaylist() {
        // Embaralha a lista de vídeos
        playlistVideos = shuffle(playlistVideos);

        // Atualiza o índice do vídeo atual para o primeiro da nova ordem aleatória
        // currentIndex = 0;

        // Carrega e reproduz o primeiro vídeo da nova ordem aleatória
        loadVideo(currentIndex);
        if (document.getElementById('feature2').checked) {
            document.querySelector(".feature2").classList.add("checked");
        } else {
            document.querySelector(".feature2").classList.remove("checked");
        }
    }

    function shuffle(array) {
        let currentIndex = array.length,
            randomIndex;

        // Enquanto ainda houver elementos para embaralhar
        while (currentIndex !== 0) {
            // Escolhe um elemento restante aleatoriamente
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;

            // E troca com o elemento atual
            [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
        }

        return array;
    }

    <?php if($playlist['type'] === 'video') :?>

        document.getElementById('feature1').onclick = () => {
            chekedButton();
        };

        document.getElementById('feature2').onclick = () => {
            randomizePlaylist();
            document.querySelector(".playButtonPlaylist").style.display = 'none' ;
        };
    <?php else: ?>
        document.getElementById('feature1').onclick = () => {
            chekedButton();
        };

        document.getElementById('feature2').onclick = () => {
            randomizePlaylist();
        };
    <?php endif ?>



    function playVideo(index) {
        currentIndex = index;
        const video = playlistVideos[currentIndex];
        player.source = {
            type: '<?=$playlist['type'] === 'video' ? 'video' : 'audio'?>',
            sources: [{
                src: `/artuniverse/public/storage/<?=$playlist['type'] === 'video' ? 'videos' : 'audios'?>/${video.file}`,
                type: '<?=$playlist['type'] === 'video' ? 'video/mp4' : 'audio/mp3'?>',
            }],
        };
        player.play();
        <?php if($playlist['type'] === 'video') :?>
            document.querySelector(".viewAreaPlaylist").style.display = 'flex';
        <?php endif ?>
    }

    function loadVideo(index) {
        currentIndex = index;
        const video = playlistVideos[currentIndex];
        player.source = {
            type: '<?=$playlist['type'] === 'video' ? 'video' : 'audio'?>',
            sources: [{
                src: `/artuniverse/public/storage/<?=$playlist['type'] === 'video' ? 'videos' : 'audios'?>/${video.file}`,
                type: '<?=$playlist['type'] === 'video' ? 'video/mp4' : 'audio/mp3'?>',
            }],
        };

        player.play()
        <?php if($playlist['type'] === 'video') :?>
            document.querySelector(".viewAreaPlaylist").style.display = 'flex';
        <?php endif ?>
    }



    function playNextVideo() {
        // Obtemos o próximo índice com base na repetição da playlist
        currentIndex = getNextIndex();

        if (currentIndex < playlistVideos.length) {
            // Carregar e reproduzir o próximo vídeo se ainda houver vídeos na playlist
            loadVideo(currentIndex);
        } else {
            // Se chegamos ao final da playlist, pausamos o player e reiniciamos o índice
            player.stop();
            currentIndex = 0;
            <?php if($playlist['type'] === 'video') :?>
                document.querySelector(".playButtonPlaylist").style.display = 'block'; // Mostra o botão "Play" novamente quando a playlist terminar
            <?php endif ?>
        }
    }

    document.getElementById("deletePlaylist").onclick = () =>{
        window.location.href = "<?=$base?>/actions/deletePlaylist.php?id_name=<?=$playlist['id_name']?>";
    }

    


    <?php if($playlist['type'] === 'audio') :?>       

        function myFunc(){
            loadVideo(currentIndex);
            myFunc = function(){};
        };

        document.getElementById("playAudioButton").onclick = () =>{
            document.getElementById("playAudioButton").style.display = 'none';
            document.getElementById("pauseAudioButton").style.display = 'flex';
            var playPromise = player.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {})
                .catch(error => {});

            }
        }

        document.getElementById("pauseAudioButton").onclick = () =>{
            document.getElementById("playAudioButton").style.display = 'flex';
            document.getElementById("pauseAudioButton").style.display = 'none';
            player.pause();
        }

        const icon = document.querySelector("#icon"),
        range = document.querySelector("#inputVolume")

        range.addEventListener("input", () => {
            let rangeVal = range.value;
            if (rangeVal < 1) {
                icon.classList.replace("fa-volume-low", "fa-volume-xmark");
            } else {
                icon.classList.replace("fa-volume-xmark", "fa-volume-low");
            }
            if (rangeVal > 0) {
                icon.classList.replace("fa-volume-xmark", "fa-volume-low");
            } else {
                icon.classList.replace("fa-volume-low", "fa-volume-xmark");
            }
            if (rangeVal > 50) {
                icon.classList.replace("fa-volume-low", "fa-volume-high");
            } else {
                icon.classList.replace("fa-volume-high", "fa-volume-low");
            }
        });

    <?php endif ?>
</script>


<?php require_once '../partials/footer.php' ?>
