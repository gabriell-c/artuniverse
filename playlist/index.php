<?php
    require_once '../config.php';
    if(!isset($_SESSION['user'])){
        header("Location: ".$base);
        exit;
    }
    require_once '../partials/header.php';
    require_once '../loadingelement.php';

    if(isset($_SESSION['warning'])){
		require_once '../modalAlert.php';
		unset($_SESSION['warning']);
	}

    $sqlAP = "SELECT * FROM allplaylist WHERE user_name = :id_user AND type = 'video'";
    $sqlAP = $pdo->prepare($sqlAP);
    $sqlAP->bindValue(':id_user', $_SESSION['user']['user_name']);
    $sqlAP->execute();
    $videosPlaylist = $sqlAP->fetchAll(PDO::FETCH_ASSOC);

    //------------------//

    $sqlPM = "SELECT * FROM allplaylist WHERE user_name = :id_user AND type = 'audio'";
    $sqlPM = $pdo->prepare($sqlPM);
    $sqlPM->bindValue(':id_user', $_SESSION['user']['user_name']);
    $sqlPM->execute();
    $audiosPlaylist = $sqlPM->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    .liPlaylistItem:hover{
        background: var(--color3);

    }

    .liPlaylistItem{
        overflow: visible !important;
        border-radius: 10px;
    }

    .d-menu{
        right: 0;
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
</style>


<section style="display: flex; width: 100%;">
    <?php require_once '../sidebar.php'?>
    <div class="mainPlaylistArea">
        <div class="headerPlaylistArea">
            <h2>Playlist</h2>
            <div class="buttonPlaylistArea">
                <button id="btnSelectTypeVideo" onclick="showVideo()" class="activeButton"><i class="fa-solid fa-play"></i>video</button>
                <button id="btnSelectTypeAudio" onclick="showAudio()" class="buttonSelectPlaylistType" ><i class="fa-solid fa-music"></i>music</button>
            </div>
        </div>

        <div class="videoPlaylistArea">

            <ul class="items-list" style="width: 100%;">
                <?php for($i = 0; $i < count($videosPlaylist); $i++): ?>

                    <?php
                        $sqlCV = "SELECT * FROM playlist WHERE id_name = :id_name AND type = 'video'";
                        $sqlCV = $pdo->prepare($sqlCV);
                        $sqlCV->bindValue(':id_name', $videosPlaylist[$i]['id_name']);
                        $sqlCV->execute();
                        $allVideosPlaylist = $sqlCV->fetchAll(PDO::FETCH_ASSOC);    
                    ?>

                    <li class="liPlaylistItem">
                    <span class="material-symbols-outlined" style="position: absolute; color: var(--color5);">playlist_play</span>
                        <div id="playlistItem<?=$videosPlaylist[$i]['id']?>" style="margin-left: 30px;">
                            <img style="border-radius: 0;" class="avatar" src="<?= $videosPlaylist[$i]['poster'] ? $base.'/public/storage/posterPlaylist/'.$videosPlaylist[$i]['poster'] : $base.'/public/img/saturn_background.jpg'?>">
                            <h3 style="color: var(--color5); margin-left: 20px; font-size: 18px; max-width: 95%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="label"><?=$videosPlaylist[$i]['playlist_name']?></h3>
                            <span style="margin-left: 20px;" class="second-label"><strong>@<?=$videosPlaylist[$i]['user_name']?></strong> | <?=count($allVideosPlaylist) > 1 ? count($allVideosPlaylist).' Videos' : count($allVideosPlaylist).' Video'?>  - <?=$videosPlaylist[$i]['privacy'] === 'private' ? '<i class="fa-solid fa-lock"></i>' : '<i class="fa-solid fa-earth-americas"></i>'?></span>
                        </div>
                        <div style="position: initial;" >
                            <span class="second-action mif-more-vert fg-white"></span>
                            <ul class="d-menu" data-role="dropdown">
                                <li onclick="copyLink('<?=$base?>/playlist/<?=$videosPlaylist[$i]['id_name']?>'); runToast('timeout')" ><a disabled><i class="fa-solid fa-link"></i>Copy link</a></li>
                                <li class="divider"></li>
                                <?php if(isset($_SESSION['user']) && isset($_SESSION['user']['user_name']) == $videosPlaylist[$i]['user_name']): ?>
                                    <li data-video="edit" data-toggle="modal" data-target="#edit<?=$videosPlaylist[$i]['id']?>"><a disabled><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                    <li><a href="#"><i class="fa-solid fa-trash"></i>Delete</a></li>
                                <?php endif ?>
                            </ul>
                        </div>
                    </li>
                    <hr style="width: 100%; opacity: .1;" >

                    <form method="post" enctype="multipart/form-data" action="/artuniverse/actions/updatePlaylist.php" class="modal fade" id="edit<?=$videosPlaylist[$i]['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        <p id="file-return<?=$videosPlaylist[$i]['id']?>" class="file-return"><?=$videosPlaylist[$i]['poster'] != '' ? $videosPlaylist[$i]['poster'] : ''?></p>
                                    </div>

                                    <input type="hidden" name="id_name_playlist" value="<?=$videosPlaylist[$i]['id_name']?>">

                                    <div style="margin: 1em 0;">
                                        <label style="color: var(--color5);" for="titleEdit">Title</label>
                                        <input placeholder="Edit title" id="titleEdit" name="title_playlist" class="inputEditPlaylist" required type="text" <?=$videosPlaylist[$i]['playlist_name'] ? 'value="'.$videosPlaylist[$i]['playlist_name'].'"' : ''?>>
                                    </div>

                                    <div style="margin: 1em 0;">
                                        <?php if($videosPlaylist[$i]['privacy'] == 'private') :?>
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
                    <script>
                        document.getElementById("playlistItem<?=$videosPlaylist[$i]['id']?>").onclick=()=>{
                            window.location.href = '<?=$base?>/playlist/<?=$videosPlaylist[$i]['id_name']?>';
                        }
                        document.querySelector( ".input-file-trigger" ).addEventListener( "keydown", function( event ) {  
                            if ( event.keyCode == 13 || event.keyCode == 32 ) {  
                                document.querySelector( ".input-file-cover-playlist" ).focus();  
                            }  
                        });
                        document.querySelector( ".input-file-trigger" ).addEventListener( "click", function( event ) {
                            document.querySelector( ".input-file-cover-playlist" ).focus();
                            return false;
                        });  
                        document.querySelector( ".input-file-cover-playlist" ).addEventListener("change", function(event) {
                            // Acesse o arquivo selecionado usando a propriedade `files`
                            if (event.target.files[0]) {
                                // Faça algo com o arquivo selecionado, como exibir o nome
                                document.getElementById("file-return<?=$videosPlaylist[$i]['id']?>").innerText = event.target.files[0].name;
                            } else {
                                // Lide com o caso em que nenhum arquivo é selecionado
                                document.getElementById("file-return<?=$videosPlaylist[$i]['id']?>").innerText = "Nenhum arquivo selecionado";
                            }
                        });

                    </script>
                <?php endfor ?>
            </ul>

        </div>



        <!--///////////////////////////////////////-->

        <div class="audioPlaylistArea" style="display: none;">

            <ul class="items-list" style="width: 100%;">
                <?php for($i = 0; $i < count($audiosPlaylist); $i++): ?>

                    <?php
                        $sqlC = "SELECT * FROM playlist WHERE id_name = :id_name AND type = 'audio'";
                        $sqlC = $pdo->prepare($sqlC);
                        $sqlC->bindValue(':id_name', $audiosPlaylist[$i]['id_name']);
                        $sqlC->execute();
                        $allAudioPlaylist = $sqlC->fetchAll(PDO::FETCH_ASSOC);    
                    ?>

                    <li class="liPlaylistItem">
                    <span class="material-symbols-outlined" style="position: absolute; color: var(--color5);">playlist_play</span>
                        <div id="playlistItem<?=$audiosPlaylist[$i]['id']?>" style="margin-left: 30px;">
                            <img style="border-radius: 0;" class="avatar" src="<?= $audiosPlaylist[$i]['poster'] ? $base.'/public/storage/posterPlaylist/'.$audiosPlaylist[$i]['poster'] : $base.'/public/img/saturn_background.jpg'?>">
                            <h3 style="color: var(--color5); margin-left: 20px; font-size: 18px; max-width: 95%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="label"><?=$audiosPlaylist[$i]['playlist_name']?></h3>
                            <span style="margin-left: 20px;" class="second-label"><strong>@<?=$audiosPlaylist[$i]['user_name']?></strong> | <?=count($allAudioPlaylist) > 1 ? count($allAudioPlaylist).' Songs' : count($allAudioPlaylist).' Music'?>  - <?=$audiosPlaylist[$i]['privacy'] === 'private' ? '<i class="fa-solid fa-lock"></i>' : '<i class="fa-solid fa-earth-americas"></i>'?></span>
                        </div>
                        <div style="position: initial;" >
                            <span class="second-action mif-more-vert fg-white"></span>
                            <ul class="d-menu" data-role="dropdown">
                                <li onclick="copyLink('<?=$base?>/playlist/<?=$audiosPlaylist[$i]['id_name']?>'); runToast('timeout')" ><a disabled><i class="fa-solid fa-link"></i>Copy link</a></li>
                                <li class="divider"></li>
                                <?php if(isset($_SESSION['user']) && isset($_SESSION['user']['user_name']) == $audiosPlaylist[$i]['user_name']): ?>
                                    <li data-video="edit" data-toggle="modal" data-target="#edit<?=$audiosPlaylist[$i]['id']?>"><a disabled><i class="fa-solid fa-pen-to-square"></i>Edit</a></li>
                                    <li id="deletePlaylsit<?=$audiosPlaylist[$i]['id']?>" data-id="<?=$audiosPlaylist[$i]['id']?>"><a disabled><i class="fa-solid fa-trash"></i>Delete</a></li>
                                <?php endif ?>
                            </ul>
                        </div>
                    </li>
                    <hr style="width: 100%; opacity: .1;" >

                    <form method="post" enctype="multipart/form-data" action="/artuniverse/actions/updatePlaylist.php" class="modal fade" id="edit<?=$audiosPlaylist[$i]['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        <p id="file-return<?=$audiosPlaylist[$i]['id']?>" class="file-return"><?=$audiosPlaylist[$i]['poster'] != '' ? $audiosPlaylist[$i]['poster'] : ''?></p>
                                    </div>

                                    <input type="hidden" name="id_name_playlist" value="<?=$audiosPlaylist[$i]['id_name']?>">

                                    <div style="margin: 1em 0;">
                                        <label style="color: var(--color5);" for="titleEdit">Title</label>
                                        <input placeholder="Edit title" id="titleEdit" name="title_playlist" class="inputEditPlaylist" required type="text" <?=$audiosPlaylist[$i]['playlist_name'] ? 'value="'.$audiosPlaylist[$i]['playlist_name'].'"' : ''?>>
                                    </div>

                                    <div style="margin: 1em 0;">
                                        <?php if($audiosPlaylist[$i]['privacy'] == 'private') :?>
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
                    <script>
                        document.getElementById("playlistItem<?=$audiosPlaylist[$i]['id']?>").onclick=()=>{
                            window.location.href = '<?=$base?>/playlist/<?=$audiosPlaylist[$i]['id_name']?>';
                        }
                        document.querySelector( ".input-file-trigger" ).addEventListener( "keydown", function( event ) {  
                            if ( event.keyCode == 13 || event.keyCode == 32 ) {  
                                document.querySelector( ".input-file-cover-playlist" ).focus();  
                            }  
                        });
                        document.querySelector( ".input-file-trigger" ).addEventListener( "click", function( event ) {
                            document.querySelector( ".input-file-cover-playlist" ).focus();
                            return false;
                        });  
                        document.querySelector( ".input-file-cover-playlist" ).addEventListener("change", function(event) {
                            // Acesse o arquivo selecionado usando a propriedade `files`
                            if (event.target.files[0]) {
                                // Faça algo com o arquivo selecionado, como exibir o nome
                                document.getElementById("file-return<?=$audiosPlaylist[$i]['id']?>").innerText = event.target.files[0].name;
                            } else {
                                // Lide com o caso em que nenhum arquivo é selecionado
                                document.getElementById("file-return<?=$audiosPlaylist[$i]['id']?>").innerText = "Nenhum arquivo selecionado";
                            }
                        });

                        document.getElementById("deletePlaylsit<?=$audiosPlaylist[$i]['id']?>").onclick = () =>{
                            window.location.href = "<?=$base?>/actions/deletePlaylist.php?id_name=<?=$audiosPlaylist[$i]['id_name']?>";
                        }

                    </script>
                <?php endfor ?>
            </ul>

        </div>
    </div>
</section>

<script>
 
    document.title = "Playlist | Artuniverse";
  
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


    //----------------------------//

    function showVideo(){
        document.querySelector(".videoPlaylistArea").style.display = 'flex';
        document.querySelector(".audioPlaylistArea").style.display = 'none';
        document.getElementById("btnSelectTypeVideo").classList.add("activeButton")
        document.getElementById("btnSelectTypeAudio").classList.remove("activeButton")
    }
    function showAudio(){
        document.querySelector(".videoPlaylistArea").style.display = 'none';
        document.querySelector(".audioPlaylistArea").style.display = 'flex';
        document.getElementById("btnSelectTypeVideo").classList.remove("activeButton")
        document.getElementById("btnSelectTypeAudio").classList.add("activeButton")
    }
</script>




<?php

    require_once '../partials/footer.php';

?>