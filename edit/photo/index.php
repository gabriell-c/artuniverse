<?php
require_once '../../config.php';
require_once '../../loadingelement.php';
if (!isset($_SESSION['user'])) {
    header('Location: '.$base);
    exit;
}
require_once '../../partials/header.php';


$id = $_GET['id_content'] ?? null;
if (!$id) {
    require_once '../../not_found.php';
    exit;
}

$sql = "SELECT * FROM allposts WHERE archive = 'false' AND type = 'image' AND id_name = :id_name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id_name', $id);
$stmt->execute();
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
    require_once '../../not_found.php';
    exit;
}

if (isset($_SESSION['warning'])) {
    require_once '../../modalAlert.php';
    unset($_SESSION['warning']);
}
?>

<style>
.image_preview_area {
    margin-top: 1em;
    display: flex;
    flex-direction: column;
    gap: 0.5em;
}
.image_preview {
    border-radius: 8px;
}
.cleardeleteButton {
    cursor: pointer;
    color: red;
    font-size: 14px;
}
</style>

<section style="display: flex; width: 100%;">
    <?php require_once '../../sidebar.php' ?>
    <div class="ContainerAreaUploadVideo">
        <div class="containerUploadVideo">
            <h5 class="titleUplaodVideoPage">Edit image</h5>
            <form enctype="multipart/form-data" method="post" action="../../actions/editPhoto.php" class="bodyUploadVideo">
                <input type="hidden" name="id_name" value="<?= htmlspecialchars($id) ?>">

                <label for="title">Title:</label>
                <input required placeholder="Edit title" type="text" maxlength="100" name="title" value="<?= htmlspecialchars($image['title']) ?>" class="inputFormUpload">

                <label for="description">Description:</label>
                <textarea name="description" maxlength="2500" class="inputFormUpload"><?= htmlspecialchars($image['description']) ?></textarea>

                <label for="tags">Tags:</label>
                <input type="text" id="tags" data-role="taginput" name="tags" value="<?= htmlspecialchars($image['tags']) ?>" data-max-tags="5">

                
                <div class="input-file-container">  
                    <input name="image_file" class="input-file" id="my-file" type="file" accept="image/*" onchange="exibirImagem(this)">
                    <label tabindex="0" for="my-file" class="input-file-trigger">Choose File</label>
                </div>

                <div id="iamgePreviewArea" class="image_preview_area" style="<?= !empty($image['file']) ? '' : 'display: none;' ?>">
                    <img id="previewImagem" class="image_preview" src="<?= $base ?>/public/storage/photos/<?= $image['file'] ?>">
                </div>

                <div style="margin: 1em auto;" class="buttonArea">
                    <a href="<?= $_SERVER['HTTP_REFERER'] ?? $base ?>" style="margin: 0 1em;" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btnPublic">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function exibirImagem(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImagem').src = e.target.result;
            document.getElementById('iamgePreviewArea').style.display = 'flex';
            document.getElementById('clearBtnImage').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../../partials/footer.php'; ?>
