<style>
    .modalAlert {
        display: none;
        position: fixed;
        z-index: 4;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modalAlert-content {
        background-color: var(--color3);
        margin: 10% auto;
        padding: 20px;
        border: 1px solid var(--color4);
        width: 60%;
        max-width: 500px;
        border-radius: 4px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
        color: var(--color5);
    }

    .modalAlert-content h4{
        font-size: 25px;
        font-weight: bold;
    }

    .modalAlert-content span{
        font-size: 18px;
        font-weight: 600;
    }

    .closeModalalert {
        color: var(--color5);
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .closeModalalert:hover,
    .closeModalalert:focus {
        filter: brightness(.7);
    }

    .modalAlert.open body {
        overflow: hidden;
        display: block;
    }
    .modal-open {
        display: block;
    }

</style>

<div id="modalAlert" class="modalAlert modal-open ">

  <!-- Conteúdo do modal -->
  <div class="modalAlert-content">
    <span class="closeModalalert">&times;</span>
    <h4 style="color: #00ff22;" >Success!</h4>
    <hr style="opacity: .4;">
    <span style="color: var(--color5);"><?= $_SESSION['success'] ?></span>
  </div>

</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var modalAlert = document.getElementById("modalAlert");
    var closeBtnAlert = document.querySelector(".closeModalalert");
    var bodyAlert = document.getElementsByTagName("body")[0];
    
    // Adiciona a classe para bloquear o fundo enquanto o modal está aberto
    bodyAlert.classList.add("modal-open");

    // Fecha o modal quando o "X" for clicado
    closeBtnAlert.onclick = function() {
      fecharModal();
    };

    // Fecha o modal quando o usuário clica fora do modal
    window.onclick = function(event) {
      if (event.target === modalAlert) {
        fecharModal();
      }
    };

    // Fecha o modal automaticamente após 3 segundos
    setTimeout(fecharModal, 4000);

    function fecharModal() {
      modalAlert.style.display = "none";
      bodyAlert.classList.remove("modal-open");
    }
  });
</script>
