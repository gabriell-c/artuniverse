<?php
    require_once '../../config.php';
    require_once '../../partials/header.php';

    if(isset($_SESSION['warning'])){
		require_once '../../modalAlert.php';
		unset($_SESSION['warning']);
	}

?>

<style>

    .track{
        display: none;
        align-items: center;
        margin-top: 20px;
    }
    .track img{
        width: 50px;
        margin-right: 10px;
        cursor: pointer;
    }

    .track div{
        flex: 1;
    }
</style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../../sidebar.php' ?>
    <div class="ContainerAreaUploadVideo">
        <div class="containerUploadVideo">
            <h5 class="titleUplaodVideoPage">Upload music</h5>
            <form enctype="multipart/form-data" method="post" action="../../actions/uploadAudio.php" class="bodyUploadVideo" >
                <label for="title">Title:</label>
                <input required placeholder="Create a title" type="text" maxlength="100" name="title" required class="inputFormUpload">

                <label for="description">Description:</label>
                <textarea  type="text" name="description" maxlength="2500" class="inputFormUpload"></textarea>

                <label for="tags">Tags:</label>
                <input type="text" id="tags" data-role="taginput" name="tags" data-max-tags="5">

                <div id="chooseMusicArea" class="input-file-container">  
                    <input style="z-index: 1;" required name="audio_file" class="input-file" id="inputMusica" type="file"  accept="audio/*" onchange="exibirMusica()">
                    <label tabindex="0" for="my-file" id="labelChosse" class="input-file-trigger"><i class="fa-solid fa-music"></i>Choose File</label>
                </div>

                <div id="trackUpload" class="track">
                    <img src="<?=$base?>/public/img/play-button.png" id="playBtn" >
                    <div id="waveform"></div>
                    <i class="fa-solid fa-xmark clear_selection" onclick="limparMusica()"></i>
                </div>

                <div id="posterMusic" class="input-file-container">  
                    <input  class="input-file" type="file" id="poster_audio_file" name="poster_audio_file" accept="image/png, image/jpeg" onchange="exibirImagem(this)">
                    <label tabindex="0" class="input-file-trigger" for="poster_audio_file"><i class="fa-solid fa-image"></i>Choose File Poster</label>
                </div>

                <div id="iamgePreviewArea" class="image_preview_area">
                    <img id="previewImagem" class="image_preview">
                    <div id="iconClearPosterAudio" class="clearpreviewPoster">
                        <!-- From Uiverse.io by vinodjangid07 --> 
                    <div onclick="clearPoster()" class="cleardeleteButton">
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
    const wavesurfer = WaveSurfer.create({
        container: '#waveform',
        waveColor: '#555',
        progressColor: '#9673ff',
        barWidth: 1,
        resposive: true,
        height: 50,
        barRadius: 4
    })

    const labelC = document.getElementById("labelChosse");
    const posterM = document.getElementById("posterMusic");
    const ipa = document.getElementById("iamgePreviewArea");

    posterM.style.display = 'none'
    ipa.style.display = 'none'


    function exibirMusica() {
        document.getElementById("trackUpload").style.display = 'flex';
        var playBtn = document.getElementById('playBtn');


        wavesurfer.load(URL.createObjectURL(document.getElementById('inputMusica').files[0]));

        playBtn.onclick = function(){
            wavesurfer.playPause();
            if(playBtn.src.includes("<?=$base?>/public/img/play-button.png")){
                playBtn.src = "<?=$base?>/public/img/pause.png";
            }else{
                playBtn.src = "<?=$base?>/public/img/play-button.png";
            }
        }

        wavesurfer.on('finish', function () {
            playBtn.src = "<?=$base?>/public/img/play-button.png";
        });
        document.getElementById('chooseMusicArea').style.display ='none';

        posterM.style.display = 'flex'

    }




    function exibirImagem(input) {
        if (input.files && input.files[0]) {
            var leitor = new FileReader();
            
            leitor.onload = function(e) {
            document.getElementById('previewImagem').setAttribute('src', e.target.result);
            document.getElementById('previewImagem').style.display = 'block';
            document.getElementById('previewImagem').style.maxHeight= '100%';
            document.getElementById('previewImagem').style.visibility = 'visible';
            document.getElementById('previewImagem').style.width = '100%';
            //   document.getElementById('previewImagem').style.display = 'block';
            
            };
            
            leitor.readAsDataURL(input.files[0]);
            ipa.style.display = 'flex'

        }
    }

    function limparMusica() {
        document.getElementById('inputMusica').value = ""; // Limpa o input
        document.getElementById('poster_audio_file').value = ""; // Limpa o input
        document.getElementById("trackUpload").style.display = 'none'; // Esconde o player
        document.getElementById('previewImagem').setAttribute('src', ''); 

        if (wavesurfer) {
            wavesurfer.empty(); // Limpa a waveform sem destruir
            playBtn.src = "<?=$base?>/public/img/play-button.png";
            document.getElementById('chooseMusicArea').style.display ='flex';
            document.getElementById('inputMusica').disabled = false;
            posterM.style.display = 'none'
            ipa.style.display = 'none'
        }
    }

    function clearPoster() {
        document.getElementById('poster_audio_file').value = ""; // Limpa o input
        document.getElementById('previewImagem').setAttribute('src', ''); 
        ipa.style.display = 'none'
    }



</script>


<?php

    require_once '../../partials/footer.php';

?>