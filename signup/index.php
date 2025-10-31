<?php
	require_once '../config.php';
	if(isset($_SESSION['user'])){
		header("Location: ".$base);
        exit();
	}
    require_once '../partials/header.php';
	
	if(isset($_SESSION['warning'])){
		require_once '../../modalAlert.php';
		unset($_SESSION['warning']);
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
	
	select {
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23c37aff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
		background-repeat: no-repeat;
		background-position: right 0.75rem center;
		background-size: 1em;
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















/* From Uiverse.io by 00Kubi */ 
.cyber-checkbox {
  --checkbox-size: 24px;
  --checkbox-color: #5c67ff;
  --checkbox-check-color: #ffffff;
  --checkbox-hover-color: #4c57ef;
  --checkbox-spark-offset: -20px;

  position: relative;
  display: inline-block;
  cursor: pointer;
  user-select: none;
}

.cyber-checkbox input {
  display: none;
}

.cyber-checkbox__mark {
  position: relative;
  display: inline-block;
  width: var(--checkbox-size);
  height: var(--checkbox-size);
}

.cyber-checkbox__box {
  position: absolute;
  inset: 0;
  border: 2px solid var(--checkbox-color);
  border-radius: 4px;
  background: transparent;
  transition: all 0.2s ease;
}

.cyber-checkbox__check {
  position: absolute;
  inset: 0;
  padding: 2px;
  stroke: var(--checkbox-check-color);
  stroke-width: 2px;
  stroke-linecap: round;
  stroke-linejoin: round;
  fill: none;
  transform: scale(0);
  transition: transform 0.2s ease;
}

.cyber-checkbox__effects {
  position: absolute;
  inset: var(--checkbox-spark-offset);
  pointer-events: none;
}

.cyber-checkbox__spark {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 2px;
  height: 2px;
  background: var(--checkbox-color);
  border-radius: 50%;
  opacity: 0;
  transform-origin: center center;
}

/* Hover */
.cyber-checkbox:hover .cyber-checkbox__box {
  border-color: var(--checkbox-hover-color);
  box-shadow: 0 0 0 2px rgba(92, 103, 255, 0.1);
}

/* Checked */
.cyber-checkbox input:checked + .cyber-checkbox__mark .cyber-checkbox__box {
  background: var(--checkbox-color);
  border-color: var(--checkbox-color);
}

.cyber-checkbox input:checked + .cyber-checkbox__mark .cyber-checkbox__check {
  transform: scale(1);
}

/* Spark Animation */
.cyber-checkbox input:checked + .cyber-checkbox__mark .cyber-checkbox__spark {
  animation: spark 0.4s ease-out !important;
}

.cyber-checkbox__spark:nth-child(1) {
  transform: rotate(0deg) translateX(var(--checkbox-spark-offset));
}
.cyber-checkbox__spark:nth-child(2) {
  transform: rotate(90deg) translateX(var(--checkbox-spark-offset));
}
.cyber-checkbox__spark:nth-child(3) {
  transform: rotate(180deg) translateX(var(--checkbox-spark-offset));
}
.cyber-checkbox__spark:nth-child(4) {
  transform: rotate(270deg) translateX(var(--checkbox-spark-offset));
}

@keyframes spark {
  0% {
    opacity: 0;
    transform: scale(0) rotate(0deg) translateX(var(--checkbox-spark-offset));
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0;
    transform: scale(1) rotate(0deg)
      translateX(calc(var(--checkbox-spark-offset) * 1.5));
  }
}

/* Active */
.cyber-checkbox:active .cyber-checkbox__box {
  transform: scale(0.9);
}

/* Focus */
.cyber-checkbox input:focus + .cyber-checkbox__mark .cyber-checkbox__box {
  box-shadow: 0 0 0 4px rgba(92, 103, 255, 0.2);
}

.cyber-checkbox__particles {
  position: absolute;
  inset: -50%;
  pointer-events: none;
}

.cyber-checkbox__particles div {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 3px;
  height: 3px;
  border-radius: 50%;
  background: var(--checkbox-color);
  opacity: 0;
}

/* Particle animations for check */
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-1 {
  animation: particle-1 0.4s ease-out forwards !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-2 {
  animation: particle-2 0.4s ease-out forwards 0.1s !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-3 {
  animation: particle-3 0.4s ease-out forwards 0.15s !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-4 {
  animation: particle-4 0.4s ease-out forwards 0.05s !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-5 {
  animation: particle-5 0.4s ease-out forwards 0.12s !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-6 {
  animation: particle-6 0.4s ease-out forwards 0.08s !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-7 {
  animation: particle-7 0.4s ease-out forwards 0.18s !important;
}
.cyber-checkbox input:checked + .cyber-checkbox__mark .particle-8 {
  animation: particle-8 0.4s ease-out forwards 0.15s !important;
}

/* Particle animations for uncheck */
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-1 {
  animation: particle-out-1 0.4s ease-out forwards !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-2 {
  animation: particle-out-2 0.4s ease-out forwards 0.1s !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-3 {
  animation: particle-out-3 0.4s ease-out forwards 0.15s !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-4 {
  animation: particle-out-4 0.4s ease-out forwards 0.05s !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-5 {
  animation: particle-out-5 0.4s ease-out forwards 0.12s !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-6 {
  animation: particle-out-6 0.4s ease-out forwards 0.08s !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-7 {
  animation: particle-out-7 0.4s ease-out forwards 0.18s !important;
}
.cyber-checkbox input:not(:checked) + .cyber-checkbox__mark .particle-8 {
  animation: particle-out-8 0.4s ease-out forwards 0.15s !important;
}

/* Particle keyframes for check */
@keyframes particle-1 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(-20px, -20px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-2 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(20px, -20px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-3 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(20px, 20px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-4 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(-20px, 20px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-5 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(-30px, 0px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-6 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(30px, 0px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-7 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0px, -30px) scale(1);
    opacity: 0;
  }
}

@keyframes particle-8 {
  0% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0px, 30px) scale(1);
    opacity: 0;
  }
}

/* Particle keyframes for uncheck */
@keyframes particle-out-1 {
  0% {
    transform: translate(-20px, -20px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-2 {
  0% {
    transform: translate(20px, -20px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-3 {
  0% {
    transform: translate(20px, 20px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-4 {
  0% {
    transform: translate(-20px, 20px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-5 {
  0% {
    transform: translate(-30px, 0px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-6 {
  0% {
    transform: translate(30px, 0px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-7 {
  0% {
    transform: translate(0px, -30px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}

@keyframes particle-out-8 {
  0% {
    transform: translate(0px, 30px) scale(1);
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(0, 0) scale(0);
    opacity: 0;
  }
}




</style>

<!-- 
<div class="content-wrapper" style=" display: flex; justify-content: center;" >
	<div class="content">
		<div class="login-wrapper shadow-box" style="border-radius: 10px; max-width: 900px;">
			<div class="company-details ">
				
				<div class="shadow"></div>
				<div class="wrapper-1">
					<div class="logo">
						<img class="logo-icon" src="../public/img/saturn.webp" alt="">
					</div>
					<h1 class="title">Artuniverse</h1>
					<div class="slogan">Welcome to the place where ideas meet! Sign up now and become part of an exceptional collaboration platform.</div>
				</div>

			</div>
			<div class="login-form ">
				<div class="wrapper-2">
					<div class="form-title" style="margin-bottom: 1em;">sign up today!</div>
					<div class="form">
						<form action="<?php echo $base ?>/actions/signupAction.php" method="POST" style="position: relative;">

							<input autocomplete="off" type="text" required class="inputSignup" maxlength="40" name="FullNameSignup" placeholder="Full name">

							<input autocomplete="off" type="text" required class="inputSignup" maxlength="30" name="UserNameSignup" placeholder="User name">

							<input autocomplete="off" type="text" required class="inputSignup" name="EmailSignup" maxlength="254" placeholder="Email">

							<input autocomplete="off" type="phone" required class="inputSignup" name="PhoneSignup" placeholder="Phone" maxlength="12">
							
							<div class="passwordAerea">
								<input autocomplete="off" id="passwordInput" type="password" style="padding-right: 45px;" minlength="8" required class="inputSignup" name="passwordLogin" placeholder="Password">
								<i onclick="togglePasswordVisibility()" id="passwordIcon" class="fa-solid fa-eye-login fa-eye-slash"></i>
							</div>

							<div class="date-inputs">
								<input autocomplete="off" type="number" min="1" max="31" placeholder="Day" name="day" id="day">
								<div class="custom-select">
									<select autocomplete="off" id="custom-select" name="month">
									<option value="" selected disabled>Month</option>
									<option value="01">January </option>
									<option value="02">February </option>
									<option value="03">March </option>
									<option value="04">April </option>
									<option value="05">May </option>
									<option value="06">June </option>
									<option value="07">July </option>
									<option value="08">August </option>
									<option value="09">September </option>
									<option value="10">October </option>
									<option value="11">November </option>
									<option value="12">December </option>
									</select>
									<i class="fas fa-chevron-down arrow-icon"></i>
								</div>
								<input autocomplete="off" type="number" min="1923" max="<?php echo date("Y") ?>" placeholder="Year" name="year" id="year">
							</div>

							<div class="sectionGender">
								<div class="labelopitionGender">
									<input type="radio" required class="option-input radio" id="Male" name="gender" value="Male" />
									<label for="Male">Male</label>
								</div>
								<div class="labelopitionGender">
									<input type="radio" required class="option-input radio" id="Female" name="gender" value="Female" />
									<label for="Female">Female</label>
								</div>
								<div class="labelopitionGender">
									<input type="radio" required class="option-input radio" id="PNS" name="gender" value="PNS" />
									<label for="PNS">prefer not to say</label>
								</div>
							</div>



						    <div class="iagree">
								<input required type="checkbox" id="checkbox" />
								<label for="checkbox" class="checkmark"></label>
								<span>When signing up, you agree to our Terms, Privacy Policy, and Cookie Policy.</span>								
							</div>
							<button style="margin-top: 10px;" type="submit" class="login">Sign up</button>
						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div> -->

















<section style="display: flex;width: 100%; justify-content: center; align-items: center; margin: 3em auto;" >

    <!-- Animated star background -->
    <canvas id="starfield"></canvas>
    
    <!-- Main container -->
	<?php require_once '../sidebar.php' ?>

    <div class="cosmic-bg w-full max-w-md rounded-xl p-8 space-y-6 backdrop-blur-md border border-[rgba(138,99,255,0.1)]">
        <div class="text-center space-y-2">
            <div class="flex justify-center">
				<!-- <img src="../public/img/saturn.svg" alt="Ícone" class="gradient-img"> -->
				 <div class="icon-container"></div>
            </div>
            <h1 class="text-2xl font-bold mt-4">Join <span class="gradient-text font-bold">Artuniverse</span></h1>
            <p class="text-gray-400">Create your account</p>
        </div>
        
        <form action="<?php echo $base ?>/actions/signupAction.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="fullname" class="block text-sm font-medium mb-1">Full Name</label>
                    <input name="FullNameSignup" autocomplete="off" required id="fullname" type="text" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="Full name">
                </div>
                
                <div>
                    <label for="username" class="block text-sm font-medium mb-1">Username</label>
                    <input name="UserNameSignup" autocomplete="off" required id="username" type="text" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="User Name">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input name="EmailSignup" autocomplete="off" required id="email" type="email" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="your@email.com">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium mb-1">Phone</label>
                    <input name="PhoneSignup" autocomplete="off" required id="phone" type="phone" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="(12) 93456-7890">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input name="passwordLogin" autocomplete="off" required id="password" type="password" class="input-field w-full bg-[var(--dark-light)]  rounded-lg py-2.5 px-4 text-white focus:outline-none" placeholder="••••••••">
                        <button type="button" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-200">
                            <i class="far fa-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Date of Birth</label>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <select autocomplete="off" required name="day" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none">
                                <option value="" disabled selected>Day</option>
                                <script>
                                    for(let i = 1; i <= 31; i++) {
                                        document.write(`<option value="${i}">${i}</option>`);
                                    }
                                </script>
                            </select>
                        </div>
                        <div>
                            <select autocomplete="off" required name="month" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none">
                                <option value="" disabled selected>Month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div>
                            <select autocomplete="off" required name="year" class="input-field w-full bg-[var(--dark-light)] rounded-lg py-2.5 px-4 text-white focus:outline-none">
                                <option value="" disabled selected>Year</option>
                                <script>
                                    const currentYear = new Date().getFullYear();
                                    for(let i = currentYear; i >= currentYear - 100; i--) {
                                        document.write(`<option value="${i}">${i}</option>`);
                                    }
                                </script>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Gender</label>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <input type="radio" id="male" name="gender" value="Male" class="hidden peer">
                            <label required for="male" class="flex items-center justify-center w-full py-2 px-4 text-gray-300 bg-[var(--dark-light)]  rounded-lg cursor-pointer peer-checked:bg-[var(--color1)] peer-checked:border-[var(--primary)] peer-checked:text-white">
                                <span class="text-sm">Male</span>
                            </label>
                        </div>
                        <div>
                            <input required type="radio" id="female" name="gender" value="Female" class="hidden peer">
                            <label for="female" class="flex items-center justify-center w-full py-2 px-4 text-gray-300 bg-[var(--dark-light)] rounded-lg cursor-pointer peer-checked:bg-[var(--color1)] peer-checked:border-[var(--primary)] peer-checked:text-white">
                                <span class="text-sm">Female</span>
                            </label>
                        </div>
                        <div>
							<input type="radio" required id="PNS" name="gender" value="PNS" class="hidden peer">
                            <label for="PNS" class="flex items-center justify-center w-full py-2 px-4 text-gray-300 bg-[var(--dark-light)] rounded-lg cursor-pointer peer-checked:bg-[var(--color1)] peer-checked:border-[var(--primary)] peer-checked:text-white">
                                <span class="text-sm">Prefer not to say</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center mt-2">
					
                    <div class="flex items-center h-5">
						
						<label class="cyber-checkbox">
						<input required id="terms" type="checkbox" />
						<span class="cyber-checkbox__mark">
							<div class="cyber-checkbox__box">
							<svg class="cyber-checkbox__check" viewBox="0 0 12 10">
								<polyline points="1.5 6 4.5 9 10.5 1"></polyline>
							</svg>
							</div>
							<div class="cyber-checkbox__effects">
							<div class="cyber-checkbox__spark"></div>
							<div class="cyber-checkbox__spark"></div>
							<div class="cyber-checkbox__spark"></div>
							<div class="cyber-checkbox__spark"></div>
							</div>
							<div class="cyber-checkbox__particles">
							<div class="particle-1"></div>
							<div class="particle-2"></div>
							<div class="particle-3"></div>
							<div class="particle-4"></div>
							<div class="particle-5"></div>
							<div class="particle-6"></div>
							<div class="particle-7"></div>
							<div class="particle-8"></div>
							</div>
						</span>
						</label>

                    </div>
                    <label for="terms" class="ml-2 block text-sm text-gray-400">
                        When signing up, you agree to our <a href="#" class="text-[var(--primary)] hover:underline">Terms</a>, <a href="#" class="text-[var(--primary)] hover:underline">Privacy Policy</a>, and <a href="#" class="text-[var(--primary)] hover:underline">Cookie Policy</a>.
                    </label>
                </div>
            </div>
            
            <button type="submit" class="bbg-[var(--color1)] hoverbg-[var(--color1)]: w-full py-2.5 rounded-lg font-medium text-white mt-4">
                Sign Up
            </button>
            
            <p class="text-center text-sm text-gray-500">
                Already have an account? <a href="<?= $base ?>/login" class="font-medium text-[var(--primary)] hover:underline">Sign in</a>
            </p>
        </form>
    </div>

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