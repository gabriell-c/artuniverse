<?php
    require_once '../../config.php';
    require_once '../../partials/header.php';

    if(isset($_SESSION['warning'])){
		require_once '../../modalAlert.php';
		unset($_SESSION['warning']);
	}

?>
<section style="display: flex; width: 100%;" >
    <?php require_once '../../sidebar.php' ?>
    <div class="ContainerAreaUploadVideo">
        <div class="containerUploadVideo">
            <h5 class="titleUplaodVideoPage">Upload image</h5>
            <form enctype="multipart/form-data" method="post" action="../../actions/uploadPhoto.php" class="bodyUploadVideo" >
                <label for="title">Title:</label>
                <input required placeholder="Create a title" type="text" maxlength="100" name="title" required class="inputFormUpload">

                <label for="description">Description:</label>
                <textarea  type="text" name="description" maxlength="2500" class="inputFormUpload"></textarea>

                <label for="tags">Tags:</label>
                <input type="text" id="tags" data-role="taginput" name="tags" data-max-tags="5">

                <div class="input-file-container">  
                    <input required name="image_file" class="input-file" id="my-file" type="file" accept="image/*" onchange="exibirImagem(this)">
                    <label tabindex="0" for="my-file" class="input-file-trigger">Choose File</label>
                </div>
                <img id="previewImagem" class="video-preview">


                <?php
                    if(isset($_SERVER['HTTP_REFERER'])) {
                        $paginaAnterior = $_SERVER['HTTP_REFERER'];
                    } else {
                        $paginaAnterior = $base;
                    }
                ?>

                <div style="margin: 1em auto;" class="buttonArea">
                    <a href="<?=$paginaAnterior?>" style="margin: 0 1em;" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btnPublic">publish</button>
                </div>
            </form> 
        </div>
    </div>
</section>

<script>

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
  }
}





  </script>


<?php

    require_once '../../partials/footer.php';

?>