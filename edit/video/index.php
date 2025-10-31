<?php
    require_once '../../config.php';
    require_once '../../loadingelement.php';
    if (!isset($_SESSION['user'])) {
        header('Location: '.$base);
        exit;
    }
    require_once '../../partials/header.php';

    if(isset($_SESSION['warning'])){
		require_once '../../modalAlert.php';
		unset($_SESSION['warning']);
	}


    $sqlP = "SELECT * FROM allposts WHERE archive = :archiveType AND id_name = :id_content";
    $stmt = $pdo->prepare($sqlP);
    $stmt->bindValue(':archiveType', 'false');
    $stmt->bindValue(':id_content', $_GET['id_content']);
    $stmt->execute();

    $content = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$content) {
        require_once '../../not_found.php';
        exit;     
    }

?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const player = new Plyr('.video-preview');
  });
  </script>

  <style>
    .plyr{
        height: auto !important;
        margin-bottom: 30px;
    }

    video{
        height: auto !important;
    }

    .plyr__video-wrapper{
        height: auto !important;
    }
  </style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../../sidebar.php' ?>
    <div class="ContainerAreaUploadVideo">
        <div class="containerUploadVideo">
            <h5 class="titleUplaodVideoPage">Edit video</h5>
            <form enctype="multipart/form-data" method="post" action="../../actions/editVideo.php" class="bodyUploadVideo" >
                <label for="title">Title:</label>
                <input required placeholder="Edit a title" value="<?=$content['title']?>" type="text" maxlength="100" name="title" required class="inputFormUpload">

                <label for="description">Description:</label>
                <textarea placeholder="Edit a description" type="text" name="description" maxlength="2500" class="inputFormUpload"><?=$content['description']?></textarea>

                <label for="tags">Tags:</label>
                <input type="text" id="tags" data-role="taginput" name="tags" data-max-tags="5" value="<?=$content['tags']?>" placeholder="Add tags (max 5)" maxlength="50">


                <video <?= isset($content['poster']) && $content['poster'] !== '' ? 'poster="'.$base.'/public/storage/posterVideo/'.$content['poster'].'"' : ''?> id="video-preview" class="video-preview" style="visibility: visible; max-height: 100%"  controls>
                    <source src="<?=$base?>/public/storage/videos/<?=$content['file']?>" type="video/mp4">
                </video>
                
                <div id="input-poster-area" style="display:flex" class="input-file-poster-container">  
                    <input onchange="exibirImagem(this)"  accept="image/*"  name="poster_video_file" class="input-file-poster" id="my-file-poster" type="file">
                    <label tabindex="0" for="my-file-poster" class="input-file-trigger-poster">Choose cover video</label>
                </div>

                <?php if($content['poster']  !== null && $content['poster'] !== ''):?>
                    <div id="containerPreview" style="width: 100%; visibility: visible; max-height: 100%" class="preview_container">
                        <img id="previewImagemV" src="<?=$base?>/public/storage/posterVideo/<?=$content['poster']?>" class="video_preview_image">
                        <i class="fa-solid fa-photo-film play_icon"></i>
                        <div style="position:absolute; top: 10px; right: 10px" onclick="clearPoster()" class="cleardeleteButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 50 59" class="clearbin" >
                                <path fill="#B5BAC1"
                                d="M0 7.5C0 5.01472 2.01472 3 4.5 3H45.5C47.9853 3 50 5.01472 50 7.5V7.5C50 8.32843 49.3284 9 48.5 9H1.5C0.671571 9 0 8.32843 0 7.5V7.5Z"
                                ></path>
                                <path fill="#B5BAC1"
                                d="M17 3C17 1.34315 18.3431 0 20 0H29.3125C30.9694 0 32.3125 1.34315 32.3125 3V3H17V3Z" ></path>
                                <path fill="#B5BAC1"
                                d="M2.18565 18.0974C2.08466 15.821 3.903 13.9202 6.18172 13.9202H43.8189C46.0976 13.9202 47.916 15.821 47.815 18.0975L46.1699 55.1775C46.0751 57.3155 44.314 59.0002 42.1739 59.0002H7.8268C5.68661 59.0002 3.92559 57.3155 3.83073 55.1775L2.18565 18.0974ZM18.0003 49.5402C16.6196 49.5402 15.5003 48.4209 15.5003 47.0402V24.9602C15.5003 23.5795 16.6196 22.4602 18.0003 22.4602C19.381 22.4602 20.5003 23.5795 20.5003 24.9602V47.0402C20.5003 48.4209 19.381 49.5402 18.0003 49.5402ZM29.5003 47.0402C29.5003 48.4209 30.6196 49.5402 32.0003 49.5402C33.381 49.5402 34.5003 48.4209 34.5003 47.0402V24.9602C34.5003 23.5795 33.381 22.4602 32.0003 22.4602C30.6196 22.4602 29.5003 23.5795 29.5003 24.9602V47.0402Z"
                                clip-rule="evenodd" fill-rule="evenodd"
                                ></path>
                                <path fill="#B5BAC1" d="M2 13H48L47.6742 21.28H2.32031L2 13Z"></path>
                            </svg>

                            <span class="cleartooltip">Delete</span>
                        </div>
                    </div>
                <?php endif ?>



                <?php
                    if(isset($_SERVER['HTTP_REFERER'])) {
                        $paginaAnterior = $_SERVER['HTTP_REFERER'];
                    } else {
                        $paginaAnterior = $base;
                    }
                ?>

                <div style="margin: 1em auto;" class="buttonArea">
                    <a href="<?=$paginaAnterior?>" style="margin: 0 1em;" class="btn btn-danger" >Cancel</a>
                    <!-- campo oculto com o ID do vídeo -->
                    <input type="hidden" name="id_name" value="<?= htmlspecialchars($_GET['id_content']) ?>">
                    <button type="submit" class="btnPublic">Save</button>
                </div>
            </form> 
        </div>
    </div>
</section>

<script>

    let poster_plyr = document.querySelector('.plyr__poster');
    document.getElementById('my-file-poster').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var videoPlayer = document.getElementById('video-preview');

        var fileURL = URL.createObjectURL(file);
        videoPlayer.poster = fileURL;
        videoPlayer.setAttribute('data-poster', fileURL); // Atualiza o data-poster também
    const poster_plyr = videoPlayer.closest('.plyr').querySelector('.plyr__poster');

    if (poster_plyr) {
        poster_plyr.style.setProperty('background-image', `url(${fileURL})`, 'important');
    }        
    });




    function clearPoster() {
        document.getElementById('my-file-poster').calue = '';
        document.getElementById('containerPreview').style.display = 'none';
        document.getElementById('containerPreview').style.maxHeight= '0';
        document.getElementById('containerPreview').style.visibility = 'hidden';
        document.getElementById('containerPreview').style.width = '0';
        document.getElementById('containerPreview').style.marginTop = '0';
        document.getElementById('previewImagemV').setAttribute('src', '');
        // document.getElementById('input-poster-area').style.display = 'flex';

    }

    function exibirImagem(input) {
        if (input.files && input.files[0]) {
            var leitor = new FileReader();

            leitor.onload = function(e) {
            document.getElementById('previewImagemV').setAttribute('src', e.target.result);
            document.getElementById('containerPreview').style.display = 'inline-block';
            document.getElementById('containerPreview').style.maxHeight= '100%';
            document.getElementById('containerPreview').style.visibility = 'visible';
            document.getElementById('containerPreview').style.width = '100%';
            document.getElementById('containerPreview').style.marginTop = '35px';

            //   document.getElementById('previewImagem').style.display = 'block';
            
            };
            leitor.readAsDataURL(input.files[0]);
        }
        // document.getElementById('input-poster-area').style.display = 'none';

    }
  </script>


<?php

    require_once '../../partials/footer.php';

?>