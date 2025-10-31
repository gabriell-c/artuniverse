<?php
  require_once __DIR__ .'/partials/header.php';
 ?>


<section style="display: flex; width: 100%;" >
  <?php require_once __DIR__ .'/sidebar.php' ?>
  <div class="wrapper">
    <div class="text_group">
      <p class="text_404">404</p>
      <p class="text_lost">The page you are looking for <br />has been lost in space.</p>
      <button id="goBack" type="button" class="btn" style="background-color: var(--color1); color: var(--color5);" >Back to home</button>
    </div>
    <div class="window_group">
      <div class="window_404">
        <div class="stars"></div>
      </div>
    </div>
  </div>
</section>

<script>
    let starContainer = document.querySelector(".stars");

for (let i = 0; i < 100; i++) {
  starContainer.innerHTML += `<div class="star"></div>`;
}

document.getElementById('goBack').addEventListener('click', function() {
        if (document.referrer) {
            window.location.href = document.referrer; // Redireciona para a página anterior
        } else {
            window.location.href = '/'; // Se não houver página anterior, vai para a home
        }
    });

</script>

<?php require_once __DIR__."/partials/footer.php" ?>
