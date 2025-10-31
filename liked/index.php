<?php
    require_once '../partials/header.php';
    require_once '../loadingElement.php';
?>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>

        .header-bg {
            /* background: var(--color4); */
            /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); */
        }
        
        .heart-beat {
            animation: heartBeat 1.5s infinite !important;
        }
        
        @keyframes heartBeat {
            0% { transform: scale(1); }
            25% { transform: scale(1.1); }
            50% { transform: scale(1); }
            75% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .post-item {
            position: relative;
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .post-item:hover {
            transform: translateY(-5px);
            transition: all ease-in-out .3s;
        }

        .post-title-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: linear-gradient(to bottom, rgba(11, 9, 12, 0.9), transparent);
            z-index: 2;
        }

        .tab-active {
            color: var(--color1);
            border-bottom: 2px solid var(--color1);
        }

        .type-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 3;
            background-color: rgba(11, 9, 12, 0.7);
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
        }

        .post-image {
            height: 300px;
            object-fit: cover;
            width: 100%;
        }

        .empty-state {
            min-height: 50vh;
        }

        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Enhanced select styling */
        .custom-select {
            position: relative;
            width: 200px;
        }

        .custom-select select {
            appearance: none;
            -webkit-appearance: none;
            width: 100%;
            padding: 0.5rem 2.5rem 0.5rem 1rem;
            background-color: var(--color4);
            border: 1px solid var(--color3);
            border-radius: 0.5rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-select select:focus {
            outline: none;
            border-color: var(--color1);
            box-shadow: 0 0 0 2px #9673ff;
        }

        .custom-select::after {
            content: "▼";
            font-size: 0.75rem;
            color: var(--color5);
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            transition: all ease-in-out .3s;
        }

        /* Tab animations */
        .tab-btn {
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--color1);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .tab-active::after {
            transform: scaleX(1);
            transition: all ease-in-out .3s;
        }

        .security-container {
            animation: fadeIn 0.6s ease-out !important;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .security-icon {
            text-shadow: 0 0 15px rgba(150, 115, 255, 0.7);
            animation: pulse 2s infinite !important;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .btn-login {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(150, 115, 255, 0.2);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(150, 115, 255, 0.3);
        }
        
        .btn-register {
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 255, 255, 0.1);
        }

        /* Fade animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(10px); }
        }

        @media (min-width: 1024px) {
            .posts-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 1023px) and (min-width: 640px) {
            .posts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 639px) {
            .posts-grid {
                grid-template-columns: 1fr;
            }
            
            .custom-select {
                width: 100%;
            }
        }
    </style>
</head>

<section style="display: flex;width: 100%; " >
    <?php require_once '../sidebar.php' ?>
    <div class="container h-[full] w-[90%] max-w-[1000px] px-4 py-8">

        <div class="mb-[15px] header-bg container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto text-center">
                <div class="w-16 h-16 rounded-full bg-[var(--color5)] flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-[var(--color1)] text-2xl"></i>
                </div>
                <h1 style="display: flex;" class="justify-center text-3xl text-center md:text-4xl font-bold mb-3 text-white">Your Favorite Posts</h1>
                <p class="text-lg mb-6 text-gray-400">
                    All the content you've loved, collected in one special place.
                </p>
                <div class="flex justify-center space-x-1">
                    <span class="inline-block w-8 h-1 bg-[var(--color5)]/30 rounded-full"></span>
                    <span class="inline-block w-12 h-1 bg-[var(--color5)] rounded-full"></span>
                    <span class="inline-block w-8 h-1 bg-[var(--color5)]/30 rounded-full"></span>
                </div>
            </div>
        </div>

        <!-- Tabs and Filters -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <!-- Enhanced Tabs with Icons -->
            <div class="flex space-x-4 border-b border-gray-800 w-full md:w-auto">
                <button class="tab-btn flex items-center gap-2 py-2 px-1 font-medium tab-active" data-filter="all">
                    <i class="fas fa-th-large text-purple-400"></i>
                    <span>All</span>
                </button>
                <button class="tab-btn flex items-center gap-2 py-2 px-1 font-medium text-gray-400 hover:text-white transition" data-filter="image">
                    <i class="fas fa-image text-green-400"></i>
                    <span>Images</span>
                </button>
                <button class="tab-btn flex items-center gap-2 py-2 px-1 font-medium text-gray-400 hover:text-white transition" data-filter="video">
                    <i class="fas fa-video text-red-400"></i>
                    <span>Videos</span>
                </button>
                <button class="tab-btn flex items-center gap-2 py-2 px-1 font-medium text-gray-400 hover:text-white transition" data-filter="audio">
                    <i class="fas fa-music text-blue-400"></i>
                    <span>Audio</span>
                </button>
            </div>

            <!-- Enhanced Sort -->
            <div class="flex items-center gap-3 w-full md:w-auto">
                <span class="text-gray-400 flex items-center gap-1">
                    <i class="fas fa-sort-amount-down"></i>
                    <span>Sort by:</span>
                </span>
                <div class="custom-select">
                    <select id="sort-select" class="bg-gray-800 text-white rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="newest">Newest first</option>
                        <option value="oldest">Oldest first</option>
                    </select>
                </div>
            </div>
        </div>


        <?php if(!isset($_SESSION['user'])): ?>
            <div class="m-auto security-container max-w-md w-full rounded-xl overflow-hidden p-8 text-center">
                <div class="security-icon bg-purple-600/20 p-5 rounded-full inline-block mb-6">
                    <i class="fas fa-lock text-purple-400 text-4xl"></i>
                </div>
                
                <h2 class="text-2xl font-bold text-white mb-3">Authentication Required</h2>
                
                <div class="bg-gray-800/50 rounded-lg p-4 mb-6 border border-gray-700">
                    <p class="text-gray-300 mb-2">
                        <i class="fas fa-exclamation-circle text-yellow-400 mr-2"></i>
                        To view your liked content, you must be logged in.
                    </p>
                    <p class="text-sm text-gray-400">
                        This protects your personal data and preferences.
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <a href="/login" class="btn-login bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium flex-1">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="<?=$base?>/signup" class="btn-register bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex-1">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                </div>
                
                <div class="mt-6 text-sm text-gray-500">
                    <i class="fas fa-shield-alt mr-1"></i> Your security is our priority
                </div>
            </div>
        <?php else: ?>

            <!-- Posts Grid -->
            <div class="posts-grid" id="posts-grid">
                
                <?php

                try {
                    // First get all liked post IDs for the current user
                    $sql = "SELECT id_post FROM post_likes WHERE id_user = :id_user";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id_user', $_SESSION['user']['id']);
                    $stmt->execute();
                    $likedPostIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

                    if (!empty($likedPostIds)) {
                        // Convert array of IDs to comma-separated string for SQL IN clause
                        $placeholders = implode(',', array_fill(0, count($likedPostIds), '?'));

                        // Get all posts that are liked and not archived
                        $sqlP = "SELECT * FROM allposts WHERE id IN ($placeholders) AND archive = 'false' ORDER BY creation_date DESC";
                        $stmtP = $pdo->prepare($sqlP);
                        $stmtP->execute($likedPostIds);
                        $posts = $stmtP->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($posts as $post) {
                            $postType = $post['type'];
                            $postIdName = $post['id_name'];
                            $postTitle = htmlspecialchars($post['title']);

                            // Determine the appropriate path based on post type
                            if ($postType === 'image') {
                                $imagePath = $base . '/public/storage/photos/' . $post['file'];
                                $linkUrl = $base . '/view/' . $postIdName;
                            } elseif ($postType === 'video') {
                                $imagePath = !empty($post['poster'])
                                    ? $base . '/public/storage/posterVideo/' . $post['poster']
                                    : $base . '/public/img/saturn_background.jpg';
                                $linkUrl = $base . '/watch/' . $postIdName;
                            } elseif ($postType === 'audio') {
                                $imagePath = !empty($post['poster'])
                                    ? $base . '/public/storage/posterAudio/' . $post['poster']
                                    : $base . '/public/img/saturn_background.jpg';
                                $linkUrl = $base . '/watch/' . $postIdName;
                            }

                            // Determine icon based on type
                            $typeIcon = '';
                            $typeClass = '';
                            if ($postType === 'image') {
                                $typeIcon = 'fa-image';
                                $typeClass = 'text-green-400';
                            } elseif ($postType === 'video') {
                                $typeIcon = 'fa-video';
                                $typeClass = 'text-red-400';
                            } elseif ($postType === 'audio') {
                                $typeIcon = 'fa-music';
                                $typeClass = 'text-blue-400';
                            }
                            ?>
                            <div class="post-item rounded-lg overflow-hidden bg-gray-800 hover:bg-gray-700 transition duration-300" data-type="<?= $postType ?>" data-date="<?= strtotime($post['creation_date']) ?>">
                                <a href="<?= $linkUrl ?>" class="block relative flex-grow">
                                    <div class="post-title-overlay">
                                        <h3 class="text-white font-medium truncate"><?= $postTitle ?></h3>
                                    </div>
                                    <div class="type-badge flex items-center">
                                        <i class="fas <?= $typeIcon ?> <?= $typeClass ?> mr-1"></i>
                                        <span class="text-white capitalize"><?= $postType ?></span>
                                    </div>
                                    <img src="<?= $imagePath ?>" alt="<?= $postTitle ?>" class="post-image w-full">
                                </a>
                            </div>
                            <?php
                        }
                    } else {
                        // No liked posts
                        ?>
                        <div class="empty-state col-span-3 flex flex-col items-center justify-center py-16">
                            <div class="bg-gray-800 p-6 rounded-full mb-4">
                                <i class="fas fa-heart text-4xl text-purple-500"></i>
                            </div>
                            <h3 class="text-xl font-medium mb-2">No liked posts yet</h3>
                            <p class="text-gray-400 text-center max-w-md">When you like posts, they'll appear here for easy access.</p>
                        </div>
                        <?php
                    }
                } catch (PDOException $e) {
                    // Error handling
                    ?>
                    <div class="col-span-3 bg-red-900/20 border border-red-700 rounded-lg p-4">
                        <p class="text-red-400">Error loading liked posts. Please try again later.</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        <?php endif ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab filtering with enhanced UI
            const tabButtons = document.querySelectorAll('.tab-btn');
            const posts = document.querySelectorAll('.post-item');
            const postsGrid = document.getElementById('posts-grid');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active tab with smooth transition
                    tabButtons.forEach(btn => {
                        btn.classList.remove('tab-active', 'text-white');
                        btn.classList.add('text-gray-400');
                    });

                    this.classList.add('tab-active', 'text-white');
                    this.classList.remove('text-gray-400');

                    const filter = this.dataset.filter;

                    // Filter posts with fade animation
                    let hasVisiblePosts = false;
                    let visiblePosts = 0;
                    
                    posts.forEach(post => {
                        if (filter === 'all' || post.dataset.type === filter) {
                            post.style.display = 'none';
                            setTimeout(() => {
                                post.style.display = 'block';
                                post.style.animation = 'fadeIn 0.5s ease';
                            }, visiblePosts * 50);
                            visiblePosts++;
                            hasVisiblePosts = true;
                        } else {
                            post.style.animation = 'fadeOut 0.3s ease';
                            setTimeout(() => {
                                post.style.display = 'none';
                            }, 300);
                        }
                    });

                    // Show empty state if no posts match filter
                    const emptyState = document.querySelector('.empty-state');
                    
                    if (!hasVisiblePosts) {
                        // Remove existing empty state if any
                        if (emptyState) {
                            emptyState.remove();
                        }
                        
                        // Create new empty state
                        const emptyStateDiv = document.createElement('div');
                        emptyStateDiv.className = 'empty-state col-span-3 flex flex-col items-center justify-center py-16';
                        emptyStateDiv.innerHTML = `
                            <div class="bg-gray-800 p-6 rounded-full mb-4">
                                <i class="fas fa-heart text-4xl text-purple-500"></i>
                            </div>
                            <h3 class="text-xl font-medium mb-2">No ${filter === 'all' ? 'liked' : filter} posts yet</h3>
                            <p class="text-gray-400 text-center max-w-md">When you like ${filter === 'all' ? '' : filter + ' '}posts, they'll appear here.</p>
                        `;
                        
                        // Insert empty state at the beginning of the grid
                        postsGrid.prepend(emptyStateDiv);
                    } else if (emptyState && filter !== 'all') {
                        // Remove empty state if we now have visible posts
                        emptyState.remove();
                    }
                });
            });

                // CHAVE: Disparar click na aba ativa ao carregar
            const defaultActiveTab = document.querySelector('.tab-btn.tab-active');
            if (defaultActiveTab) {
                defaultActiveTab.click();
            }

            // Enhanced sort functionality
            const sortSelect = document.getElementById('sort-select');
            sortSelect.addEventListener('change', function () {
                const sortOrder = this.value;
                const postsContainer = document.getElementById('posts-grid');
                const postsArray = Array.from(document.querySelectorAll('.post-item')).filter(el => el.offsetParent !== null);
                
                postsContainer.style.opacity = '0.5';
                postsContainer.style.transition = 'opacity 0.3s ease';

                setTimeout(() => {
                    postsArray.sort((a, b) => {
                        const dateA = parseInt(a.dataset.date);
                        const dateB = parseInt(b.dataset.date);

                        return sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
                    });

                    postsArray.forEach(post => {
                        postsContainer.appendChild(post);
                    });

                    postsContainer.style.opacity = '1';
                }, 300);
            });

        });

    </script>
</section>



<?php require_once '../partials/footer.php' ?>