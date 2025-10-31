<?php
	require_once '../config.php';

    if (!isset($_GET['token'])) {
        $_SESSION['warning'] = "❌ No token provided.";
        header('Location: '.$base);
        exit;
    }

    $token = $_GET['token'];

    // Verifica se o token é válido e não expirou
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['warning'] = "❌ Invalid or expired token.";
        header('Location: '.$base);
        exit;
    }

    $email = $user['email']; // Pega o e-mail associado ao token


    require_once '../partials/header.php';
	
	if(isset($_SESSION['warning'])){
		require_once '../modalAlert.php';
		unset($_SESSION['warning']);
	}
    if(isset($_SESSION['success'])){
		require_once '../modalSuccess.php';
		unset($_SESSION['success']);
	}
?>

<style>
    .form-container {

        max-width: 500px;
        width: 100%;
        background-color: var(--color3);
        padding: 32px;
        color: var(--color5);
        display: flex;
        flex-direction: column;
        gap: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.084), 0px 2px 3px rgba(0, 0, 0, 0.168);
    }

    .form-container button:active {
        scale: 0.95;
    }

    .form-container .logo-container {
        text-align: center;
        font-weight: 600;
        font-size: 18px;
    }

    .form-container .form {
        display: flex;
        flex-direction: column;
        margin: 2em 0;
    }

    .form-container .form-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .form-container .form-group i{
        top: 50%;
        transform: translate(0%, -50%);
        cursor: pointer;
        display: flex;
        align-items: center;
        right: 3%;
        height: 100%;
        padding: 10px;
    }

    .form-container .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-container .form-group input {
        padding: 7px 50px 7px 20px;
        border-radius: 20px;
        font-size: 16px;
        width: 100%;
        outline: none;
        border: none;
        background-color: var(--color4);
        box-shadow: 3px 3px 8px #00000038, -3px -3px 8px #ffffff1e;
        transition: box-shadow 0.3s ease;
        color: var(--color1);
    }

    .form-container .form-group input::placeholder {
        opacity: 0.5;
    }

    .form-container .form-group input:focus { 
        color: var(--color5);
    }

    .form-container .form-submit-btn {
        border-radius: 50%;
        background-color: var(--color4);
        color: var(--color5);
        border: none;
        outline: none;
        border-radius: 150px;
        padding: 7px 2em;
        font-weight: 500;
        transition: box-shadow 0.3s ease;
        cursor: pointer;
        box-shadow: 3px 3px 8px #00000038, -3px -3px 8px #ffffff1e;
    }

    .form-container .form-submit-btn:hover {
        filter: brightness(1.3);
        transition: ease .1s;
        scale: .98;
    }

    .form-container .link {
        color: var(--color1);
        text-decoration: none;
    }

    .form-container .signup-link {
        align-self: center;
    }

    .form-container .signup-link .link {
        font-weight: 400;
    }

    .form-container .link:hover {
        text-decoration: underline;
    }

    .form-submit-btn {
        background-color: gray;
        cursor: not-allowed;
    }

    /* Quando o botão está ativado */
    #send:not(:disabled) {
        cursor: pointer;
    }

    .fa-1, .fa-2, .fa-shield-halved{
        color: var(--color1);
        margin-right: 10px;
    }

    .textForgot{
        margin: 0;
    }


</style>

<section style="display: flex; width: 100%;" >
    <?php require_once '../sidebar.php' ?>

    <div class="forgotArea">
        <div class="form-container">
            <div class="logo-container">
                Reset password
            </div>


            <form class="form" action="../actions/resetPassword.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="password">New password</label>
                <div class="form-group">
                    <i onclick="togglePSWVisibility()" id="passwordIcon" class="fa-solid fa-eye-login fa-eye-slash"></i>
                    <input minlength="8"  type="password" id="password" name="new_password" placeholder="New password" required>
                </div>
                <label for="confirm_password">Repeat password</label>
                <div class="form-group">
                    <input minlength="8"  type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                </div>

                <button style="margin-top: 1em;" id="send" class="form-submit-btn" type="submit" disabled>Reset password</button>
            </form>


            <p class="signup-link">
                Have an account?
            <a href="<?=$base?>/login" class="signup-link link"> Sign in now</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const pswInput = document.getElementById("password");
            const rptPassword = document.getElementById("confirm_password");
            const sendButton = document.getElementById("send");

            function validateForm() {
                const isPasswordValid = pswInput.value.length >= 8;
                const passwordsMatch = pswInput.value === rptPassword.value;

                if ( isPasswordValid && passwordsMatch) {
                    sendButton.disabled = false;
                    sendButton.style.backgroundColor = "var(--color1)";
                } else {
                    sendButton.disabled = true;
                    sendButton.style.backgroundColor = "gray";
                }
            }

            pswInput.addEventListener("input", validateForm);
            rptPassword.addEventListener("input", validateForm);

            
        });

        function togglePSWVisibility() {
            var passwordInput = document.getElementById("password");
            var passwordIcon = document.getElementById("passwordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

   
</section>


<?php
    require_once '../partials/footer.php';
?>