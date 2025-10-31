<?php
require_once '../../config.php';
require_once '../../loadingelement.php';
$id = $_GET['id_content'];
if (!$id) {
    require_once '../../not_found.php';
    exit;
}

// Busca o áudio do usuário
$sql = "SELECT * FROM allposts WHERE archive = 'false' AND id_name = :id_name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id_name', $id);
$stmt->execute();
$audio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user']) || $_SESSION['user']['user_name'] !== $audio['user_name']) {
    header('Location: '.$base);
    exit;
}


require_once '../../partials/header.php';

if (!$audio) {
    require_once '../../not_found.php';
    exit;
}

if(isset($_SESSION['warning'])){
	require_once '../../modalAlert.php';
	unset($_SESSION['warning']);
}
?>

<style>
.track {
    display: flex;
    align-items: center;
    margin-top: 20px;
}
.track img {
    width: 50px;
    margin-right: 10px;
    cursor: pointer;
}
.track div {
    flex: 1;
}
.image_preview_area {
    margin-top: 1em;
    display: flex;
    flex-direction: column;
    gap: 0.5em;
}
.cleardeleteButton {
    cursor: pointer;
    color: red;
    font-size: 14px;
}
</style>

<section style="display: flex; width: 100%;">
    <?php require_once '../../sidebar.php'; ?>
    <div class="ContainerAreaUploadVideo">
        <div class="containerUploadVideo">
            <h5 class="titleUplaodVideoPage">Edit music</h5>
            <form enctype="multipart/form-data" method="post" action="../../actions/editAudio.php" class="bodyUploadVideo">
                <input type="hidden" name="id_name" value="<?= htmlspecialchars($id) ?>">

                <label for="title">Title:</label>
                <input required placeholder="Edit title" type="text" maxlength="100" name="title" value="<?= htmlspecialchars($audio['title']) ?>" class="inputFormUpload">

                <label for="description">Description:</label>
                <textarea name="description" maxlength="2500" class="inputFormUpload"><?= htmlspecialchars($audio['description']) ?></textarea>

                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" value="<?= htmlspecialchars($audio['tags']) ?>" data-role="taginput" data-max-tags="5">

                <div class="track">
                    <img src="<?= $base ?>/public/img/play-button.png" id="playBtn" >
                    <div id="waveform"></div>
                </div>

                <script src="https://unpkg.com/wavesurfer.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const wavesurfer = WaveSurfer.create({
                            container: '#waveform',
                            waveColor: '#555',
                            progressColor: '#9673ff',
                            barWidth: 1,
                            responsive: true,
                            height: 50,
                            barRadius: 4
                        });

                        wavesurfer.load("<?= $base ?>/public/storage/audios/<?= $audio['file'] ?>");

                        const playBtn = document.getElementById('playBtn');
                        playBtn.onclick = function() {
                            wavesurfer.playPause();
                            playBtn.src = wavesurfer.isPlaying()
                                ? "<?= $base ?>/public/img/pause.png"
                                : "<?= $base ?>/public/img/play-button.png";
                        };

                        wavesurfer.on('finish', function () {
                            playBtn.src = "<?= $base ?>/public/img/play-button.png";
                        });
                    });
                </script>

                <div id="posterMusic" class="input-file-container">  
                    <input  class="input-file" type="file" id="poster_audio_file" name="poster_audio_file" accept="image/png, image/jpeg" onchange="exibirImagem(this)">
                    <label tabindex="0" class="input-file-trigger" for="poster_audio_file"><i class="fa-solid fa-image"></i>Choose File Poster</label>
                </div>


                <div id="iamgePreviewArea" class="image_preview_area" style="<?= !empty($audio['poster']) ? '' : 'display: none;' ?>">
                    <img id="previewImagem" class="image_preview" src="<?= !empty($audio['poster']) ? $base.'/public/storage/posterAudio/'.$audio['poster'] : '' ?>">
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

                <script>
                    function exibirImagem(input) {
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                document.getElementById('previewImagem').src = e.target.result;
                                document.getElementById('iamgePreviewArea').style.display = 'flex';
                                document.getElementById('clearBtnPoster').style.display = 'block';
                            };
                            reader.readAsDataURL(input.files[0]);
                        }
                    }

                    function clearPoster() {
                        document.getElementById('poster_audio_file').value = "";
                        document.getElementById('previewImagem').src = "";
                        document.getElementById('iamgePreviewArea').style.display = 'none';
                    }
                </script>

                <div style="margin: 1em auto;" class="buttonArea">
                    <a href="<?= $_SERVER['HTTP_REFERER'] ?? $base ?>" style="margin: 0 1em;" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btnPublic">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once '../../partials/footer.php'; ?>
