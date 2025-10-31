<?php
	require_once '../config.php';
	if(isset($_SESSION['user'])){
		header("Location: ".$base);
        exit();
	}
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
        
        :root {
            --primary: #8a63ff;
            --primary-light: rgba(138, 99, 255, 0.1);
            --dark: #0f0e13;
            --dark-light: #1a1820;
            --light: #f5f3ff;
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
		.btn-primary:hover {
			color: var(--color5) !important;
			background-color: var(--color1) !important;
			border-color: none !important;
		}

		.icon-container {
			width: 85px;
			height: 85px;
			background: linear-gradient(45deg,rgb(193, 118, 255),rgb(137, 0, 250));
			-webkit-mask-image: url('../public/img/saturn.svg');
			mask-image: url('../public/img/saturn.svg');
			-webkit-mask-size: contain;
			mask-size: contain;
			-webkit-mask-repeat: no-repeat;
			mask-repeat: no-repeat;
			-webkit-mask-position: center;
			mask-position: center;
		}

    </style>

<!-- <section style="display: flex;width: 100%;" >
	<div class="content-wrapper" style="display: flex; justify-content: center;">
		<div class="content">
			<div class="login-wrapper shadow-box" style="border-radius: 10px;">
				<div class="company-details ">
					
					<div class="shadow"></div>
					<div class="wrapper-1">
						<div class="logo">
							<img class="logo-icon" src="../public/img/saturn.webp" alt="">
						</div>
						<h1 class="title">Artuniverse</h1>
						<div class="slogan">Welcome to our space! Connect and explore.</div>
					</div>

				</div>
				<div class="login-form ">
					<div class="wrapper-2">
						<div class="form-title">Log in today!</div>
						<div class="form">
							<form method="POST" action="../actions/loginAction.php" style="position: relative;">
								<input autocomplete="off" type="text" required class="userNameLogin" name="UserNameLogin" placeholder="User name">
								<div class="passwordArea">
									<input autocomplete="off" id="passwordInput" type="password" style="padding-right: 45px;" minlength="8" required class="passwordLogin" name="passwordLogin" placeholder="Password">
									<i onclick="togglePasswordVisibility()" id="passwordIcon" class="fa-solid fa-eye-login fa-eye-slash"></i>
								</div>
								<div class="rememberme">
									<input name="rememberMe" value="1" type="checkbox" checked id="switch" /><label class="labeltoglleLogin" for="switch">Toggle</label>
									<p id="rememberMe" class="toggleTextlogin">Remember me</p>
								</div>
								<button type="submit" class="login">Log in</button>
								<br>
								<a href="../forgot_password" class="forgotpassword">Forgot Password?</a>
							</form>

							<hr>
							<p class="DonthavAccount">Don't have an account? <a href="<?= $base ?>/signup"> Sign up</a></p>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>

</section> -->














 <section style="display: flex;width: 100%; justify-content: center; align-items: center;" >

    <!-- Animated star background -->
    <canvas id="starfield"></canvas>
    <?php require_once '../sidebar.php' ?>
    <!-- Main container -->
    <div class="cosmic-bg w-full max-w-md rounded-xl p-8 space-y-8 backdrop-blur-md border border-[rgba(138,99,255,0.1)]">
        <div class="text-center space-y-2">
            <div class="flex justify-center">
				<div class="icon-container"></div>
            </div>
            <h1 class="text-2xl font-bold mt-4" style="color: var(--color5);">Welcome to <span class="gradient-text font-bold">Artuniverse</span></h1>
            <p class="text-gray-400">Connect to your creative universe</p>
        </div>
        
        <form method="POST" action="../actions/loginAction.php" class="space-y-6">
            <div class="space-y-4">
                <div>
                    <label for="userName" style="color: var(--color5);" class="block text-sm font-medium  mb-1">User name</label>
                    <div class="relative">
                        <input autocomplete="off" required name="UserNameLogin" id="userName" type="text" class="input-field w-full bg-[var(--dark-light)]  rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="your user name">
                    </div>
                </div>
                
                <div>
                    <label for="password" style="color: var(--color5);" class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input minlength="8" required name="passwordLogin" id="password" type="password" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="••••••••">
                        <button type="button" class="absolute right-3 top-2.5 text-gray-400">
                            <i class="far fa-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <!-- <div class="flex items-center">
                    <input id="remember-me" type="checkbox" class=" w-4 h-4 rounded bg-[var(--dark-light)] border-[var(--dark-light)] focus:ring-[var(--primary)]">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-400">Remember me</label>
                </div> -->
				
				<label class="relative inline-flex items-center cursor-pointer">
					<input type="checkbox" name="rememberMe" value="1" type="checkbox" checked class="sr-only peer" />
					<div
						class="group peer bg-[var(--dark-light)] rounded-full duration-300 w-9 h-5 
						ring-2 ring-[var(--color4)] peer-checked:ring-[var(--color1)] 
						after:duration-300 after:bg-[var(--color4)] peer-checked:after:bg-[var(--color1)] 
						after:rounded-full after:absolute after:h-4 after:w-4 after:top-0.5 after:left-0.5 
						after:flex after:justify-center after:items-center peer-checked:after:translate-x-4 
						peer-hover:after:scale-95"
					></div>
					<span for="remember-me" class="ml-2 block text-sm text-gray-400">Remember me</span>
				</label>







                <a href="../forgot_password" class="text-sm font-medium text-[var(--primary)] hover:underline">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-primary w-full py-2.5 rounded-lg font-medium text-white">
                Sign In
            </button>
            
            <!-- <div class="relative text-center connect-line">
                <span class="px-2 text-sm text-gray-400 bg-[var(--dark)] relative">or continue with</span>
            </div>
            
            <div class="flex space-x-4">
                <button type="button" class="flex-1 py-2 rounded-lg border border-[var(--dark-light)] flex items-center justify-center hover:bg-[var(--dark-light)] transition-colors">
                    <i class="fab fa-google text-[var(--primary)]"></i>
                </button>
                <button type="button" class="flex-1 py-2 rounded-lg border border-[var(--dark-light)] flex items-center justify-center hover:bg-[var(--dark-light)] transition-colors">
                    <i class="fab fa-apple text-gray-300"></i>
                </button>
                <button type="button" class="flex-1 py-2 rounded-lg border border-[var(--dark-light)] flex items-center justify-center hover:bg-[var(--dark-light)] transition-colors">
                    <i class="fab fa-github text-gray-300"></i>
                </button>
            </div> -->
        </form>
        
        <p class="text-center text-sm text-gray-500">
            Don't have an account? <a href="<?=$base?>/signup" class="font-medium text-[var(--primary)] hover:underline">Sign up</a>
        </p>
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
            
            // Toggle password visibility
            const togglePassword = document.querySelector('[type="password"] + button');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'password') {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        });
    </script>


<?php
    require_once '../partials/footer.php';
?>
