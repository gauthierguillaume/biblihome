<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Abonnés / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');

if(isset($_GET['action']) && $_GET['action'] == "modifAbonne"){
    $id = $_GET['id'];

    // je sélectionne les éléments de la table abonnes qui correspondent à mon id.
    $selectAbonne = $db->prepare('SELECT * FROM abonnes
        NATURAL JOIN civilites
        WHERE id_abonne = ?
    ');
    $selectAbonne->execute([$id]);
    $abonne = $selectAbonne->fetch(PDO::FETCH_OBJ);

    // Je mets en variable l'id de la civilité de mon abonné.
    $id_civilite = $abonne->id_civilite;

    // Je sélectionne toutes les civilités de la table civilités à l'exception de l'id de mon abonné.
    $selectCivilites = $db->prepare('SELECT * FROM civilites
        WHERE id_civilite != ?
    ');
    $selectCivilites->execute([$id_civilite]);

    if(isset($_POST['update_abonne'])){
        $civilite = $_POST['id_civilite'];
        $prenom = htmlspecialchars($_POST['abonne_prenom']);
        $nom = htmlspecialchars($_POST['abonne_nom']);
        $date = $_POST['abonne_date_naissance'];

        $update_abonne = $db->prepare('UPDATE abonnes SET
            id_civilite = ?,
            abonne_prenom = ?,
            abonne_nom = ?,
            abonne_date_naissance = ?
            WHERE id_abonne = ?
        ');
        $update_abonne->execute([$civilite, $prenom, $nom, $date, $id]);

        echo "<script language='javascript'>
            document.location.replace('abonnes.php')
            </script>";
    }

    //var_dump($abonne);

    ?>
        <form method="POST">
            <div>
                <label for="">Civilité</label>
                <select name="id_civilite" id="">
                    <option value="<?php echo $abonne->id_civilite;?>"><?php echo $abonne->civilite_titre;?></option>
                    <?php
                    while($sC = $selectCivilites->fetch(PDO::FETCH_OBJ)){
                        ?>
                            <option value="<?php echo $sC->id_civilite;?>"><?php echo $sC->civilite_titre;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="">Prénom de l'abonné</label>
                <input type="text" name="abonne_prenom" value="<?php echo $abonne->abonne_prenom;?>">
            </div>

            <div>
                <label for="">Nom de l'abonné</label>
                <input type="text" name="abonne_nom" value="<?php echo $abonne->abonne_nom;?>">
            </div>

            <div>
                <label for="">date de naissance de l'abonné</label>
                <input type="date" name="abonne_date_naissance" value="<?php echo $abonne->abonne_date_naissance;?>">
            </div>

            <div>
                <input type="submit" value="Modifier" name="update_abonne">
            </div>
        </form>

    <?php
}else{

    $selectCivilites = $db->prepare('SELECT * FROM civilites');
    $selectCivilites->execute();

    $selectAbonnes = $db->prepare('SELECT * FROM abonnes');
    $selectAbonnes->execute();

    // Fonctionnalité d'ajout d'un abonné.
    if(isset($_POST['add_abonne'])){
        $id_civilite = $_POST['id_civilite'];
        $firstname = htmlspecialchars($_POST['abonne_prenom']);
        $lastname = htmlspecialchars($_POST['abonne_nom']);
        $date = $_POST['abonne_date_naissance'];

        $insert_abonne = $db->prepare('INSERT INTO abonnes SET
            id_civilite = ?,
            abonne_prenom = ?,
            abonne_nom = ?,
            abonne_date_naissance = ?
        ');
        $insert_abonne->execute([$id_civilite, $firstname, $lastname, $date]);

        echo "<script language='javascript'>
            document.location.replace('abonnes.php')
            </script>";
    }

    ?>

    <form method="POST">
        <select name="id_civilite">
            <?php
            while($sC = $selectCivilites->fetch(PDO::FETCH_OBJ)){
                ?>
                    <option value="<?php echo $sC->id_civilite;?>"><?php echo $sC->civilite_titre;?></option>
                <?php
            }
            ?>
        </select>
        <div>
            <input type="text" name="abonne_prenom">
        </div>
        <div>
            <input type="text" name="abonne_nom">
        </div>
        <div>
            <input type="date" name="abonne_date_naissance">
        </div>
        <div>
            <input type="submit" name="add_abonne" value="Enr">
        </div>

    </form>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>
                <button>Add record</button>
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
                        <th><span class="las la-sort"></span> ABONNES</th>
                        <th><span class="las la-sort"></span> DATE DE NAISSANCE</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($sA = $selectAbonnes->fetch(PDO::FETCH_OBJ)){
                            ?>
                            <tr>
                                <td>#<?php echo $sA->id_abonne;?></td>
                                <td>
                                    <div class="client">
                                        <div class="client-img bg-img" style="background-image: url(img/3.jpeg)"></div>
                                        <div class="client-info">
                                            <h4><?php echo $sA->abonne_prenom;?> <?php echo $sA->abonne_nom;?></h4>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php echo $sA->abonne_date_naissance;?>
                                </td>

                                <td>
                                    <div class="actions">
                                        <span class="lab la-telegram-plane"></span>
                                        <a href="abonnes.php?zone=abonnes&action=modifAbonne&id=<?php echo $sA->id_abonne;?>">
                                            <span class="las la-eye"></span>
                                        </a>
                                        <span class="las la-ellipsis-v"></span>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    
                    
                </tbody>
            </table>
        </div>

    </div>


    <?php
}

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');

?>