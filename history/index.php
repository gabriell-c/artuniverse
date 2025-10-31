<?php
	require_once '../config.php';
    if(!isset($_SESSION['user'])){
        header("Location: ".$base);
        exit();
    }
    require_once '../partials/header.php';
	
	if(isset($_SESSION['warning'])){
		require_once '../modalAlert.php';
		unset($_SESSION['warning']);
	}
    if(isset($_SESSION['success'])){
		require_once '../modalSuccess.php';
		unset($_SESSION['success']);
	}


    $sqlViewer = "SELECT DISTINCT id_video FROM video_views WHERE id_user = :id_user";
    $sqlView = $pdo->prepare($sqlViewer);
    $sqlView->bindValue(':id_user', $_SESSION['user']['id']);
    $sqlView->execute();
    $allViews = $sqlView->fetchAll(PDO::FETCH_ASSOC);


    $sql = "SELECT * FROM post_likes WHERE id_user = :id_user ";
    $sql = $pdo->prepare($sql);
    $sql->bindValue(':id_user', $_SESSION['user']['id']);
    $sql->execute();
    $likes = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
    <section style="display: flex; width: 100%;" >
        <?php require_once '../sidebar.php' ?>

        <style>
            
            .content-card {
                transition: all 0.3s ease;
                transform-origin: center;
            }
            
            .content-card:hover {
                transform: scale(1.03);
                box-shadow: 0 10px 25px rgba(150, 115, 255, 0.2);
            }
            
            .filter-pill {
                transition: all 0.2s ease;
            }
            
            .filter-pill:hover {
                background-color: var(--color1);
                color: var(--color2);
            }
            
            .filter-pill.active {
                background-color: var(--color1);
                color: var(--color2);
            }
            
            .audio-wave {
                height: 4px;
                background: linear-gradient(90deg, var(--color1) 0%, transparent 100%);
                animation: wave 1.5s infinite linear;
            }
            
            @keyframes wave {
                0% { background-position: 0% 50%; }
                100% { background-position: 100% 50%; }
            }
        </style>
        <div class="max-w-7xl mx-auto px-1 mt-[80px]">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-2 text-gray-100">Seu Histórico</h1>
                <p class="text-gray-400">Conteúdo que você assistiu ou ouviu recentemente</p>
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap gap-3 mb-8">
                <button class="filter-pill active px-4 py-2 rounded-full bg-[#312b34] text-[#fbf5ff]">
                    <i class="fas fa-bars mr-2"></i>Todos
                </button>
                <button class="filter-pill px-4 py-2 rounded-full bg-[#312b34] text-[#fbf5ff]">
                    <i class="fas fa-play mr-2"></i>Vídeos
                </button>
                <button class="filter-pill px-4 py-2 rounded-full bg-[#312b34] text-[#fbf5ff]">
                    <i class="fas fa-music mr-2"></i>Áudios
                </button>
                <button class="filter-pill px-4 py-2 rounded-full bg-[#312b34] text-[#fbf5ff]">
                    <i class="fas fa-heart mr-2"></i>Curtidos
                </button>
            </div>
            
            <!-- Content Grid -->
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Video Card 1 -->

                <?php for($i = 0; $i < count($allViews); $i++): ?>
                    <?php
                        $postViews = "SELECT * FROM allposts WHERE id = :id";
                        $postView = $pdo->prepare($postViews);
                        $postView->bindValue(':id', $allViews[$i]['id_video']);
                        $postView->execute();
                        $postViewers = $postView->fetchAll(PDO::FETCH_ASSOC);


                        $sqlCounter = "SELECT * FROM video_views WHERE id_video = :id_video";
                        $sqlCountView = $pdo->prepare($sqlCounter); 
                        $sqlCountView->bindValue(':id_video', $allViews[$i]['id_video']);
                        $sqlCountView->execute();
                        $countAllView = $sqlCountView->fetchAll(PDO::FETCH_ASSOC);
                    ?>


                    <a href="<?=$base?>/watch/<?=$postViewers[0]['id_name']?>" class="content-card block rounded-xl overflow-hidden bg-[#1e1d1f] hover:border-[#9673ff] border border-transparent"
                        data-type="<?=$postViewers[0]['type']?>" 
                        data-liked="<?= in_array($postViewers[0]['id'], array_column($likes, 'id_post')) ? '1' : '0' ?>">
                        <div class="relative aspect-square">
                            <?php if($postViewers[0]['poster'] !== ''): ?>
                                <img src="<?=$base?>/public/storage/poster<?=$postViewers[0]['type'] === 'video' ? 'Video' : 'Audio'?>/<?=$postViewers[0]['poster']?>" alt="Video thumbnail" class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="<?=$base?>/public/img/saturn_background.jpg" alt="Default thumbnail" class="w-full h-full object-cover">
                            <?php endif; ?>
                            <div class="text-gray-300 absolute bottom-2 right-2 bg-black bg-opacity-70 px-2 py-1 rounded-[5px] text-sm">
                                12:34
                            </div>
                            <div class="absolute top-2 left-2 bg-[#9673ff] text-[#0b090c] px-2 py-1 rounded-full text-xs font-bold">
                                <i class="fas fa-<?=$postViewers[0]['type'] === 'video' ? 'play' : 'music'?> mr-1"></i> <?=$postViewers[0]['type']?>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-1 line-clamp-2 text-gray-100"><?=$postViewers[0]['title']?></h3>
                            <p class="text-gray-400 text-sm mb-2">Por @<?=$postViewers[0]['user_name']?></p>
                            <div class="flex items-center text-xs text-gray-400">
                                <i class="fas fa-eye mr-1" style="color: inherit; filter: drop-shadow(0 0 0);"></i> <?=count($countAllView)?> views
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i> 2 dias atrás
                            </div>
                        </div>
                    </a>
                <?php endfor; ?>

            </div>
                <div id="filter-curtidos" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 hidden">
                    <?php
                        $likedPosts = [];
                        for ($j = 0; $j < count($likes); $j++) {
                            $stmt = $pdo->prepare("SELECT * FROM allposts WHERE id = :id");
                            $stmt->bindValue(':id', $likes[$j]['id_post']);
                            $stmt->execute();
                            $post = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($post) $likedPosts[] = $post;
                            
                        }
                    ?>
                    <?php for ($k = 0; $k < count($likedPosts); $k++): ?>

                        <?php

                            $sqlLikeCounter = "SELECT * FROM post_likes WHERE id_post = :id_post";
                            $sqlCountLike = $pdo->prepare($sqlLikeCounter); 
                            $sqlCountLike->bindValue(':id_post', $likedPosts[$k]['id']);
                            $sqlCountLike->execute();
                            $countAllLike = $sqlCountLike->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <a href="<?=$base?>/<?=$likedPosts[$k]['type'] === 'video' || $likedPosts[$k]['type'] === 'audio' ? 'watch/' : 'view/'?>/<?=$likedPosts[$k]['id_name']?>" class="content-card  block rounded-xl overflow-hidden bg-[#1e1d1f]">
                            <div class="relative aspect-square">
                                <?php if($likedPosts[$k]['poster'] !== ''): ?>
                                    <?php if($likedPosts[$k]['type'] === 'video' || $likedPosts[$k]['type'] === 'audio'): ?>
                                        <img src="<?=$base?>/public/storage/poster<?=$likedPosts[$k]['type'] === 'video' ? 'Video' : 'Audio'?>/<?=$likedPosts[$k]['poster']?>" alt="Thumbnail" class="w-full h-full object-cover">
                                        
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($likedPosts[$k]['type'] === 'image'): ?>
                                        <img src="<?=$base?>/public/storage/photos/<?=$likedPosts[$k]['file']?>" alt="Thumbnail" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <img src="<?=$base?>/public/img/saturn_background.jpg" alt="Default thumbnail" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($likedPosts[$k]['type'] === 'video' || $likedPosts[$k]['type'] === 'audio'): ?>
                                    <div class="text-gray-300 absolute bottom-2 right-2 bg-black bg-opacity-70 px-2 py-1 rounded-[5px] text-sm">
                                        12:34
                                    </div>
                                <?php endif; ?>
                                
                                <div class="absolute top-2 left-2 bg-[#9673ff] text-[#0b090c] px-2 py-1 rounded-full text-xs font-bold">
                                    <i class="fas fa-<?=$likedPosts[$k]['type'] === 'video' ? 'play' : 'music'?> mr-1"></i> <?=$likedPosts[$k]['type']?>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium mb-1 line-clamp-2 text-gray-100"><?=$likedPosts[$k]['title']?></h3>
                                <p class="text-gray-400 text-sm mb-2">Por @<?=$likedPosts[$k]['user_name']?></p>
                                <div class="flex items-center text-xs text-gray-400">
                                <?php if($likedPosts[$k]['type'] === 'video' || $likedPosts[$k]['type'] === 'audio'): ?>
                                    <i class="fas fa-eye mr-1" style="color: inherit; filter: drop-shadow(0 0 0);" ></i> <?=$likedPosts[$k]['views_count'] ?? '0'?> views
                                <?php else: ?>
                                    <i class="fas fa-heart mr-1"></i> <?=count($countAllLike)?> likes
                                <?php endif; ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock mr-1"></i> 2 dias atrás
                                </div>
                            </div>
                        </a>
                    <?php endfor; ?>
                </div>

                    
        </div>

<script>
  const filterButtons = document.querySelectorAll('.filter-pill');
  const containerTodos = document.querySelector('.grid.sm\\:grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4.gap-6'); // seu container "todos"
  const containerCurtidos = document.getElementById('filter-curtidos');
  const cardsTodos = containerTodos.querySelectorAll('.content-card');

  filterButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Remove active
      filterButtons.forEach(b => b.classList.remove('active'));
      button.classList.add('active');

      const filterText = button.textContent.trim().toLowerCase();

      if (filterText.includes('curtidos')) {
        containerCurtidos.classList.remove('hidden');
        containerTodos.classList.add('hidden');
      } else {
        containerCurtidos.classList.add('hidden');
        containerTodos.classList.remove('hidden');

        if (filterText.includes('vídeos')) {
          // mostra só os cards de vídeo
          cardsTodos.forEach(card => {
            card.style.display = card.getAttribute('data-type') === 'video' ? 'block' : 'none';
          });
        } else if (filterText.includes('áudios')) {
          // mostra só os cards de áudio
          cardsTodos.forEach(card => {
            card.style.display = card.getAttribute('data-type') === 'audio' ? 'block' : 'none';
          });
        } else {
          // "Todos" mostra todos
          cardsTodos.forEach(card => card.style.display = 'block');
        }
      }
    });
  });
</script>



    </section>

<?php
    require_once '../partials/footer.php';
?>