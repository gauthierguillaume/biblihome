<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

// if(isset($_SESSION['auth']) && $_SESSION['auth']['role_level'] > 99 ){

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/sidebar.php');
include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Admin / Liste des utilisateurs";

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/ariane.php');

/**
 * Petit helper simple (au cas où noaccent() n'existe pas dans ton projet)
 * -> tu peux supprimer si tu as déjà une fonction noaccent() dans ton projet.
 */
if (!function_exists('noaccent')) {
    function noaccent(string $str): string
    {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $str = preg_replace('/[^a-zA-Z0-9\.\-_]/', '', $str);
        return $str ?: 'file';
    }
}

// Dossier unique pour images BO (avatars + couvertures)
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/bo/img/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

/* ===========================
   AJOUT UTILISATEUR
=========================== */
if (isset($_GET['action']) && $_GET['action'] == "addUser") {

    $selectCivilites = $db->prepare('SELECT * FROM civilites');
    $selectCivilites->execute();

    $selectRoles = $db->prepare('SELECT * FROM roles');
    $selectRoles->execute();

    $errors = [];

    if (isset($_POST['addUser'])) {

        if (empty($_POST['id_civilite'])) {
            $errors['civilite'] = "Vous devez sélectionner une civilité.";
        }

        if (empty($_POST['user_nom']) || !preg_match("/^[\p{L}\p{M} '’-]+$/u", $_POST['user_nom'])) {
            $errors['user_nom'] = 'Le champs "Nom" n\'est pas valide.';
        }

        if (empty($_POST['user_prenom']) || !preg_match("/^[\p{L}\p{M} '’-]+$/u", $_POST['user_prenom'])) {
            $errors['user_prenom'] = 'Le champs "Prénom" n\'est pas valide.';
        }

        if (empty($_POST['user_mail']) || !filter_var($_POST['user_mail'], FILTER_VALIDATE_EMAIL)) {
            $errors['user_mail'] = "Votre mail n'est pas valide.";
        } else {
            $req = $db->prepare('SELECT id_user FROM users WHERE user_mail = ? LIMIT 1');
            $req->execute([$_POST['user_mail']]);
            $email = $req->fetch();
            if ($email) {
                $errors['user_mail'] = "L'email est déjà utilisé.";
            }
        }

        if (empty($_POST['id_role'])) {
            $errors['role'] = "Vous devez sélectionner un rôle.";
        }

        if (empty($_POST['user_mdp']) || ($_POST['user_mdp'] != ($_POST['conf_pwd'] ?? ''))) {
            $errors['user_mdp'] = "Les mots de passe ne sont pas identiques.";
        }

        if (empty($errors)) {
            $id_civilite = (int)$_POST['id_civilite'];
            $name = trim($_POST['user_nom']);
            $firstname = trim($_POST['user_prenom']);
            $mail = trim($_POST['user_mail']);
            $id_role = (int)$_POST['id_role'];
            $pwd = $_POST['user_mdp'];

            $insert_user = $db->prepare('INSERT INTO users SET
                id_civilite = ?,
                user_nom = ?,
                user_prenom = ?,
                user_mail = ?,
                id_role = ?,
                user_mdp = ?
            ');
            $password = password_hash($pwd, PASSWORD_ARGON2I);
            $insert_user->execute([$id_civilite, $name, $firstname, $mail, $id_role, $password]);

            $_SESSION['flash']['success'] = "Utilisateur ajouté.";
            echo "<script>document.location.replace('admin.php?zone=admin')</script>";
            exit;
        }
    }
?>

    <div class="flexRow justifyCenter">
        <?php if (!empty($errors)): ?>
            <ul style="margin:20px; color:#c00;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="container">
            <div class="form-box register">
                <form method="POST">
                    <h1>Ajouter un utilisateur</h1>

                    <div class="input-box">
                        <select name="id_civilite" required>
                            <option value="">-- Civilité --</option>
                            <?php while ($sC = $selectCivilites->fetch(PDO::FETCH_OBJ)): ?>
                                <option value="<?php echo $sC->id_civilite; ?>">
                                    <?php echo $sC->civilite_nom; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-box">
                        <input type="text" placeholder="Nom" name="user_nom" required />
                    </div>

                    <div class="input-box">
                        <input type="text" placeholder="Prénom" name="user_prenom" required />
                    </div>

                    <div class="input-box">
                        <input type="email" placeholder="Email" name="user_mail" required />
                    </div>

                    <div class="input-box">
                        <select name="id_role" required>
                            <option value="">-- Rôle --</option>
                            <?php while ($sR = $selectRoles->fetch(PDO::FETCH_OBJ)): ?>
                                <option value="<?php echo $sR->id_role; ?>">
                                    <?php echo $sR->role_name; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-box">
                        <input type="password" placeholder="Mot de passe" name="user_mdp" required />
                    </div>

                    <div class="input-box">
                        <input type="password" placeholder="Confirmer" name="conf_pwd" required />
                    </div>

                    <input type="submit" class="btn" name="addUser" value="Enregistrer">
                </form>
            </div>
        </div>
    </div>

<?php

    /* ===========================
   UPLOAD AVATAR USER
=========================== */
} else if (isset($_GET['action']) && $_GET['action'] == "user") {

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        $_SESSION['flash']['danger'] = "Utilisateur invalide.";
        echo "<script>document.location.replace('admin.php?zone=admin')</script>";
        exit;
    }

    if (isset($_POST['add'])) {

        if (empty($_FILES['image']) || empty($_FILES['image']['name'])) {
            $_SESSION['flash']['danger'] = "Aucun fichier sélectionné.";
            echo "<script>document.location.replace('admin.php?zone=admin&action=user&id=" . $id . "')</script>";
            exit;
        }

        $tmp_img = $_FILES['image']['tmp_name'];
        $error   = (int)$_FILES['image']['error'];
        $size    = (int)$_FILES['image']['size'];
        $type    = $_FILES['image']['type'] ?? '';

        // Sécurité simple extensions
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/jpg' => 'jpg'];
        if (!isset($allowed[$type])) {
            $_SESSION['flash']['danger'] = "Format non autorisé (jpg/png).";
            echo "<script>document.location.replace('admin.php?zone=admin&action=user&id=" . $id . "')</script>";
            exit;
        }

        if ($error !== 0) {
            $_SESSION['flash']['danger'] = "Le téléchargement a échoué (code $error).";
            echo "<script>document.location.replace('admin.php?zone=admin&action=user&id=" . $id . "')</script>";
            exit;
        }

        if ($size > 5000000) {
            $_SESSION['flash']['danger'] = "Image trop lourde (max 5Mo).";
            echo "<script>document.location.replace('admin.php?zone=admin&action=user&id=" . $id . "')</script>";
            exit;
        }

        $img_ext  = $allowed[$type];
        $img_name = $id . '.' . $img_ext;

        // Enregistre en BDD le nom du fichier
        $insertImg = $db->prepare('UPDATE users SET user_img = ? WHERE id_user = ?');
        $insertImg->execute([$img_name, $id]);

        // Upload dans assets/bo/img
        $ok = move_uploaded_file($tmp_img, $uploadDir . $img_name);

        if ($ok) {
            $_SESSION['flash']['success'] = "Avatar mis à jour.";
            echo "<script>document.location.replace('admin.php?zone=admin')</script>";
            exit;
        } else {
            $_SESSION['flash']['danger'] = "Impossible de déplacer le fichier (droits dossier ?).";
            echo "<script>document.location.replace('admin.php?zone=admin&action=user&id=" . $id . "')</script>";
            exit;
        }
    }

    // Petite vue simple pour uploader
?>
    <div style="padding: 20px;">
        <h2>Changer l'avatar utilisateur #<?php echo $id; ?></h2>

        <form method="POST" enctype="multipart/form-data" style="margin-top:15px;">
            <input type="file" name="image" accept="image/jpeg,image/png" required>
            <input type="submit" value="Ajouter" name="add" class="btn" style="margin-left:10px;">
        </form>

        <p style="margin-top:12px; opacity:.8;">
            Les images sont enregistrées dans <b>/assets/bo/img/</b>
        </p>
    </div>
<?php

    /* ===========================
   LISTE USERS
=========================== */
} else {

    $selectAllUsers = $db->prepare('SELECT * FROM users
        NATURAL JOIN roles
        NATURAL JOIN civilites
    ');
    $selectAllUsers->execute();
?>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>
                <a href="admin.php?zone=admin&action=addUser">Ajouter un utilisateur</a>
            </div>

            <div class="browse">
                <input type="search" placeholder="Search" class="record-search">
                <select name="" id="">
                    <option value="">Status</option>
                </select>
            </div>
        </div>

        <div>
            <table width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><span class="las la-sort"></span> UTILISATEUR</th>
                        <th><span class="las la-sort"></span> ROLE</th>
                        <th><span class="las la-sort"></span> CIVILITE</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sAu = $selectAllUsers->fetch(PDO::FETCH_OBJ)): ?>
                        <tr>
                            <td>#<?php echo $sAu->id_user; ?></td>
                            <td>
                                <div class="client">
                                    <?php
                                    $img = !empty($sAu->user_img) ? $sAu->user_img : "default.png";
                                    ?>
                                    <div class="client-img bg-img"
                                        style="background-image: url('/assets/bo/img/<?php echo htmlspecialchars($img); ?>')">
                                    </div>

                                    <div class="client-info">
                                        <h4><?php echo ucwords($sAu->user_prenom); ?> <?php echo ucwords($sAu->user_nom); ?></h4>
                                        <small><?php echo $sAu->user_mail; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="capitalise">
                                <?php echo $sAu->role_name; ?>
                            </td>
                            <td>
                                <?php echo $sAu->civilite_nom; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="admin.php?zone=admin&action=user&id=<?php echo $sAu->id_user; ?>">
                                        <span class="lab la-telegram-plane"></span>
                                    </a>
                                    <span class="las la-eye"></span>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

<?php
}

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/footer.php');

// } else {
//     echo "<script>document.location.replace('login.php')</script>";
// }
?>