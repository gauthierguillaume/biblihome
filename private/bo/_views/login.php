<?php

include($_SERVER['DOCUMENT_ROOT'].'/host.php');

if(isset($_POST['login'])){
    if(!empty($_POST['user_mail']) && !empty($_POST['user_mdp'])){
        $mail = $_POST['user_mail'];
        $pwd = $_POST['user_mdp'];

        $req = $db->prepare('SELECT * FROM users
            NATURAL JOIN civilites
            NATURAL JOIN roles
            WHERE user_mail = ?
        ');
        $req->execute([$mail]);
        $user = $req->fetch();

        if(password_verify($pwd, $user['user_mdp'])){
            $_SESSION['auth'] = $user;

            $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté.';

            echo "<script language='javascript'>
            document.location.replace('../index.php?zone=dashboard')
            </script>";
        }else{
            $_SESSION['flash']['danger'] = "Informations de connexion incorrectes.";

            echo "<script language='javascript'>
            document.location.replace('login.php')
            </script>";
        }
    }else{
        $_SESSION['flash']['danger'] = "Les champs sont vides.";

        echo "<script language='javascript'>
        document.location.replace('login.php')
        </script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/_styles/form.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/_styles/generic.css">
    <title>Connectez-vous</title>
</head>
<body>

    <div class="log flexRow justifyCenter alignCenter">

        <div class="container login">
            <div class="form-box flexCol">

                <h1 class="textCenter">Connectez-vous</h1>

                <form method="POST">

                    <div class="input-box">
                        <input type="email" placeholder="Email" name="user_mail" required />
                        <i class="bx bxs-envelope"></i>
                    </div>

                    <div class="input-box">
                        <input type="password" placeholder="Mot de passe" name="user_mdp" required />
                        <i class="bx bxs-lock-alt"></i>
                    </div>

                    <input type="submit" class="btn" name="login" value="Me connecter">

                </form>

            </div>
        </div>

    </div>
    
</body>
</html>

<?php
if(isset($_SESSION['flash'])){
    foreach($_SESSION['flash'] as $type => $message){
        ?>
        <div id="zoneDeNotification">
            <div class="alert alert-<?php echo $type;?>">
                <?php echo $message; ?>
            </div>
        </div>
        <?php
    }
    unset($_SESSION['flash']);
}
?>