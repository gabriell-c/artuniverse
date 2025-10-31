<script>
    document.addEventListener("DOMContentLoaded", function () {

        const menuIcon = document.getElementById('sidebar-menu');
        const sidebar = document.getElementById('sidebar');

        menuIcon.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-open');
        });

    })
</script>

<style>
    .notSavedArea{
        height: calc(100vh - 67px);
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--color5);
        width: 90%;
        max-width: 300px;
    }
</style>


<section style="display: flex; width: 100%;" >
<?php require_once __DIR__ . '/../sidebar.php'; ?>
    <div class="notSavedArea">
        <svg style="height: 150px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
            <g data-name="Layer 651" fill="var(--color1)" >
                <path fill="none" stroke="var(--color1)" stroke-linecap="round" stroke-width="2" d="M57,22.55V18a2,2,0,0,0-2-2H32a2,2,0,0,1-2-2h0a2,2,0,0,0-2-2H8a2,2,0,0,0-2,2V52a2,2,0,0,0,2,2"></path>
                <path fill="none" stroke="var(--color1)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M61,23H14a2,2,0,0,0-2,2L6,52a2,2,0,0,0,2,2H55a2,2,0,0,0,2-2l6-27A2,2,0,0,0,61,23Z" class="colorStroke54596e svgStroke"></path>
                <path fill="none" stroke="var(--color1)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M31.86,43A7,7,0,0,1,37,41.07,7,7,0,0,1,42.1,43" class="colorStroke54596e svgStroke"></path>
                <circle cx="45" cy="34" r="2" fill="var(--color1)" class="color54596e svgShape"></circle>
                <circle cx="30" cy="34" r="2" fill="var(--color1)" class="color54596e svgShape"></circle>
                <line x1="2" x2="18" y1="54" y2="54" fill="none" stroke="var(--color1)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="colorStroke54596e svgStroke"></line>
            </g>
        </svg>
        <h1>Start saving</h1>
        <p style="text-align: center;" >you haven't saved any posts yet, how about starting now? <br> :)</p>
        <button id="buttonNoSavedHref" class="btn" type="button" style="color: var(--color5);background: var(--color1);" >Start saving now</button>
    </div>
</section>

<script>
    const btnHref = document.getElementById("buttonNoSavedHref")
      <?php if(isset($_SESSION['user'])) :?>
        btnHref.onclick = () =>{
        window.location.href = "<?=$base?>";
        }
    <?php else :?>
        btnHref.onclick = () =>{
        window.location.href = '<?=$base?>'+"/login";
        }
    <?php endif ?>
</script>


