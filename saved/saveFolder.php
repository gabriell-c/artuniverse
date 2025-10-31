<?php

    require_once '../partials/header.php';
    require_once '../loadingElement.php';

    $sql = "SELECT * FROM allsave WHERE id_name = :id_name ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_name', $_GET['id_name']);
    $stmt->execute();
    $saved = $stmt->fetch(PDO::FETCH_ASSOC);

    //--------------------------------------//

    $sqlI = "SELECT * FROM itemsave WHERE id_name = :id_name ";
    $stmtI = $pdo->prepare($sqlI);
    $stmtI->bindValue(':id_name', $_GET['id_name']);
    $stmtI->execute();
    $savedItem = $stmtI->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
    .swiper-container {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: var(--color2);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        max-width: 100%;
        max-height: 80vh;
        object-fit: cover;
    }



</style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../sidebar.php' ?>
    <div class="savedItemArea">
        <h1 style="margin-bottom: 2em;"><?=$saved['save_name']?></h1>
        <div class="savedItemsArea">
            <?php for($i = 0; $i < count($savedItem); $i++) : ?>
                <div style="background-image: url('<?=$base?>/public/storage/photos/<?=$savedItem[$i]['file']?>');" class="savedItem" data-toggle="modal" data-target="#SwiperModal">

                </div>
            <?php endfor ?>
        </div>
    </div>

    <div class="modal fade" id="SwiperModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 900px; overflow: hidden; height: 100%; max-height: 95vh; margin: 1rem auto;justify-content: center; align-items: center;display: flex;">
            <div class="modal-content" >
                <div class="modal-header">
                    <h5 id="titleModal<?=count($savedItem)?>" style="cursor: pointer; color: var(--color5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="modal-title"><?=$savedItem[0]['title']?></h5>
                    <button  style="color: var(--color5);" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center;align-items: center;" class="modal-body">

                    <div class="swiper" style="width: 100%;">
                        <!-- Additional required wrapper -->
                        <div id="LoadingContentA" class="LoadingContentA">
                            <div id="loadingImage" class="loadingArea">
                                <div class="planet mask">
                                <div class="ring mask"></div>
                                <div class="cover-ring mask"></div>
                                </div>
                                <p class="loaginText">loading</p>
                            </div>
                        </div>
                        <div class="swiper-wrapper">
 
                            <!-- Slides -->

                            <?php for($i = 0; $i < count($savedItem); $i++) : ?>
                                <div class="swiper-slide">
                                    <img class="img-slider<?=$i?>" src="<?=$base?>/public/storage/photos/<?=$savedItem[$i]['file']?>" >
                                </div>
                            <?php endfor ?>
                        </div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                    </div>

                </div>
    
            </div>
        </div>
    </div>
    <?php echo count($savedItem)?>

</section>

<script>
    var modal = document.getElementById('SwiperModal');
    var modalContent = document.querySelector('.modal-content');
    var closeModal = document.querySelector('.close');
    var imageList = document.querySelectorAll('.savedItem');



    const swiper = new Swiper('.swiper', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        on: {
            init: function (swiper) { // Usa 'swiper' como parâmetro para garantir acesso à instância
                var currentSlideIndex = swiper.realIndex; // Obtém o índice real da imagem atual
                var savedItems = <?= json_encode($savedItem) ?>; // Converte o array PHP em JavaScript
                
                document.querySelector(".modal-title").innerHTML = savedItems[currentSlideIndex]['title'];
                document.querySelector(".modal-title").onclick = () => {
                    window.location.href = "<?=$base?>/view/" + (savedItems[currentSlideIndex].file).slice(0, -4);
                };

                for (var i = 0; i < <?=count($savedItem)?>; i++) {
                    document.querySelector(".img-slider" + i).addEventListener("dblclick", function() {
                        window.location.href = "<?=$base?>/view/" + (savedItems[currentSlideIndex].file).slice(0, -4);
                    });
                }
            },
            slideChange: function (swiper) {
                var savedItems = <?= json_encode($savedItem) ?>;
                updateModalTitle(swiper.realIndex, savedItems);
                updateDoubleClick(swiper.realIndex, savedItems);

            }
        }
    });

    function updateModalTitle(index, savedItems) {
        document.querySelector(".modal-title").innerHTML = savedItems[index]['title'];
        document.querySelector(".modal-title").onclick = () => {
            window.location.href = "<?=$base?>/view/" + (savedItems[index].file).slice(0, -4);
        };  
    }

    function updateDoubleClick(index, savedItems) {
        document.querySelectorAll(".swiper-slide").forEach((slide, i) => {
            slide.ondblclick = () => {
                window.location.href = "<?=$base?>/view/" + (savedItems[i].file).slice(0, -4);
            };
        });
    }

    imageList.forEach(function(image, index) {
    image.setAttribute('data-index', index);
    image.addEventListener('click', function(event) {
        var clickedIndex = event.currentTarget.getAttribute('data-index');
        swiper.slideTo(clickedIndex);
        $('#SwiperModal').modal('show');

        // Atualiza o título corretamente ao abrir o modal
        var savedItems = <?= json_encode($savedItem) ?>;
        document.querySelector(".modal-title").innerHTML = savedItems[clickedIndex]['title'];
        document.querySelector(".modal-title").onclick = () => {
            window.location.href = "<?=$base?>/view/" + (savedItems[clickedIndex].file).slice(0, -4);
        };
    });
});


    $('#SwiperModal').on('shown.bs.modal', function () {
        var loadingImage = document.getElementById('LoadingContentA');
        loadingImage.style.display = 'none'; // Oculta a imagem de carregamento
    })

</script>

<?php require_once '../partials/footer.php' ?>