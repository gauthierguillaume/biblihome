<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Livres / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/ariane.php');



if(isset($_GET['action']) && $_GET['action'] == "modifLivre"){

    // Je récupère l'id dans le Get qui correspond au livre que je veux modifier.
    $id = $_GET['id'];

    // Je sélectionne le livre dans la table à l'aide de l'id récupéré dans le GET.
    $selectLivre = $db->prepare('SELECT * FROM livres
        NATURAL JOIN types
        WHERE id_livre = ?
    ');
    $selectLivre->execute([$id]);
    $livre = $selectLivre->fetch(PDO::FETCH_OBJ);

    // Je récupère l'id du type du livre qui est dans le résultat de la requête $livre.
    $id_type = $livre->id_type;

    // Je sélectionne les types de la table types qui ne sont pas celui du livre grâce la variable id_type.
    $select_types = $db->prepare('SELECT *FROM types
        WHERE id_type != ?
    ');
    $select_types->execute([$id_type]);

    $select_auteurs = $db->prepare('SELECT * FROM auteurs');
    $select_auteurs->execute();

    $selectAuteursLivre = $db->prepare('SELECT * FROM livres_auteurs
        NATURAL JOIN auteurs
        WHERE id_livre = ?
    ');
    $selectAuteursLivre->execute([$id]);

    if(isset($_POST['addAuteur'])){
        $id_auteur = $_POST['id_auteur'];

        $insertAuteur = $db->prepare('INSERT INTO livres_auteurs SET
            id_auteur = ?,
            id_livre = ?
        ');
        $insertAuteur->execute([$id_auteur, $id]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=livres&action=modifLivre&id=".$id."')
            </script>";
    }

    if(isset($_POST['updateLivre'])){
        $synopsys = $_POST['livre_synopsys'];

        $updateLivre = $db->prepare('UPDATE livres SET
            livre_synopsys = ?
            WHERE id_livre = ?    
        ');
        $updateLivre->execute([$synopsys, $id]);

        $_SESSION['flash']['success'] = "votre livre a bien été modifié";

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=livres')
            </script>";
    }  

    ?>

    <form method="POST">

        <p>Les auteurs sont:</p>
        <?php
            while($sAL = $selectAuteursLivre->fetch(PDO::FETCH_OBJ)){
                ?>
                    <p>- <?php echo $sAL->auteur_prenom;?> <?php echo $sAL->auteur_nom;?></p>
                <?php
            }
        ?>

        <hr>

        <label for="">Sélectionner le type</label>
        <select name="id_type" id="">
            <option value="<?php echo $livre->id_type;?>"><?php echo $livre->type_nom;?></option>
            <?php
            while($sT = $select_types->fetch(PDO::FETCH_OBJ)){
                ?>
                    <option value="<?php echo $sT->id_type;?>"><?php echo $sT->type_nom;?></option>
                <?php
            }
            ?>
        </select>

        <div>
            <label for="">Nom du livre</label>
            <input type="text" name="livre_titre" value="<?php echo $livre->livre_titre;?>">
        </div>

        <div>
            <label for="">Synopsys du livre</label>
            <textarea class="ckeditor" name="livre_synopsys" id="" placeholder="Ecrire le synopsys ici"><?php echo $livre->livre_synopsys;?></textarea>
        </div>

        <div>
            <label for="">Date d'écriture du livre</label>
            <input type="date" name="livre_date_create" value="<?php echo $livre->livre_date_create;?>">
        </div>

        <div>
            <input type="submit" value="Enr" name="updateLivre">
        </div>

    </form>

    <script>
        CKEDITOR.replace('livre_synopsys');
    </script>

    <form method="POST">        

        <label for="">Sélectionner un auteur</label>
        <select name="id_auteur">
            <?php
            while($sA = $select_auteurs->fetch(PDO::FETCH_OBJ)){
                ?>
                    <option value="<?php echo $sA->id_auteur;?>"><?php echo ucwords($sA->auteur_prenom);?> <?php echo ucwords($sA->auteur_nom);?></option>
                <?php
            }
            ?>
        </select>

        <div>
            <input type="submit" value="Enr" name="addAuteur">
        </div>
    </form>

    <?php

}else if(isset($_GET['action']) && $_GET['action'] == "addType"){

    $selectTypes = $db->prepare('SELECT * FROM types');
    $selectTypes->execute();

    if(isset($_POST['addType'])){
        $nom = htmlspecialchars($_POST['type_nom']);
        $description = htmlspecialchars($_POST['type_description']);

        $insert_type = $db->prepare('INSERT INTO types SET
            type_nom = ?,
            type_description = ?
        ');
        $insert_type->execute([$nom, $description]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=livres&action=addType')
            </script>";
    }

    ?>

    <form method="POST">

        <div>
            <label for="">Nom du type</label>
            <input type="text" name="type_nom">
        </div>

        <div>
            <label for="">Description du type</label>
            <textarea name="type_description" id="" placeholder="Décrire le type"></textarea>
        </div>

        <div>
            <input type="submit" value="Enr" name="addType">
        </div>

    </form>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>

                <a href="livres.php?zone=livres&action=addType">Gérer les types</a>                
                <a href="livres.php?zone=livres&action=addGenre">Gérer les genres</a>

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
                        <th><span class="las la-sort"></span> TYPES</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($sT = $selectTypes->fetch(PDO::FETCH_OBJ)){
                            ?>
                            <tr>
                                <td>#<?php echo $sT->id_type;?></td>
                                <td>
                                    <div class="client">
                                        <div class="client-info">
                                            <h4><?php echo $sT->type_nom;?></h4>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="actions">
                                        <span class="lab la-telegram-plane"></span>
                                        <a href="livres.php?zone=livres&action=modifType&id=<?php echo $sT->id_type;?>">
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

}else if(isset($_GET['action']) && $_GET['action'] == "modifType"){
    
    echo "Ici se trouvera l'environnement de modification des types.";

}else if(isset($_GET['action']) && $_GET['action'] == "addGenre"){

    $selectGenres = $db->prepare('SELECT * FROM genres');
    $selectGenres->execute();

    if(isset($_POST['addGenre'])){
        $nom = htmlspecialchars($_POST['genre_nom']);
        $description = htmlspecialchars($_POST['genre_description']);

        $insert_genre = $db->prepare('INSERT INTO genres SET
            genre_nom = ?,
            genre_description = ?
        ');
        $insert_genre->execute([$nom, $description]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=livres&action=addGenre')
            </script>";
    }

    ?>

    <form method="POST">

        <div>
            <label for="">Nom du Genre</label>
            <input type="text" name="genre_nom">
        </div>

        <div>
            <label for="">Description du genre</label>
            <textarea name="genre_description" id="" placeholder="Décrire le genre"></textarea>
        </div>

        <div>
            <input type="submit" value="Enr" name="addGenre">
        </div>

    </form>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>

                <a href="livres.php?zone=livres&action=addType">Gérer les types</a>                
                <a href="livres.php?zone=livres&action=addGenre">Gérer les genres</a>

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
                        <th><span class="las la-sort"></span> GENRES</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($sG = $selectGenres->fetch(PDO::FETCH_OBJ)){
                            ?>
                            <tr>
                                <td>#<?php echo $sG->id_genre;?></td>
                                <td>
                                    <div class="client">
                                        <div class="client-info">
                                            <h4><?php echo $sG->genre_nom;?></h4>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="actions">
                                        <span class="lab la-telegram-plane"></span>
                                        <a href="livres.php?zone=livres&action=modifGenre&id=<?php echo $sG->id_genre;?>">
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

}else if(isset($_GET['action']) && $_GET['action'] == "modifGenre"){
    
    echo "Ici se trouvera l'environnement de modification des genres.";

}else{

    $select_types = $db->prepare('SELECT * FROM types');
    $select_types->execute();

    $selectLivres = $db->prepare('SELECT * FROM livres');
    $selectLivres->execute();

    if(isset($_POST['addLivre'])){
        $titre = htmlspecialchars($_POST['livre_titre']);
        $synopsys = htmlspecialchars($_POST['livre_synopsys']);
        $date = $_POST['livre_date_create'];
        $type = $_POST['id_type'];

        $insert_livre = $db->prepare('INSERT INTO livres SET
            livre_titre = ?,
            livre_synopsys = ?,
            livre_date_create = ?,
            id_type = ?
        ');
        $insert_livre->execute([$titre, $synopsys, $date, $type]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=livres')
            </script>";
    }

    ?>

    <form method="POST">

        <label for="">Sélectionner le type</label>
        <select name="id_type" id="">
            <?php
            while($sT = $select_types->fetch(PDO::FETCH_OBJ)){
                ?>
                    <option value="<?php echo $sT->id_type;?>"><?php echo $sT->type_nom;?></option>
                <?php
            }
            ?>
        </select>

        <div>
            <label for="">Nom du livre</label>
            <input type="text" name="livre_titre">
        </div>

        <div>
            <label for="">Synopsys du livre</label>
            <textarea name="livre_synopsys" id="" placeholder="Ecrire le synopsys ici"></textarea>
        </div>

        <div>
            <label for="">Date d'écriture du livre</label>
            <input type="date" name="livre_date_create">
        </div>

        <div>
            <input type="submit" value="Enr" name="addLivre">
        </div>

    </form>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>

                <a href="livres.php?zone=livres&action=addType">Gérer les types</a>                
                <a href="livres.php?zone=livres&action=addGenre">Gérer les genres</a>

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
                        <th><span class="las la-sort"></span> TITRES</th>
                        <th><span class="las la-sort"></span> AUTEURS</th>
                        <th><span class="las la-sort"></span> TYPE</th>
                        <th><span class="las la-sort"></span> GENRES</th>
                        <th><span class="las la-sort"></span> DATE D'ECRITURE</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($sL = $selectLivres->fetch(PDO::FETCH_OBJ)){
                            ?>
                            <tr>
                                <td>#<?php echo $sL->id_livre;?></td>
                                <td>
                                    <div class="client">
                                        <div class="client-img bg-img" style="background-image: url(img/3.jpeg)"></div>
                                        <div class="client-info">
                                            <h4><?php echo $sL->livre_titre;?></h4>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    auteur
                                </td>

                                <td>
                                    Type
                                </td>

                                <td>
                                    genre
                                </td>

                                <td>
                                    <?php echo $sL->livre_date_create;?>
                                </td>

                                <td>
                                    <div class="actions">
                                        <span class="lab la-telegram-plane"></span>
                                        <a href="livres.php?zone=livres&action=modifLivre&id=<?php echo $sL->id_livre;?>">
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
include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/footer.php');

?>