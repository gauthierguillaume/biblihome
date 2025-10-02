<?php

$id_user = $_SESSION['auth']['id_user'];

$selectUser = $db->prepare('SELECT * FROM users
    NATURAL JOIN roles
    WHERE id_user = ?
');
$selectUser->execute([$id_user]);
$user = $selectUser->fetch(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <title>Modern Admin Dashboard</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/bo/css/style.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/bo/css/generic.css">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/bo/css/form.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <?php
    if(isset($_SESSION['flash'])){
        foreach($_SESSION['flash'] as $type => $message){
            ?>
            <div id="zoneDeNotification">
                <div class="alert alert-<?php echo $type;?> flexRow alignCenter JustifyCenter">
                    <?php if($type = "danger"){ echo '<i class="las la-exclamation-triangle"></i> ';}else if($type = "success"){ echo '<i class="las la-info-circle"></i> ';} echo $message; ?>
                </div>
            </div>
            <?php
        }
        unset($_SESSION['flash']);
    }
    ?>

   <input type="checkbox" id="menu-toggle">
    <div class="sidebar">
        <div class="side-header">
            <h3>M<span>odern</span></h3>
        </div>
        
        <div class="side-content">
            <div class="profile">
                <div class="profile-img bg-img" style="background-image: url(<?php $_SERVER['DOCUMENT_ROOT']?>/assets/bo/img/3.jpeg)"></div>
                <h4><?php echo ucfirst($user->user_prenom);?> <?php echo ucfirst($user->user_nom);?></h4>
                <small><?php echo $user->role_name;?></small>
            </div>

            <div class="side-menu">
                <ul>

                    <li>
                       <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/index.php?zone=dashboard" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "dashboard"){ echo "active";}?>">
                            <span class="las la-home"></span>
                            <small>Dashboard</small>
                        </a>
                    </li>
                    <li>
                       <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/livraisons.php?zone=livraisons" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "livraisons"){ echo "active";}?>">
                            <span class="las la-truck"></span>
                            <small>Livraisons</small>
                        </a>
                    </li>
                    <li>
                        <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/utilisateurs.php?zone=utilisateurs" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "utilisateurs"){ echo "active";}?>">
                            <span class="las la-user-friends"></span>
                            <small>Utilisateurs</small>
                        </a>
                    </li>
                    <li>
                       <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/livres.php?zone=livres" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "livres"){ echo "active";}?>">
                            <span class="las la-book"></span>
                            <small>Livres</small>
                        </a>
                    </li>
                    <li>
                       <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/auteurs.php?zone=auteurs" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "auteurs"){ echo "active";}?>">
                            <span class="las la-user-alt"></span>
                            <small>Auteurs</small>
                        </a>
                    </li>
                    <li>
                        <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/donnees.php?zone=donnees" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "donnees"){ echo "active";}?>">
                            <span class="las la-file-alt"></span>
                            <small>Données</small>
                        </a>
                    </li>

                    <li>
                       <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/abonnements.php?zone=abonnements" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "abonnements"){ echo "active";}?>">
                            <span class="las la-wallet"></span>
                            <small>Abonnements</small>
                        </a>
                    </li>
                    <?php
                    if(isset($_SESSION['auth']) && $_SESSION['auth']['role_level'] > 99){
                        ?>
                            <li>
                            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/admin.php?zone=admin" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "admin"){ echo "active";}?>">
                                    <span class="las la-user-secret"></span>
                                    <small>Admin</small>
                                </a>
                            </li>
                        <?php
                    }
                    ?>
                    <li>
                        <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/bo/_views/divers.php?zone=divers" class="<?php if(isset($_GET["zone"]) && $_GET["zone"] == "divers"){ echo "active";}?>">
                            <span class="las la-dove"></span>
                            <small>Divers</small>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>

    