<div  class="containerBodySettings">
    <div class="boxSettings">
        <h6>Edit profile</h6>
        <form method="POST" action="../actions/updateUser.php">
            <input type="text" required value="<?=$_SESSION['user']['full_name']?>" class="inputEditProfile" name="full_name" maxlength="40" placeholder="Edit full name">
            <input type="text" required value="<?=$_SESSION['user']['user_name']?>" class="inputEditProfile" name="user_name" maxlength="30" placeholder="Edit user name">
            <input type="text" required value="<?=$_SESSION['user']['email']?>" class="inputEditProfile" name="email" placeholder="Edit email">
            <?php 
                $bioUser = !empty($_SESSION['user']['bio_user']) ? htmlspecialchars($_SESSION['user']['bio_user']) : ''; 
            ?>
            <textarea maxlength="150" class="inputEditProfile" name="bio_user" placeholder="Edit bio"><?= $bioUser ?></textarea>
            <input type="tel" required value="<?=$_SESSION['user']['phone_number']?>" class="inputEditProfile" maxlength="12" name="phone_number" placeholder="Edit phone number">
            <input type="text" required value="<?=date('d/m/Y', strtotime($_SESSION['user']['date_of_birth']));?>" maxlength="10" class="inputEditProfile js-date" name="date_of_birth" placeholder="Edit date of birth">
            <div class="sectionGender" style="color: var(--color5);" >
                <div class="form-check">
                    <input class="form-check-input" <?= $_SESSION['user']['gender'] === 'Male' ? 'checked' : ''?> type="radio" name="gender" id="Male" value="Male">
                    <label class="form-check-label" for="Male">
                        Male
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" <?= $_SESSION['user']['gender'] === 'Female' ? 'checked' : ''?> type="radio" name="gender" id="Female" value="Female" >
                    <label class="form-check-label" for="Female">
                        Female
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" <?= $_SESSION['user']['gender'] === 'PNS' ? 'checked' : ''?> type="radio" name="gender" id="PNS" value="PNS">
                    <label class="form-check-label" for="PNS">
                        Prefer not to say
                    </label>
                </div>
            </div>
            
            <?php 
                $linkUser = !empty($_SESSION['user']['link_user']) ? htmlspecialchars($_SESSION['user']['link_user']) : ''; 
            ?>
            <input type="text" value="<?= $linkUser ?>" class="inputEditProfile" name="link_user" placeholder="Edit link">

            <div class="buttonSaveProfile">
                <button type="submit" class="btn btn-success w-100 m-0" >Save</button>
            </div>
        </form>
        <hr style="width: 100%; opacity: .1;" >
        <button id="myBtn" class="btn w-100 " style="color: var(--color5);background: var(--color1);">Change password</button>

    </div>
</div>


<div id="myModal" class="modalChangePassword ">

  <!-- Conteúdo do modal -->
  <form method="post" action="../../actions/changePassword.php" class="modalChangePassword-content">
    <span class="closeModalPassword ">&times;</span>
    <h4>Change password</h4>
    <input type="text" class="inputEditProfile" name="user_name" placeholder="User name">
    <input type="password" class="inputEditProfile" name="current_password" placeholder="Current password">
    <hr style="opacity: .4;">
    <input type="password" class="inputEditProfile" name="new_password" placeholder="New password">
    <input type="password" class="inputEditProfile" name="repeat_new_password" placeholder="Repeat new passowrd">
    <button class="btn btn-success" type="submit">Save</button>
  </form>

</div>




<script>
//   var input = document.querySelectorAll('.js-date')[0];
  
//   var dateInputMask = function dateInputMask(elm) {
//     elm.addEventListener('keypress', function(e) {
//     if(e.keyCode < 47 || e.keyCode > 57) {
//         e.preventDefault();
//     }
    
//     var len = elm.value.length;
    
//     // If we're at a particular place, let the user type the slash
//     // i.e., 12/12/1212
//     if(len !== 1 || len !== 3) {
//         if(e.keyCode == 47) {
//         e.preventDefault();
//         }
//     }
    
//     // If they don't add the slash, do it for them...
//     if(len === 2) {
//         elm.value += '/';
//     }

//     // If they don't add the slash, do it for them...
//     if(len === 5) {
//         elm.value += '/';
//     }
//     });
//   };
      
//   dateInputMask(input);

//   // Obtém os elementos do DOM
//   var modal = document.getElementById("myModal");
//   var btn = document.getElementById("myBtn");
//   var closeBtn = document.getElementsByClassName("closeModalPassword")[0];
//   var body = document.getElementsByTagName("body")[0];

//   // Abre o modal quando o botão for clicado
//   btn.onclick = function() {
//       modal.style.display = "block";
//       body.classList.add("modal-open");
//   }

//   // Fecha o modal quando o "X" for clicado
//   closeBtn.onclick = function() {
//       modal.style.display = "none";
//       body.classList.remove("modal-open");
//   }

//   // Fecha o modal quando o usuário clica fora do modal
//   window.onclick = function(event) {
//       if (event.target == modal) {
//           modal.style.display = "none";
//           body.classList.remove("modal-open");
//       }
//   }

</script>
