<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/sidebar.php');
include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Auteurs / Liste";

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/ariane.php');

// dossier upload (même logique que tes livres)
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/bo/img/';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

// helper upload image
function uploadAuteurImage($fileField, $idAuteur, $uploadDir)
{
    if (empty($_FILES[$fileField]) || empty($_FILES[$fileField]['name'])) {
        return null;
    }

    $tmp  = $_FILES[$fileField]['tmp_name'];
    $err  = $_FILES[$fileField]['error'];
    $size = $_FILES[$fileField]['size'];
    $type = $_FILES[$fileField]['type'];

    if ($err !== 0) {
        $_SESSION['flash']['danger'] = "Le téléchargement de l'image a échoué.";
        return false;
    }

    if ($size > 2000000) {
        $_SESSION['flash']['danger'] = "Votre image est trop lourde (maximum 2Mo)";
        return false;
    }

    $ext = strtolower(pathinfo($_FILES[$fileField]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $_SESSION['flash']['danger'] = "Format image non autorisé (jpg, jpeg, png).";
        return false;
    }

    // nom propre
    $imgName = "auteur_" . (int)$idAuteur . "." . $ext;

    if (!move_uploaded_file($tmp, $uploadDir . $imgName)) {
        $_SESSION['flash']['danger'] = "Impossible d'enregistrer l'image sur le serveur.";
        return false;
    }

    return $imgName;
}


// ==============================
// MODIF AUTEUR
// ==============================
if (isset($_GET['action']) && $_GET['action'] == "modifAuteur") {

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo "<script>document.location.replace('auteurs.php?zone=auteurs')</script>";
        exit;
    }

    $selectAuteur = $db->prepare("SELECT * FROM auteurs WHERE id_auteur = ? LIMIT 1");
    $selectAuteur->execute([$id]);
    $auteur = $selectAuteur->fetch(PDO::FETCH_OBJ);

    if (!$auteur) {
        echo "<script>document.location.replace('auteurs.php?zone=auteurs')</script>";
        exit;
    }

    if (isset($_POST['update_auteur'])) {

        $nom          = htmlspecialchars(trim($_POST['auteur_nom'] ?? ''));
        $prenom       = htmlspecialchars(trim($_POST['auteur_prenom'] ?? ''));
        $nationalite  = htmlspecialchars(trim($_POST['auteur_nationalite'] ?? ''));
        $bio          = $_POST['auteur_biographie'] ?? ''; // peut contenir du HTML
        $dateN        = $_POST['auteur_date_naissance'] ?? null;
        $dateD        = $_POST['auteur_date_deces'] ?? null;

        if ($dateN === '') $dateN = null;
        if ($dateD === '') $dateD = null;

        // update texte
        $update = $db->prepare("UPDATE auteurs SET
            auteur_nom = ?,
            auteur_prenom = ?,
            auteur_nationalite = ?,
            auteur_biographie = ?,
            auteur_date_naissance = ?,
            auteur_date_deces = ?
            WHERE id_auteur = ?
        ");
        $update->execute([$nom, $prenom, $nationalite, $bio, $dateN, $dateD, $id]);

        // upload image optionnel
        $img = uploadAuteurImage('auteur_image', $id, $uploadDir);
        if ($img && $img !== false) {
            $updImg = $db->prepare("UPDATE auteurs SET auteur_image = ? WHERE id_auteur = ?");
            $updImg->execute([$img, $id]);
        }

        echo "<script>document.location.replace('auteurs.php?zone=auteurs')</script>";
        exit;
    }
?>

    <!-- IMPORTANT: enctype pour upload -->
    <form method="POST" enctype="multipart/form-data">

        <div>
            <label for="">Nom de l'auteur</label>
            <input type="text" name="auteur_nom" value="<?php echo htmlspecialchars($auteur->auteur_nom ?? ''); ?>">
        </div>

        <div>
            <label for="">Prénom de l'auteur</label>
            <input type="text" name="auteur_prenom" value="<?php echo htmlspecialchars($auteur->auteur_prenom ?? ''); ?>">
        </div>

        <div>
            <label for="">Nationalité</label>
            <input type="text" name="auteur_nationalite" value="<?php echo htmlspecialchars($auteur->auteur_nationalite ?? ''); ?>">
        </div>

        <div>
            <label for="">Date de naissance</label>
            <input type="date" name="auteur_date_naissance" value="<?php echo htmlspecialchars($auteur->auteur_date_naissance ?? ''); ?>">
        </div>

        <div>
            <label for="">Date de décès</label>
            <input type="date" name="auteur_date_deces" value="<?php echo htmlspecialchars($auteur->auteur_date_deces ?? ''); ?>">
        </div>

        <div>
            <label for="">Image (optionnel)</label>
            <input type="file" name="auteur_image">
        </div>

        <div>
            <label for="">Biographie</label>
            <textarea name="auteur_biographie" placeholder="Biographie de l'auteur"><?php echo ($auteur->auteur_biographie ?? ''); ?></textarea>
        </div>

        <div>
            <input type="submit" value="Enregistrer" name="update_auteur">
        </div>

    </form>

<?php
} else {

    // ==============================
    // LISTE + AJOUT
    // ==============================
    $selectAuteurs = $db->prepare("SELECT * FROM auteurs ORDER BY id_auteur DESC");
    $selectAuteurs->execute();

    if (isset($_POST['add_auteur'])) {

        $nom          = htmlspecialchars(trim($_POST['auteur_nom'] ?? ''));
        $prenom       = htmlspecialchars(trim($_POST['auteur_prenom'] ?? ''));
        $nationalite  = htmlspecialchars(trim($_POST['auteur_nationalite'] ?? ''));
        $bio          = $_POST['auteur_biographie'] ?? '';
        $dateN        = $_POST['auteur_date_naissance'] ?? null;
        $dateD        = $_POST['auteur_date_deces'] ?? null;

        if ($dateN === '') $dateN = null;
        if ($dateD === '') $dateD = null;

        // mini validation (évite insertion vide)
        if ($nom === '' && $prenom === '') {
            $_SESSION['flash']['danger'] = "Veuillez renseigner au moins un nom ou un prénom.";
            echo "<script>document.location.replace('auteurs.php?zone=auteurs')</script>";
            exit;
        }

        // insert auteur (sans image d'abord)
        $ins = $db->prepare("INSERT INTO auteurs SET
            auteur_nom = ?,
            auteur_prenom = ?,
            auteur_image = NULL,
            auteur_nationalite = ?,
            auteur_biographie = ?,
            auteur_date_naissance = ?,
            auteur_date_deces = ?
        ");
        $ins->execute([$nom, $prenom, $nationalite, $bio, $dateN, $dateD]);

        $newId = (int)$db->lastInsertId();

        // upload image optionnel
        $img = uploadAuteurImage('auteur_image', $newId, $uploadDir);
        if ($img && $img !== false) {
            $updImg = $db->prepare("UPDATE auteurs SET auteur_image = ? WHERE id_auteur = ?");
            $updImg->execute([$img, $newId]);
        }

        echo "<script>document.location.replace('auteurs.php?zone=auteurs')</script>";
        exit;
    }
?>

    <!-- IMPORTANT: enctype pour upload -->
    <form method="POST" enctype="multipart/form-data">

        <div>
            <label for="">Nom de l'auteur</label>
            <input type="text" name="auteur_nom">
        </div>

        <div>
            <label for="">Prénom de l'auteur</label>
            <input type="text" name="auteur_prenom">
        </div>

        <div>
            <label for="">Nationalité</label>
            <input type="text" name="auteur_nationalite">
        </div>

        <div>
            <label for="">Date de naissance</label>
            <input type="date" name="auteur_date_naissance">
        </div>

        <div>
            <label for="">Date de décès</label>
            <input type="date" name="auteur_date_deces">
        </div>

        <div>
            <label for="">Image (optionnel)</label>
            <input type="file" name="auteur_image">
        </div>

        <div>
            <label for="">Biographie</label>
            <textarea name="auteur_biographie" placeholder="Biographie de l'auteur"></textarea>
        </div>

        <div>
            <input type="submit" value="Enregistrer" name="add_auteur">
        </div>

    </form>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>
                <button type="button">Add record</button>
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
                        <th><span class="las la-sort"></span> AUTEUR</th>
                        <th><span class="las la-sort"></span> NATIONALITÉ</th>
                        <th><span class="las la-sort"></span> NAISSANCE</th>
                        <th><span class="las la-sort"></span> DÉCÈS</th>
                        <th><span class="las la-sort"></span> BIOGRAPHIE</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sA = $selectAuteurs->fetch(PDO::FETCH_OBJ)) {

                        $img = !empty($sA->auteur_image) ? $sA->auteur_image : '1.jpeg';
                        $imgSrc = "/assets/bo/img/" . $img;
                    ?>
                        <tr>
                            <td>#<?php echo (int)$sA->id_auteur; ?></td>
                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(<?php echo htmlspecialchars($imgSrc); ?>)"></div>
                                    <div class="client-info">
                                        <h4><?php echo htmlspecialchars(($sA->auteur_prenom ?? '') . ' ' . ($sA->auteur_nom ?? '')); ?></h4>
                                    </div>
                                </div>
                            </td>

                            <td><?php echo htmlspecialchars($sA->auteur_nationalite ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($sA->auteur_date_naissance ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($sA->auteur_date_deces ?? ''); ?></td>
                            <td>
                                <?php
                                $bioShort = '';
                                if (!empty($sA->auteur_biographie)) {
                                    $bioClean = trim(strip_tags($sA->auteur_biographie)); // enlève HTML
                                    $bioShort = mb_substr($bioClean, 0, 60, 'UTF-8');
                                    if (mb_strlen($bioClean, 'UTF-8') > 60) $bioShort .= '…';
                                }
                                echo htmlspecialchars($bioShort);
                                ?>
                            </td>


                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <a href="auteurs.php?zone=auteurs&action=modifAuteur&id=<?php echo (int)$sA->id_auteur; ?>">
                                        <span class="las la-eye"></span>
                                    </a>
                                    <span class="las la-ellipsis-v"></span>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

<?php
}

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/footer.php');
?>