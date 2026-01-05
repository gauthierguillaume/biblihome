<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

if (!empty($_SESSION['auth'])) {
    header('Location: ../index.php?zone=dashboard');
    exit;
}

if (isset($_POST['login'])) {

    $mail = trim($_POST['user_mail'] ?? '');
    $pwd  = $_POST['user_mdp'] ?? '';

    if ($mail !== '' && $pwd !== '') {

        // Evite NATURAL JOIN
        $req = $db->prepare("
            SELECT u.*, r.role_name, r.role_level, c.civilite_nom
            FROM users u
            LEFT JOIN roles r ON r.id_role = u.id_role
            LEFT JOIN civilites c ON c.id_civilite = u.id_civilite
            WHERE u.user_mail = ?
            LIMIT 1
        ");
        $req->execute([$mail]);
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($pwd, $user['user_mdp'])) {
            $_SESSION['flash']['danger'] = "Informations de connexion incorrectes.";
            header('Location: login.php');
            exit;
        }

        // BO réservé : Modérateur (50) ou Admin (100)
        if ((int)($user['role_level'] ?? 0) < 50) {
            $_SESSION['flash']['danger'] = "Accès refusé.";
            header('Location: login.php');
            exit;
        }

        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = "Vous êtes maintenant connecté.";
        header('Location: ../index.php?zone=dashboard');
        exit;

    } else {
        $_SESSION['flash']['danger'] = "Les champs sont vides.";
        header('Location: login.php');
        exit;
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Reprend le style du front -->
    <link rel="stylesheet" href="/assets/fo/css/base.css">
    <link rel="stylesheet" href="/assets/fo/css/generic.css">
    <link rel="stylesheet" href="/assets/fo/css/components.css">
    <link rel="stylesheet" href="/assets/fo/css/login.css">

    <title>BO - Connexion</title>
</head>
<body>

<section class="flex-row jc-center">
    <div class="container-login">
        <div class="form-box login">
            <form class="form-login" method="POST" action="">
                <h1 class="underline-hug">Se connecter (BO)</h1>

                <div class="input-box">
                    <input type="email" name="user_mail" placeholder="Adresse mail" required />
                    <img src="/assets/fo/img/icons/mail.png" alt="">
                </div>

                <div class="input-box">
                    <input type="password" name="user_mdp" placeholder="Mot de passe" required />
                    <img src="/assets/fo/img/icons/open eye magenta.png" alt="">
                </div>

                <button type="submit" name="login" class="btn btn-connex flex-row ai-center jc-center">
                    <img src="/assets/fo/img/icons/playful button.png" alt="">
                    <p>Connexion</p>
                </button>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-right">
                <h2 class="underline-hug">Espace Administration</h2>
                <p style="max-width: 320px; opacity:.8;">
                    Connexion réservée au personnel autorisé.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ✅ bon chemin -->
<script src="/assets/fo/js/login.js"></script>

<?php
if (isset($_SESSION['flash'])) {
    foreach ($_SESSION['flash'] as $type => $message) {
        ?>
        <div id="zoneDeNotification">
            <div class="alert alert-<?php echo htmlspecialchars($type); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        </div>
        <?php
    }
    unset($_SESSION['flash']);
}
?>

</body>
</html>
