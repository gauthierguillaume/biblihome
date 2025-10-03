<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Responsive Login and Registration Form in HTML CSS & Javascript</title>
    <meta name="description" content="BibliHome - Une bibliothèque accessible à tous">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/assets/fo/css/base.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/assets/fo/css/generic.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/assets/fo/css/components.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/assets/fo/css/login.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
		<div class="container-login">
			<div class="form-box login">
				<form action="">
					<h1>Se connecter</h1>
					<div class="input-box">
						<input type="text" placeholder="Adresse mail" required />
						<img src="/assets/fo/img/icons/mail.png" alt="">
					</div>
					<div class="input-box">
						<input type="password" placeholder="Mot de passe" required />
						<img src="/assets/fo/img/icons/open eye magenta.png" alt="icone enveloppe">
					</div>
					<div class="forgot-link">
						<a href="#" class="login-Btn">Mot de passe oublié ?</a>
                        <img src="/assets/fo/img/icons/playful PW.png" alt="icone oeil">
					</div>
					<a href="#" class="btn btn-connex flex-row ai-center jc-center">
                        <img src="/assets/fo/img/icons/playful button.png" alt="">
                        <p>Connexion</p>
                    </a>
				</form>
			</div>

			<div class="form-box register">
				<form action="">
					<h1>S’enregistrer</h1>
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
					<h2>Vous n’êtes pas inscrit ?</h2>
                    <a href="#" class="register-btn btn-sub flex-row ai-center jc-center">
                        <img src="/assets/fo/img/icons/playful button.png" alt="">
                        <p>S'inscrire</p>
                    </a>
                    <!-- <button class="btn register-btn">S’inscrire</button> -->
				</div>
				<div class="toggle-panel toggle-right">
					<h2>Heureux de vous revoir!</h2>
					<a class="btn login-btn flex-row ai-center jc-center">
						<img src="/assets/fo/img/icons/playful button.png" alt="">
						<p>Connectez-vous</p>
					</a>
				</div>
			</div>
		</div>

		<script src="/assets/js/login.js"></script>
	</body>
</html>