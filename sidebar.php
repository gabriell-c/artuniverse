
    <style>
        :root {
            --primary: #6e48ff;
            --primary-dark: #5d3bef;
            --dark: #0f0e14;
            --darker: #09080d;
            --light: #f9f7ff;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }



        /* Sidebar Container */
        .Msidebar-container {
            position: relative;
            height: 100vh;
        }

        /* Sidebar */
        .Msidebar {
            left: 0;
            top: 0;
            width: 80px;
            height: 100vh;
            background: rgba(15, 14, 20, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-right: 1px solid var(--glass-border);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.16, 0.77, 0.21, 0.99) !important;
            position: fixed;
            z-index: 100;
        }

        .Msidebar.Mexpanded {
            width: 240px;
            align-items: flex-start;
            padding-left: 20px;
        }

        /* Logo/Hamburger */
        .Mlogo-toggle {
            width: 40px;
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: relative;
            z-index: 2;
        }

        .Mlogo-toggle:hover {
            opacity: 0.8;
        }

        .Mlogo-toggle span {
            display: block;
            height: 3px;
            width: 28px;
            background: var(--light);
            border-radius: 4px;
            transition: all 0.3s ease !important;
            position: absolute;
        }

        .Mlogo-toggle span:nth-child(1) {
            transform: translateY(-8px);
        }

        .Mlogo-toggle span:nth-child(3) {
            transform: translateY(8px);
        }

        .Mlogo-toggle.Mactive span:nth-child(1) {
            transform: translateY(0) rotate(45deg);
        }

        .Mlogo-toggle.Mactive span:nth-child(2) {
            opacity: 0;
        }

        .Mlogo-toggle.Mactive span:nth-child(3) {
            transform: translateY(0) rotate(-45deg);
        }

        /* User Profile */
        .Muser-profile {
            align-items: center;
            margin-bottom: 30px;
            width: calc(100% - 20px);
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
        }
        .Msidebar .Muser-info{
            display: none;
        }


        .Mexpanded .Muser-profile .Muser-info {
            display: flex;
        }

        .Muser-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-weight: bold;
            color: var(--light);
            font-size: 14px;
            /* box-shadow: 0 4px 12px rgba(110, 72, 255, 0.3); */
        }

        .Muser-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .Muser-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 3px;
            letter-spacing: 0.2px;
            color: var(--color1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .Muser-role {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Menu Items */
        .Mmenu-items {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex-grow: 1;
        }

        .Mmenu-item {
            display: flex;
            align-items: center;
            padding: 14px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: calc(100% - 20px);
            overflow: hidden;
            position: relative;
        }

        .Mmenu-item:hover {
            background: var(--glass);
        }

        .Mmenu-item.Mactive {
            background: linear-gradient(90deg, rgba(110, 72, 255, 0.2), transparent);
            border-left: 2px solid var(--primary);
        }
        .Mmenu-item:hover {
            background: linear-gradient(90deg, rgba(110, 72, 255, 0.2), transparent);
            border-left: 2px solid var(--primary);
        }

        .Mmenu-item.Mactive i {
            color: var(--primary);
        }
        .Mmenu-item:hover i {
            color: var(--primary);
        }

        .Mmenu-item i {
            font-size: 1.2rem;
            min-width: 40px;
            display: flex;
            justify-content: center;
            color: var(--light);
            transition: all 0.3s ease;
        }

        .Mmenu-item .Mitem-title {
            font-weight: 500;
            opacity: 0;
            white-space: nowrap;
            transform: translateX(-10px);
            transition: all 0.3s ease 0.1s;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
            color: var(--color5);
        }

        .Msidebar.Mexpanded .Mmenu-item .Mitem-title {
            opacity: 1;
            transform: translateX(0);
        }

        /* Logout Section */
        .Mlogout-section {
            margin-top: auto;
            width: 100%;
            padding-top: 15px;
            border-top: 1px solid var(--glass-border);
            display: flex;
            justify-content: center;
        }

        .Mlogout-item {
            display: flex;
            align-items: center;
            padding: 12px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: calc(100% - 20px);
            overflow: hidden;
            color: #f82945;
        }

        .Mlogout-item:hover {
            background: var(--color4);
            color: #ff0022;
        }

        .Mlogout-item i {
            font-size: 1.2rem;
            min-width: 40px;
            display: flex;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .Mlogout-item .Mitem-title {
            font-weight: 500;
            opacity: 0;
            white-space: nowrap;
            transform: translateX(-10px);
            transition: all 0.3s ease 0.1s;
            font-size: 0.9rem;
        }

        .Msidebar.Mexpanded .Mlogout-item .Mitem-title {
            opacity: 1;
            transform: translateX(0);
        }

 

        /* Tooltip */
         .Mtooltip {
            position: absolute;
            left: 70px;
            background: var(--dark);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            pointer-events: none;
            z-index: 20;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
        }

        .Mmenu-item:hover .Mtooltip {
            opacity: 1;
            visibility: visible;
            left: 75px;
            color: var(--color5);
        }.Mmenu-item:hover {
            overflow: visible;
        }
        .Mexpanded .Mmenu-item:hover .Mtooltip{
            opacity: 0 !important;
        }


        .linkBtnNav:hover .Mtooltip {
            opacity: 1;
            visibility: visible;
            left: 55px;
            color: var(--color5);
        }.linkBtnNav:hover {
            overflow: visible;
        }
        .Mexpanded .linkBtnNav:hover .Mtooltip{
            opacity: 0 !important;
        }

        .logoBox{
            width: 50px;
        }
        .linkBtnNav{
            width: 100%;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: ease-in-out .2s;
        }
        .btnNav{
            border-radius: 50%;
            background-color: var(--color4);
            color: var(--color5);
            border: none;
            outline: none;
            border-radius: 150px;
            padding: 14px 10px;
            width: 50px;
            height: 50px;
            font-weight: 500;
            transition: box-shadow 0.3s ease;
            cursor: pointer;
            box-shadow: 3px 3px 8px #00000038, -3px -3px 8px #ffffff1e;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: ease-in-out .2s;

        }
        .btnNav .Mitem-title{
            opacity: 0;
            width: 0;
            transition: ease-in-out .2s;
        }
        .Mexpanded .btnNav {
            padding: 12px 12px 12px 16px;
            width: calc(100% - 20px);
            display: flex;
            justify-content: flex-start;
            transition: ease-in-out .2s;
        }
        .Mexpanded .btnNav .Mitem-title{
            opacity: 1;
            width: auto;
            margin-left: 10px;
            transition: ease-in-out .2s;
        }
        .linkBtnNav:hover .btnNav{
            transition: ease-in-out .2s;
            scale: .98;
            background-color: var(--color1);
        }

        /*---------- search input ------------*/



        .search-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .custom-search-input {
            padding: 0;
            width: 50px;
            height: 50px;
            border-radius: 50px;
            font-size: 16px;
            /* width: 100%; */
            outline: none;
            border: none;
            background-color: var(--color4);
            box-shadow: 3px 3px 8px #00000038, -3px -3px 8px #ffffff1e;
            transition: box-shadow 0.3s ease;
        }
        .custom-search-input::placeholder {
            color: var(--color5);
            opacity: 0;
        }
        .custom-search-input:focus {
            color: var(--color5);
        }
        .custom-search-input {
            color: var(--color1);
        }

        .fa-searchs {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--color1);
            background-color: transparent;
            border: none;
            outline: none;
            cursor: pointer;
            transition: color 0.3s ease;
            display: flex;
        }
        
        .Mexpanded .custom-search-input{
            padding: 10px 20px;
            width: calc(100% - 20px);
        }

        .Mexpanded .fa-searchs{
            display: none;
        }
        .Mexpanded .custom-search-input::placeholder {
            color: var(--color5);
            opacity: 0.3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .Msidebar {
                transform: translateX(-100%);
            }

            .Msidebar.Mexpanded {
                transform: translateX(0);
                width: 260px;
            }


            .Mmenu-item .Mtooltip {
                display: none;
            }
        }

        /* Animation for menu items */
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .Msidebar.Mexpanded .Mmenu-item {
            animation: fadeInSlide 0.4s ease forwards !important;
        }

        .Msidebar.Mexpanded .Mmenu-item:nth-child(1) { animation-delay: 50ms !important;}
        .Msidebar.Mexpanded .Mmenu-item:nth-child(2) { animation-delay: 100ms !important;}
        .Msidebar.Mexpanded .Mmenu-item:nth-child(3) { animation-delay: 150ms !important;}
        .Msidebar.Mexpanded .Mmenu-item:nth-child(4) { animation-delay: 200ms !important;}
        .Msidebar.Mexpanded .Mmenu-item:nth-child(5) { animation-delay: 250ms !important;}
        .Msidebar.Mexpanded .Mmenu-item:nth-child(7) { animation-delay: 300ms !important;}
        .Msidebar.Mexpanded .Mlogout-item { animation-delay: 350ms !important; }
    </style>
    <div class="Msidebar-container">
        <div class="Msidebar">
            <div class="Mlogo-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <a class="logoBox" href="<?= $base ?>">
                <img src="<?=$base?>/public/img/saturn.webp" alt="">
            </a> 

            
            
            <div class="Mmenu-items">
                
                <form action="/artuniverse/results.php" method="GET" class="search-container">
                    <input value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>" type="text" name="q" required class="search-input custom-search-input" placeholder="Search">
                    <div class="fa-searchs " ><i class="fas fa-search"></i></div>
                </form>
                <a href="<?=$base?>" class="Mmenu-item <?=$URL === '/artuniverse/' || $URL === '/artuniverse' ? 'Mactive' : ''?>">
                    <i class="fas fa-home"></i>
                    <span class="Mitem-title">Início</span>
                    <span class="Mtooltip">Início</span>
                </a>
                <a href="<?=$base?>/top" class="Mmenu-item <?= (strpos($URL, "/artuniverse/top") !== false) ? 'Mactive' : '' ?>">
                    <i class="fas fa-fire-flame-curved"></i>
                    <span class="Mitem-title">Top</span>
                    <span class="Mtooltip">Top</span>
                </a>
                <a  href="<?=$base?>/saved" class="Mmenu-item <?= (strpos($URL, "/artuniverse/saved") !== false) ? 'Mactive' : '' ?>">
                    <i class="fas fa-bookmark"></i>
                    <span class="Mitem-title">Saved</span>
                    <span class="Mtooltip">Saved</span>
                </a>
                <a  href="<?=$base?>/<?=isset($_SESSION['user']) ? 'playlist' : 'login'?>" class="Mmenu-item <?= (strpos($URL, "/artuniverse/playlist") !== false) ? 'Mactive' : '' ?>">
                    <i class="fas fa-tasks"></i>
                    <span class="Mitem-title">Playlist</span>
                    <span class="Mtooltip">Playlist</span>
                </a>
                <a  href="<?=$base?>/<?=isset($_SESSION['user']) ? 'history' : 'login'?>" class="Mmenu-item <?= (strpos($URL, "/artuniverse/history") !== false) ? 'Mactive' : '' ?>">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span class="Mitem-title">History</span>
                    <span class="Mtooltip">History</span>
                </a>
                <a  href="<?=$base?>/<?=isset($_SESSION['user']) ? 'settings' : 'login'?>" class="Mmenu-item <?= (strpos($URL, "/artuniverse/settings") !== false) ? 'Mactive' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span class="Mitem-title">Settings</span>
                    <span class="Mtooltip">Settings</span>
                </a>
            </div>
            <?php if(isset($_SESSION['user'])): ?>
                <div style="border: 0;"  class="Mlogout-section">
                    <a href="<?=$base?>/<?=$_SESSION['user']['user_name']?>" class="Muser-profile">
                        <div style="display: flex;" class="Muser-avatar">
                        <?php                   
                            if ($_SESSION['user']['profile_photo'] !== null) {
                                echo '<img id="pphoto" class="imageUserHeader" src="' . $base . '/upload/profilePhoto/' . $profile_photo_header . '?t=' . $timestamp . '" />';
                            } else {
                                echo '<img id="pphoto" class="imageUserHeader" src="' . $base . '/public/img/saturn_background.jpg" />';
                            }
                        ?>
                        </div>
                        <div class="Muser-info">
                            <span class="Muser-name"><?=implode(' ', array_slice(explode(' ', $_SESSION['user']['full_name']), 0, 2))?></span>
                            <span class="Muser-role">@<?=$_SESSION['user']['user_name'] ?></span>
                        </div>
                    </a>
                </div>
            <?php elseif ($URL !== '/artuniverse/login' && $URL !== '/artuniverse/Signup'): ?>
                <a href="<?=$base?>/login"  id="btnNav" class="linkBtnNav">
                    <button class="btnNav" type="button" style="text-decoration: none; color: var(--color5);" >
                        <i class="fas fa-solid fa-right-to-bracket"></i>
                        <span class="Mitem-title">Login</span>
                        <span class="Mtooltip">Login</span>
                    </button>
                </a>
            <?php endif ?>
        <?php if(isset($_SESSION['user'])): ?>
            <div class="Mlogout-section">
                <a href="<?=$base?>/logout.php" class="Mlogout-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="Mitem-title">Sair</span>
                </a>
            </div>
        <?php endif ?>
        </div>
    </div> 
<script>
    console.info = function() {
        var mensagem = arguments[0];
        if (
            mensagem.indexOf("Metro 4 - v") === -1 &&
            mensagem.indexOf("m4q - ") === -1
        ) {
            console.info.apply(console, arguments);
        }
    };


    document.addEventListener('DOMContentLoaded', function() {
        const logoToggle = document.querySelector('.Mlogo-toggle');
        const sidebar = document.querySelector('.Msidebar');
        const menuItems = document.querySelectorAll('.Mmenu-item');
        <?php if(isset($_SESSION['user'])): ?>
            const logoutItem = document.querySelector('.Mlogout-item');
        <?php endif ?>
        const search = document.querySelector('.custom-search-input');
        const searchContainer = document.querySelector('.search-input');
        const searchBtn = document.querySelector('.fa-search');


        // Toggle sidebar with smooth animation
        // LogoToggle: abre e fecha
        logoToggle.addEventListener('click', function() {
            this.classList.toggle('Mactive');
            sidebar.classList.toggle('Mexpanded');
            search.classList.toggle('Mexpanded');
        });

        // Função genérica para abrir sidebar
        function expandSidebar() {
            if (!sidebar.classList.contains('Mexpanded')) {
                sidebar.classList.add('Mexpanded');
                search.classList.add('Mexpanded');
                logoToggle.classList.add('Mactive');
            }
        }

        // SearchContainer e SearchBtn só abrem
        searchContainer.addEventListener('click', expandSidebar);
        searchBtn.addEventListener('click', expandSidebar);
        // Menu item click handler
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                menuItems.forEach(i => i.classList.remove('Mactive'));
                this.classList.add('Mactive');
                
                // On mobile, close sidebar after selecting an item
                if (window.innerWidth <= 768) {
                    logoToggle.classList.remove('Mactive');
                    sidebar.classList.remove('Mexpanded');
                    search.classList.remove('Mactive');
                }
            });
        });

        <?php if(isset($_SESSION['user'])): ?>

            // Logout item click handler
            logoutItem.addEventListener('click', function() {
                // Simula efeito visual de logout
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        <?php endif ?>

        // Improved hover effect for menu items
        menuItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                if (!this.classList.contains('Mactive')) {
                    this.style.transform = 'translateX(4px)';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                if (!this.classList.contains('Mactive')) {
                    this.style.transform = 'translateX(0)';
                }
            });
        });

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !logoToggle.contains(e.target) &&
                !search.contains(e.target) &&
                sidebar.classList.contains('Mexpanded')) {
                logoToggle.classList.remove('Mactive');
                sidebar.classList.remove('Mexpanded');
                search.classList.remove('Mactive');
            }
        });


        <?php if( isset($_SESSION['user']) && strpos($URL, '/artuniverse/'.$_SESSION['user']['user_name']) !== false ): ?>
            function rgbToHex(r, g, b) {
                return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
            }

            const img = document.getElementById('pphoto');
            window.onload = () => {
                const colorThief = new ColorThief();
                const dominantColor = colorThief.getColor(img);
                const hexColor = rgbToHex(dominantColor[0], dominantColor[1], dominantColor[2]);

                document.querySelector(".Muser-avatar").style.boxShadow = "0 0px 12px " + hexColor +"90";
            };
        <?php endif ?>
    });
</script>