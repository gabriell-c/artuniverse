<?php
    require_once '../../config.php';
    require_once '../../partials/header.php';

    if(isset($_SESSION['warning'])){
		require_once '../../modalAlert.php';
		unset($_SESSION['warning']);
	}

?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const player = new Plyr('.video-preview');
  });
  </script>

<section style="display: flex; width: 100%;" >
    <?php require_once '../../sidebar.php' ?>
    <div class="ContainerAreaUploadVideo">
        <div class="containerUploadVideo">
            <h5 class="titleUplaodVideoPage">Upload video</h5>
            <form enctype="multipart/form-data" method="post" action="../../actions/uploadVideo.php" class="bodyUploadVideo" >
                <label for="title">Title:</label>
                <input required placeholder="Create a title" type="text" maxlength="100" name="title" required class="inputFormUpload">

                <label for="description">Description:</label>
                <textarea  type="text" name="description" maxlength="2500" class="inputFormUpload"></textarea>

                <label for="tags">Tags:</label>
                <input type="text" id="tags" data-role="taginput" name="tags" data-max-tags="5">

                <!-- <input type="file" name="video_file" accept="video/*"> -->

                <div class="input-file-container">  
                    <input required name="video_file" class="input-file" id="my-file" type="file" accept="video/*">
                    <label tabindex="0" for="my-file" class="input-file-trigger">Choose File</label>
                </div>

                <div id="input-poster-area" class="input-file-poster-container">  
                    <input onchange="exibirImagem(this)" accept="image/*" name="poster_video_file" class="input-file-poster" id="my-file-poster" type="file">
                    <label tabindex="0" for="my-file-poster" class="input-file-trigger-poster">Choose cover video</label>
                </div>

                <video id="video-preview" class="video-preview" controls></video>
                <div id="containerPreview" class="preview_container">
                    <img id="previewImagemV" class="video_preview_image">
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



                <?php
                    if(isset($_SERVER['HTTP_REFERER'])) {
                        $paginaAnterior = $_SERVER['HTTP_REFERER'];
                    } else {
                        $paginaAnterior = $base;
                    }
                ?>

                <div style="margin: 1em auto;" class="buttonArea">
                    <a href="<?=$paginaAnterior?>" style="margin: 0 1em;" class="btn btn-danger" >Cancel</a>
                    <button type="submit" class="btnPublic">publish</button>
                </div>
            </form> 
        </div>
    </div>
</section>

<script>
    document.getElementById('my-file').addEventListener('change', function(e) {
      var file = e.target.files[0];
      var videoPlayer = document.getElementById('video-preview');

      var fileURL = URL.createObjectURL(file);
      videoPlayer.src = fileURL;
      videoPlayer.style.maxHeight = "100%";
      videoPlayer.style.visibility = "visible"
      document.getElementById('input-poster-area').style.display = "block"
    });


    document.getElementById('my-file-poster').addEventListener('change', function(e) {
      var file = e.target.files[0];
      var videoPlayer = document.getElementById('video-preview');

      var fileURL = URL.createObjectURL(file);
      videoPlayer.poster = fileURL;
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