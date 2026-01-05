<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

/**
 * LOGIN (front)
 * - vérifie users.user_mail + users.user_mdp (hash)
 * - met en session $_SESSION['user'] (ce que ta nav attend)
 */

if (isset($_POST['login'])) {

    $mail = trim($_POST['user_mail'] ?? '');
    $pwd  = $_POST['user_mdp'] ?? '';

    if ($mail !== '' && $pwd !== '') {

        $req = $db->prepare("SELECT * FROM users WHERE user_mail = ? LIMIT 1");
        $req->execute([$mail]);
        $u = $req->fetch(PDO::FETCH_ASSOC);

        if ($u && password_verify($pwd, $u['user_mdp'])) {

            $avatarPath = null;
            if (!empty($u['user_img'])) {
                $avatarPath = '/assets/bo/img/' . $u['user_img'];
            }

            $_SESSION['user'] = [
                'id'         => (int)$u['id_user'],
                'first_name' => $u['user_prenom'],
                'last_name'  => $u['user_nom'],
                'avatar'     => $avatarPath,
                'id_role'    => (int)$u['id_role'],
                'mail'       => $u['user_mail']
            ];

            $_SESSION['flash']['success'] = "Connecté.";
            header("Location: /index.php");
            exit;

        } else {
            $_SESSION['flash']['danger'] = "Email ou mot de passe incorrect.";
        }

    } else {
        $_SESSION['flash']['danger'] = "Les champs sont vides.";
    }
}

//  ================== HEADER ==================
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');
?>

<section class="flex-row jc-center">
	<div class="container-login">
		<div class="form-box login">

            <?php
            if (isset($_SESSION['flash'])) {
                foreach ($_SESSION['flash'] as $type => $msg) {
                    echo '<div class="alert alert-' . htmlspecialchars($type) . '" style="margin-bottom:10px;">'
                        . htmlspecialchars($msg) .
                        '</div>';
                }
                unset($_SESSION['flash']);
            }
            ?>

			<form class="form-login" method="POST" action="">
				<h1 class="underline-hug">Se connecter</h1>

				<div class="input-box">
					<input type="email" name="user_mail" placeholder="Adresse mail" required />
					<img src="/assets/fo/img/icons/mail.png" alt="">
				</div>

				<div class="input-box">
					<input type="password" name="user_mdp" placeholder="Mot de passe" required />
					<img src="/assets/fo/img/icons/open eye magenta.png" alt="icone enveloppe">
				</div>

				<div class="forgot-link">
					<a href="#" class="login-btn">Mot de passe oublié ?</a>
					<img src="/assets/fo/img/icons/playful PW.png" alt="icone oeil">
				</div>

				<button type="submit" name="login" class="btn btn-connex flex-row ai-center jc-center">
					<img src="/assets/fo/img/icons/playful button.png" alt="">
					<p>Connexion</p>
				</button>
			</form>
		</div>

		<!-- Tu peux garder ton bloc register en déco (pas branché) -->
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
							<li id="lower">Au moins une lettre minuscule</li>
							<li id="upper">Au moins une lettre majuscule</li>
							<li id="number">Au moins un chiffre</li>
							<li id="special">Au moins un caractère spécial</li>
							<li id="length">Au moins 8 caractères</li>
							<li id="match">Mêmes mots de passe</li>
						</ul>
					</div>
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
			</div>
			<div class="toggle-panel toggle-right">
				<h2 class="underline-hug">Heureux de vous revoir!</h2>
				<a href="#" id="login" class="btn login-btn flex-row ai-center jc-center">
					<img src="/assets/fo/img/icons/playful button.png" alt="">
					<p>Connectez-vous</p>
				</a>
			</div>
		</div>
	</div>
</section>

<script src="/assets/fo/js/login.js"></script>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php');
?>
