<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

//  ================== HEADER ================== 

include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');
?>

	<section class="flex-row jc-center">
		<div class="container-login">
			<div class="form-box login">
				<form class="form-login" action="">
					<h1 class="underline-hug">Se connecter</h1>
					<div class="input-box">
						<input type="text" placeholder="Adresse mail" required />
						<img src="/assets/fo/img/icons/mail.png" alt="">
					</div>
					<div class="input-box">
						<input type="password" placeholder="Mot de passe" required />
						<img src="/assets/fo/img/icons/open eye magenta.png" alt="icone enveloppe">
					</div>
					<div class="forgot-link">
						<a href="#" class="login-btn">Mot de passe oublié ?</a>
                        <img src="/assets/fo/img/icons/playful PW.png" alt="icone oeil">
					</div>
					<a href="#" class="btn btn-connex flex-row ai-center jc-center">
                        <img src="/assets/fo/img/icons/playful button.png" alt="">
                        <p>Connexion</p>
                    </a>
				</form>
			</div>

			<div class="form-box register">
				<form class="form-login" action="">
					<h1 class="underline-hug">S’enregistrer</h1>
					<div class="input-box">
						<input type="email" placeholder="Email" required />
                        <img src="/assets/fo/img/icons/mail.png" alt="">
					</div>

					<div class="box">
						<div class="input-box">
							<input type="password" id="pswrd" placeholder="Entrez votre mot de passe" onkeyup="checkPassword(this.value)" />
                            <img src="/assets/fo/img/icons/open eye magenta.png" alt="icone enveloppe">
							<span id="toggleBtn"></span>
						</div>

						<div class="input-box">
							<input type="password" placeholder="Confirmez votre mot de passe" required />
							<img src="/assets/fo/img/icons/closed eye cyan.png" alt="">
						</div>

						<div class="validation">
							<ul>
								<li id="lower">At least one lowercase letter</li>
								<li id="upper">At least one uppercase letter</li>
								<li id="number">At least one number</li>
								<li id="special">At least one special character</li>
								<li id="length">At least 8 characters</li>
								<li id="match">Same Password</li>
							</ul>
						</div>
						<!-- <div class="sanction">
							<input type="submit" id="ok" value="Submit" disabled="disabled" />
						</div> -->
					</div>
					<button type="submit" class="btn">Enregistrer</button>
				</form>
			</div>

			<div class="toggle-box">
				<div class="toggle-panel toggle-left">
					<h2 class="underline-hug">Vous n’êtes pas inscrit ?</h2>
                    <a href="#" id="register" class="register-btn btn-sub flex-row ai-center jc-center">
                        <img src="/assets/fo/img/icons/playful button.png" alt="">
                        <p>S'inscrire</p>
                    </a>
                    <!-- <button class="btn register-btn">S’inscrire</button> -->
				</div>
				<div class="toggle-panel toggle-right">
					<h2 class="underline-hug">Heureux de vous revoir!</h2>
					<a href="#" id="login"class="btn login-btn flex-row ai-center jc-center">
						<img src="/assets/fo/img/icons/playful button.png" alt="">
						<p>Connectez-vous</p>
					</a>
				</div>
			</div>
		</div>
	</section>

	<script src="/assets/js/login.js"></script>
		
	<!-- ================== FOOTER ==================  -->
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php');
?>

		
