<?php

    require_once '../config.php';
    if (!isset($_SESSION['user'])) {
        header('Location: '.$base);
        exit;
    }
    require_once '../partials/header.php';

    if(isset($_SESSION['warning'])){
        require_once '../modalAlert.php';
        unset($_SESSION['warning']);
    }

    $sqlP = "SELECT * FROM allposts WHERE id_user = :id_user AND archive = :archiveType";
    $stmt = $pdo->prepare($sqlP);
    $stmt->bindValue(':id_user', $_SESSION['user']['id']);
    $stmt->bindValue(':archiveType', 'true');
    $stmt->execute();
    $postItem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM post_likes WHERE id_user = :id_user ";
    $sql = $pdo->prepare($sql);
    $sql->bindValue(':id_user', $_SESSION['user']['id']);
    $sql->execute();
    $likes = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<script src="https://cdn.tailwindcss.com"></script>

<style>
    
    :root {
        --primary: #8a63ff;
        --primary-light: rgba(138, 99, 255, 0.1);
        --dark: #0f0e13;
        --dark-light: #1a1820;
        --light: #f5f3ff;
    }
    
    .gradient-text {
        background: linear-gradient(45deg, var(--primary), #c37aff);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    
    .input-field {
        transition: all 0.2s ease;
    }
    
    .input-field:focus {
        box-shadow: 0 0 0 2px var(--primary);
    }
    
    .btn-primary {
        background-color: var(--primary);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px -3px rgba(138, 99, 255, 0.4);
    }
    
    .cosmic-bg {
        background: 
            radial-gradient(circle at 25% 25%, rgba(138, 99, 255, 0.1) 0%, transparent 35%),
            radial-gradient(circle at 75% 75%, rgba(138, 99, 255, 0.1) 0%, transparent 35%),
            var(--dark);
    }
    
    .tab-active {
        color: var(--primary);
        border-bottom: 2px solid var(--primary);
    }
    
    .tab-inactive {
        color: var(--light);
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease;
    }
    
    .tab-inactive:hover {
        color: var(--primary);
        border-bottom: 2px solid var(--primary-light);
    }
    
    .modal {
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    
    .modal-content {
        transform: translateY(20px);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .modal.active {
        opacity: 1;
        visibility: visible;
    }
    
    .modal.active .modal-content {
        transform: translateY(0);
    }
    :root {
        --size: 100px;
        --frames: 62;
    }

    .likeHeart input {
        display: none;
    }

    .likeHeart {
        display: block;
        width: var(--size);
        height: var(--size);
        cursor: pointer;
        border-radius: 999px;
        overflow: visible;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        -webkit-tap-highlight-color: transparent;
    }

    .hearthLike {
        background-image: url('https://assets.codepen.io/23500/Hashflag-CountdownToMars.svg');
        background-size: calc(var(--size) * var(--frames)) var(--size);
        background-repeat: no-repeat;
        background-position-y: calc(var(--size) * 0.02);
        width: var(--size);
        height: var(--size);
        background-position-x: calc(var(--size) * (var(--frames) * -1 + 3)); /* Coração sempre cheio */
    }

    @keyframes like {
        0% {
            background-position-x: 0;
        }
        100% {
            background-position-x: calc(var(--size) * (var(--frames) * -1 + 3));
        }
    }

    .likeHeart.animate .hearthLike {
        animation: like 1s steps(calc(var(--frames) - 3)) !important;
        animation-fill-mode: forwards !important;
        opacity: 1 !important;
    }
</style>

<!-- <style>
    .warpper{
        display:flex;
        align-items: flex-start;
        margin: 2em auto;
    }
    .tabs{
        background-color: transparent;
        border: 0;
    }
    .tab{
        cursor: pointer;
        padding:10px 20px;
        margin:0px 2px;
        display:inline-block;
        color: var(--color5);
        border-radius:3px 3px 0px 0px;
        display: flex;
        flex-direction: column;
    }
    .panels{
        background: var(--color2);
        min-height:200px;
        width:700px;
        border-radius:3px;
        overflow:hidden;
        display: flex;
    }
    .panel{
        display:none;
        background-color: transparent;        
        border: 0;
    }
    @keyframes fadein {
        from {
            opacity:0;
        }
        to {
            opacity:1;
        }
    }
    .panel-title{
        font-size:1.5em;
        font-weight:bold
    }
    .radio{
        display:none;
    }
    #one:checked ~ .panels #one-panel,
    #two:checked ~ .panels #two-panel,
    #three:checked ~ .panels #three-panel{
        display:block
    }
    #one:checked ~ .tabs #one-tab,
    #two:checked ~ .tabs #two-tab,
    #three:checked ~ .tabs #three-tab{
        background: var(--color1);
        color: var(--color5);
        border-top: 3px solid var(--color4);
    }

    .hrSetting{
        height: auto;
        width: 3px;
        opacity: .1;
        background: var(--color5);
        margin: 0 2em;
        border-radius: 2px;
    }
</style>

<section style="display: flex; width: 100%;" >

    <div class="warpper">
        <input class="radio" id="one" name="group" type="radio" checked>
        <input class="radio" id="two" name="group" type="radio">
        <input class="radio" id="three" name="group" type="radio">

        <div class="tabs">
            <label class="tab" id="one-tab" for="one">Profile</label>
            <label class="tab" id="two-tab" for="two">Archived</label>
            <label class="tab" id="three-tab" for="three">Prerequisites</label>
        </div>

        

        <div class="panels">
        <div class="hrSetting"></div>
            <div class="panel" id="one-panel">
            </div>
            <div class="panel" id="two-panel">
                <button id="btnToArchivedAllPosts" type="button" class="btn" style="background-color: var(--color1); color: var(--color5);" >see all archived posts</button>
            </div>
            <div class="panel" id="three-panel">
                <div class="panel-title">Note on Prerequisites</div>
                <p>We recommend that you complete Learn HTML before learning CSS.</p>
            </div>
        </div>
    </div> -->



















<section style="display: flex;width: 100%; justify-content: center; align-items: center; margin: 3em auto;" >
    <?php require_once '../sidebar.php' ?>

    <!-- Main container -->
    <div class="cosmic-bg w-full max-w-2xl rounded-xl p-8 space-y-6 backdrop-blur-md border border-[rgba(138,99,255,0.1)]">
        <div class="text-center space-y-2">
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="url(#gradient)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#8a63ff" />
                            <stop offset="100%" stop-color="#c37aff" />
                        </linearGradient>
                    </defs>
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold mt-4">Account <span class="gradient-text font-bold">Settings</span></h1>
            <p class="text-gray-400">Manage your profile information and account settings</p>
        </div>
        
        <!-- Tabs -->
        <div class="flex border-b border-[var(--dark-light)]">
            <button class="tab-active px-4 py-2 font-medium text-sm" data-tab="profile">
                <i class="fas fa-user mr-2"></i>Profile
            </button>
            <button class="tab-inactive px-4 py-2 font-medium text-sm" data-tab="archived">
                <i class="fas fa-archive mr-2"></i>Archived
            </button>
            <button class="tab-inactive px-4 py-2 font-medium text-sm" data-tab="example">
                <i class="fas fa-heart mr-2"></i>Liked
            </button>
        </div>
        
        <!-- Tab content -->
        <div class="space-y-6">
            <!-- Profile tab content -->
            <div id="profile-tab" class="tab-content">
                <form class="space-y-4" method="POST" action="../actions/updateUser.php">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fullname" class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <input required value="<?=$_SESSION['user']['full_name']?>" name="full_name" id="fullname" type="text" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="Full Name">
                            </div>
                        </div>
                        
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-300 mb-1">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-gray-500"></i>
                                </div>
                                <input required value="<?=$_SESSION['user']['user_name']?>" name="user_name" id="username" type="text" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="User name">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-500"></i>
                            </div>
                            <input required value="<?=$_SESSION['user']['email']?>" name="email" id="email" type="email" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="your@email.com">
                        </div>
                    </div>
                    <?php 
                        $bioUser = !empty($_SESSION['user']['bio_user']) ? htmlspecialchars($_SESSION['user']['bio_user']) : ''; 
                    ?>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-300 mb-1">Bio</label>
                        <textarea id="bio" rows="3" maxlength="150" name="bio_user" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="Tell us about yourself..."><?= $bioUser ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Phone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-500"></i>
                                </div>
                                <input required value="<?=$_SESSION['user']['phone_number']?>" maxlength="12" name="phone_number" id="phone" type="phone" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="(01) 23456-7890">
                            </div>
                        </div>
                        
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-300 mb-1">Birth Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-gray-500"></i>
                                </div>
                                <input required value="<?=date('Y-m-d', strtotime($_SESSION['user']['date_of_birth']));?>" maxlength="10" name="date_of_birth" id="birthdate" type="date" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none">
                            </div>
                        </div>
                        
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-300 mb-1">Gender</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-venus-mars text-gray-500"></i>
                                </div>
                                <select name="gender" id="gender" required class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none">
                                    <option value="male" <?= $_SESSION['user']['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= $_SESSION['user']['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                                    <option value="PNS" <?= $_SESSION['user']['gender'] === 'PNS' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <?php 
                            $linkUser = !empty($_SESSION['user']['link_user']) ? htmlspecialchars($_SESSION['user']['link_user']) : ''; 
                        ?>
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-300 mb-1">Instagram</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-instagram text-gray-500"></i>
                            </div>
                            <input value="<?= $linkUser ?>" name="link_user" id="instagram" type="text" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="yourusername">
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="btn-primary w-full py-2.5 rounded-lg font-medium text-white">
                            Save Changes
                        </button>
                    </div>
                </form>
                
                <div class="border-t border-[var(--dark-light)] pt-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-300 mb-4">Password Settings</h3>
                    <button id="change-password-btn" class="w-full py-2.5 rounded-lg font-medium text-white border border-[var(--primary)] text-[var(--primary)] hover:bg-[var(--primary-light)] transition-colors">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </div>
            </div>
            
            <!-- Archived tab content -->
            <div id="archived-tab" class="tab-content hidden">
                <div class="text-center py-8">
                    <?php if(count($postItem) === 0):?>

                        <i class="fas fa-archive text-4xl text-gray-500 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-300 mb-2">Archived Content</h3>
                        <p class="text-gray-500">Your archived items will appear here</p>
                    <?php else: ?>
                        <button id="btnToArchivedAllPosts" type="button" class="btn" style="background-color: var(--color1); color: var(--color5);" >see all archived posts</button>
                    <?php endif ?>
                </div>
            </div>
            
            <!-- Example tab content -->
            <div id="example-tab" class="tab-content hidden">
                <div class="text-center py-8">
                    <?php if(count($likes) === 0):?>
                        <div class=" flex flex-col items-center justify-center"  style="color: var(--color5);">
                            <label class="likeHeart items-center justify-center" style="scale: 1.5; margin-bottom: 2em;">
                                <input type="checkbox" />
                                <div class="hearthLike"></div>
                            </label>
                            <h2>Start liking</h2>
                            <p style="text-align: center;" >you still haven't liked any post, how about starting now? <br>:)</p>
                            <button id="buttonNoLikedHref" type="button" class="btn" style="color: var(--color5);background: var(--color1);" >Start liking now</button>
                        </div>
                    <?php else: ?>
                        <button id="btnToLikedAllPosts" type="button" class="btn" style="background-color: var(--color1); color: var(--color5);" >see all liked posts</button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Change Password Modal -->
    <div id="password-modal" class="modal fixed inset-0 bg-[#00000099] bg-opacity-50 flex items-center justify-center p-4 opacity-0 invisible">
        <div class="modal-content cosmic-bg w-full max-w-md rounded-xl p-6 space-y-4 backdrop-blur-md border border-[rgba(138,99,255,0.1)]">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold gradient-text">Change Password</h3>
                <button id="close-modal" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="post" action="../../actions/changePassword.php" class="space-y-4">
                <div>
                    <label for="current-username" class="block text-sm font-medium text-gray-300 mb-1">User name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-500"></i>
                        </div>
                        <input name="user_name" id="current-username" type="text" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="Your username">
                    </div>
                </div>
                
                <div>
                    <label for="current-password" class="block text-sm font-medium text-gray-300 mb-1">Current Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                        <input name="current_password" id="current-password" type="password" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="Your current password">
                    </div>
                </div>
                
                <div class="border-t border-[var(--dark-light)] pt-4">
                    <div class="mb-4">
                        <label for="new-password" class="block text-sm font-medium text-gray-300 mb-1">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-500"></i>
                            </div>
                            <input name="new_password" id="new-password" type="password" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="Create new password">
                        </div>
                    </div>
                    
                    <div>
                        <label for="confirm-password" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-500"></i>
                            </div>
                            <input name="repeat_new_password" id="confirm-password" type="password" class="input-field w-full bg-[var(--dark-light)] border border-[var(--dark-light)] rounded-lg py-2.5 pl-10 pr-4 text-white focus:outline-none" placeholder="Repeat new password">
                        </div>
                    </div>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full py-2.5 rounded-lg font-medium text-white">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            // Tab switching functionality
            document.querySelectorAll('[data-tab]').forEach(tab => {
                tab.addEventListener('click', () => {
                    // Update active tab styling
                    document.querySelectorAll('[data-tab]').forEach(t => {
                        t.classList.remove('tab-active');
                        t.classList.add('tab-inactive');
                    });
                    tab.classList.add('tab-active');
                    tab.classList.remove('tab-inactive');
                    
                    // Show corresponding tab content
                    const tabId = tab.getAttribute('data-tab');
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById(`${tabId}-tab`).classList.remove('hidden');
                });
            });
            
            // Password modal functionality
            const modal = document.getElementById('password-modal');
            const openBtn = document.getElementById('change-password-btn');
            const closeBtn = document.getElementById('close-modal');
            
            openBtn.addEventListener('click', () => {
                modal.classList.add('active');
                modal.classList.remove('invisible');
            });
            
            closeBtn.addEventListener('click', () => {
                modal.classList.remove('active');
                modal.classList.add('invisible');

            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    modal.classList.add('invisible');
                }
            });
            document.getElementById("btnToArchivedAllPosts").onclick = () =>{
                window.location.href = "<?=$base?>/settings/archive"
            }
            document.getElementById("btnToLikedAllPosts").onclick = () =>{
                window.location.href = "<?=$base?>/liked"
            }


        })
        <?php if(count($likes) === 0):?>

            // Função para adicionar a classe de animação ao botão
            function iniciarAnimacao() {
                const botao = document.querySelector('.likeHeart');
                botao.classList.add('animate');
            }

            // Função para remover a classe de animação do botão
            function pararAnimacao() {
                const botao = document.querySelector('.likeHeart');
                botao.classList.remove('animate');
            }

            // Iniciar a animação a cada 4 segundos
            setInterval(() => {
                iniciarAnimacao();
                setTimeout(pararAnimacao, 1000);
            }, 2000);
        <?php endif ?>

        const btnHref = document.getElementById("buttonNoLikedHref");

        btnHref.onclick = () =>{
            window.location.href = "<?=$base?>";
        }
    </script>



<?php require_once '../partials/footer.php' ?>