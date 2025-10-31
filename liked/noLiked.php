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

</style>

<session style="display: flex; width: 100%;">
    <?php require_once '../sidebar.php' ?>

    <div class="mainNoLiked" style="color: var(--color5);">
        <label class="likeHeart" style="scale: 1.5; margin-bottom: 2em;">
            <input type="checkbox" />
            <div class="hearthLike"></div>
        </label>
        <h2>Start liking</h2>
        <p style="text-align: center;" >you still haven't liked any post, how about starting now? <br>:)</p>
        <button id="buttonNoLikedHref" type="button" onclick="window.location.href = '<?=$base?>'" class="btn" style="color: var(--color5);background: var(--color1);" >Start liking now</button>
    </div>
</session>

<script>
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

  const btnHref = document.getElementById("buttonNoLikedHref");

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
