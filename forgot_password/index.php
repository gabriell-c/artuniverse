<?php
	require_once '../config.php';
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

<script src="https://cdn.tailwindcss.com"></script>





<style>
       
        :root {
            --primary: #8a63ff;
            --primary-light: rgba(138, 99, 255, 0.1);
            --dark: #0f0e13;
            --dark-light: #1a1820;
            --light: #f5f3ff;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--dark);
            color: var(--light);
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
        
        .connect-line::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-light), transparent);
            z-index: -1;
        }
        
        #starfield {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .step-item {
            position: relative;
            padding-left: 2rem;
        }
        
        .step-item:not(:last-child) {
            margin-bottom: 1.5rem;
        }
        
        .step-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
        }
    </style>

<!-- 
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
        margin-bottom: 1em;
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


</style> -->


<!-- 
    <div class="forgotArea">
        <div class="form-container">
            <div class="logo-container">
            Forgot your password?
            </div>

            <p class="textForgot">Change your password by following the instructions below. This helps keep your new password secure
            <i class="fa-solid fa-shield-halved"></i></p>
            <p class="textForgot">
                <i class="fa-solid fa-1"></i>Please enter your username or email address.<br>
            </p>
            <p class="textForgot">
                <i class="fa-solid fa-2"></i>You will receive a link via email to create a new password.
            </p>

            <form class="form" action="./process_forgot.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <button id="send" class="form-submit-btn" type="submit" disabled>Send Email</button>
            </form>

            

            <style>
                /* Estilo inicial do botão */
                
            </style>


            <p class="signup-link">
            have an account?
            <a href="<?$base?>" class="signup-link link"> Sign up now</a>
            </p>
        </div>
    </div> -->












<section style="display: flex;width: 100%; justify-content: center; align-items: center; margin: 3em auto;" >
    <?php require_once '../sidebar.php' ?>

    <!-- Animated star background -->
    <canvas id="starfield"></canvas>
    
    <!-- Main container -->
    <div class="cosmic-bg w-full max-w-md rounded-xl p-8 space-y-6 backdrop-blur-md border border-[rgba(138,99,255,0.1)]">
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
            <h1 class="text-2xl font-bold mt-4">Forgot <span class="gradient-text font-bold">Password</span></h1>
            <p class="text-gray-400">Change your password by following the instructions below. This helps keep your new password secure</p>
        </div>
        
        <div class="space-y-6">
            <div class="space-y-4">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="pl-2">
                        <p class="text-sm font-medium text-gray-300">Please enter your email address.</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="pl-2">
                        <p class="text-sm font-medium text-gray-300">You will receive a link via email to create a new password.</p>
                    </div>
                </div>
            </div>
            
            <form class="space-y-4" action="./process_forgot.php" method="POST">
                <div>
                    <label id="emailLabel" for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input required name="email" id="email" type="email" class="input-field w-full bg-[var(--dark-light)] border rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="your@email.com">
                </div>
                
                <button id="send" type="submit" class=" w-full py-2.5 rounded-lg bg-[var(--color4)] font-medium text-white mt-4" disabled>
                    Send Reset Link
                </button>
                
                <p class="text-center text-sm text-gray-500">
                    Remember your password? <a href="<?=$base?>/login" class="font-medium text-[var(--primary)] hover:underline">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</section>

    <script>
        // Starfield animation
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('starfield');
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);
            
            // Create stars
            const stars = [];
            const starCount = Math.floor(window.innerWidth * window.innerHeight / 1000);
            
            for (let i = 0; i < starCount; i++) {
                stars.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    radius: Math.random() * 1.2,
                    speed: Math.random() * 0.1,
                    opacity: Math.random()
                });
            }
            
            // Animation loop
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                stars.forEach(star => {
                    ctx.beginPath();
                    ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 255, 255, ${star.opacity})`;
                    ctx.fill();
                    
                    // Move stars to create parallax effect
                    star.y += star.speed;
                    
                    // Reset star when it goes off screen
                    if (star.y > canvas.height) {
                        star.y = 0;
                        star.x = Math.random() * canvas.width;
                    }
                });
                
                requestAnimationFrame(animate);
            }
            
            animate();

            const emailInput = document.getElementById("email");
            const sendButton = document.getElementById("send");

            emailInput.addEventListener("input", () => {
                if (emailInput.value.trim() === "") {
                    sendButton.disabled = true;
                    sendButton.style.backgroundColor = "var(--color4) !important"; // Cor quando desativado
                } else {
                    sendButton.disabled = false;
                    sendButton.style.backgroundColor = "var(--color1) !important"; // Cor quando ativado
                }
            });
        });



    </script>





<?php
    require_once '../partials/footer.php';
?>