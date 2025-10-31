<?php
    require_once "./partials/header.php";
?>

<main>
    <?php require_once './sidebar.php' ?>

    <section class="sectionVideosHome">
        <div class="opitionsMenu">
            <ul>
                <li class="filterBtn activeBtnFilter" data-filter="all">all</li>
                <li class="filterBtn" data-filter="video">video</li>
                <li class="filterBtn" data-filter="audio">audio</li>
                <li class="filterBtn" data-filter="image">image</li>
                <li class="filterBtn" data-filter="user">user</li>
            </ul>
        </div>

        <div class="titleText">
            <h1>Resultados:</h1>
        </div>

        <div id="searchResults">
            <p>Carregando resultados...</p>
        </div> 

    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const resultsContainer = document.getElementById("searchResults");
        const searchParams = new URLSearchParams(window.location.search);
        const searchTerm = searchParams.get("q");
        let allResults = []; // Array para armazenar os resultados originais

        if (searchTerm) {
        fetch(`http://localhost:5000/buscar?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = "";

                if (!Array.isArray(data) || data.length === 0) {
                    resultsContainer.innerHTML = `<p>Nenhum resultado encontrado.</p>`;
                    return;
                }

                allResults = data; // Armazena os dados para reutilização no filtro
                renderResults(allResults);
            })
            .catch(error => {
                console.error("Erro na busca:", error);
                resultsContainer.innerHTML = `<p>Erro ao carregar os resultados.</p>`;
            });
        }

        function renderResults(filteredData) {
            resultsContainer.innerHTML = ""; // Limpa os resultados antes de renderizar

                filteredData.forEach(post => {
                    const userAvatar = post.profile_photo ? `/artuniverse/upload/profilePhoto/${post.profile_photo}` : "/artuniverse/public/img/saturn_background.jpg";
                    const posterPath = post.thumbnail ? post.thumbnail : "/artuniverse/public/img/saturn_background.jpg";
                    const dataAtual = new Date();
                    const dataPassada = new Date(post.created_at);

                    const diffMs = dataAtual - dataPassada;
                    const diffMin = Math.floor(diffMs / 60000);
                    const diffHrs = Math.floor(diffMin / 60);
                    const diffDays = Math.floor(diffHrs / 24);
                    const diffMonths = Math.floor(diffDays / 30);
                    const diffYears = Math.floor(diffMonths / 12);

                    let timePost;
                    if (diffYears > 0) {
                        timePost = `${diffYears} ${diffYears === 1 ? "year" : "years"} ago`;
                    } else if (diffMonths > 0) {
                        timePost = `${diffMonths} ${diffMonths === 1 ? "month" : "months"} ago`;
                    } else if (diffDays > 0) {
                        timePost = `${diffDays} ${diffDays === 1 ? "day" : "days"} ago`;
                    } else if (diffHrs > 0) {
                        timePost = `${diffHrs} ${diffHrs === 1 ? "hour" : "hours"} ago`;
                    } else if (diffMin > 0) {
                        timePost = `${diffMin} ${diffMin === 1 ? "minute" : "minutes"} ago`;
                    } else {
                        timePost = "Recently";
                    }

                    let resultHTML = "";

                    if (post.category === 'post') {
                        resultHTML = `
                            <div class="resultItem" data-type="${post.type}">
                                <a href="<?=$base?>/${post.type === 'video' || post.type === 'audio' ? 'watch' : 'view'}/${post.id_name}" class="thumbAreaResult">
                                    <img src="${posterPath}" alt="poster">
                                </a>
                                <div class="infoItemResultArea">
                                    <a href="<?=$base?>/${post.type === 'video' || post.type === 'audio' ? 'watch' : 'view'}/${post.id_name}"><h1>${post.title}</h1></a>
                                    <p>${post.views} views | ${timePost}</p>
                                    <a href="<?=$base?>/${post.user}" class="userItemResult">
                                        <img src="${userAvatar}" alt="profile_photo">
                                        <h4>${post.user}</h4>
                                    </a>
                                    <p>${post.description ? post.description.slice(0, 100) + "..." : "Sem descrição disponível."}</p>
                                </div>
                            </div>
                        `;
                    } else if (post.category === 'user') {
                        resultHTML = `
                            <div class="resultItem" data-type="user">
                                <a href="<?=$base?>/${post.user_name}" class="profileAreaResult">
                                    <img src="${userAvatar}" alt="poster">
                                </a>
                                <div class="infoItemResultArea">
                                    <a href="<?=$base?>/${post.user_name}"><h1>${post.full_name}</h1></a>
                                    <p>@${post.user_name} | ${post.followers} seguidores</p>
                                    <p>${post.bio ? post.bio.slice(0, 100) + "..." : "Sem biografia disponível."}</p>
                                </div>
                            </div>
                        `;
                    }

                    resultsContainer.insertAdjacentHTML("beforeend", resultHTML);
                });
            }

            // Adicionando funcionalidade aos botões de filtro
           // Adicionando funcionalidade aos botões de filtro
            const filterButtons = document.querySelectorAll(".filterBtn");

            filterButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const filter = this.getAttribute("data-filter");

                    document.querySelectorAll(".resultItem").forEach(item => {
                        if (filter === "all" || item.getAttribute("data-type") === filter) {
                            item.style.display = "flex";
                        } else {
                            item.style.display = "none";
                        }
                    });

                    // Remover a classe ativa de todos os botões e adicionar ao botão clicado
                    filterButtons.forEach(btn => btn.classList.remove("activeBtnFilter"));
                    this.classList.add("activeBtnFilter");
                });
            });

        });

    </script>

</main>

<?php require_once "./partials/footer.php"; ?>
