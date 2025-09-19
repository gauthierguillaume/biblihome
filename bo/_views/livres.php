<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Livres / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');



if(isset($_GET['action']) && $_GET['action'] == "modifLivre"){

    // Je récupère l'id dans le Get qui correspond au livre que je veux modifier.
    $id = $_GET['id'];

    // Je sélectionne le livre dans la table à l'aide de l'id récupéré dans le GET.
    $selectLivre = $db->prepare('SELECT * FROM livres
        NATURAL JOIN livres_auteurs
        NATURAL JOIN auteurs
        NATURAL JOIN livres_genres
        NATURAL JOIN genres
        NATURAL JOIN livres_series
        NATURAL JOIN series
        NATURAL JOIN langues
        WHERE id_livre = ?
    ');
    $selectLivre->execute([$id]);
    $livre = $selectLivre->fetch(PDO::FETCH_OBJ);

    $id_langue = $livre->id_langue;

    $select_genres = $db->prepare('SELECT *FROM genres
        WHERE id_genre != ?
    ');
    $select_genres->execute([$id_genre]);

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
        $synopsis = $_POST['livre_synopsis'];

        $updateLivre = $db->prepare('UPDATE livres SET
            livre_synopsis = ?
            WHERE id_livre = ?    
        ');
        $updateLivre->execute([$synopsis, $id]);

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

        <label for="">Sélectionner le genre</label>
        <select name="id_genre" id="">
            <option value="<?php echo $livre->id_genre;?>"><?php echo $livre->genre_nom;?></option>
            <?php
            while($sT = $select_genres->fetch(PDO::FETCH_OBJ)){
                ?>
                    <option value="<?php echo $sT->id_genre;?>"><?php echo $sT->genre_nom;?></option>
                <?php
            }
            ?>
        </select>

        <div>
            <label for="">Nom du livre</label>
            <input genre="text" name="livre_titre" value="<?php echo $livre->livre_titre;?>">
        </div>

        <div>
            <label for="">synopsis du livre</label>
            <textarea class="ckeditor" name="livre_synopsis" id="" placeholder="Ecrire le synopsis ici"><?php echo $livre->livre_synopsis;?></textarea>
        </div>

        <div>
            <label for="">Date d'écriture du livre</label>
            <input genre="date" name="livre_date_create" value="<?php echo $livre->livre_date_create;?>">
        </div>

        <div>
            <input genre="submit" value="Enr" name="updateLivre">
        </div>

    </form>

    <script>
        CKEDITOR.replace('livre_synopsis');
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
            <input genre="submit" value="Enr" name="addAuteur">
        </div>
    </form>

    <?php

}else{

    $select_genres = $db->prepare('SELECT * FROM genres');
    $select_genres->execute();

    $selectLivres = $db->prepare('SELECT * FROM livres');
    $selectLivres->execute();

    if(isset($_POST['addLivre'])){
        $titre = htmlspecialchars($_POST['livre_titre']);
        $synopsis = htmlspecialchars($_POST['livre_synopsis']);
        $date = $_POST['livre_date_create'];
        $genre = $_POST['id_genre'];

        $insert_livre = $db->prepare('INSERT INTO livres SET
            livre_titre = ?,
            livre_synopsis = ?,
            livre_date_create = ?,
            id_genre = ?
        ');
        $insert_livre->execute([$titre, $synopsis, $date, $genre]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=livres')
            </script>";
    }

    ?>

    <form method="POST">

        <label for="">Sélectionner le genre</label>
        <select name="id_genre" id="">
            <?php
            while($sT = $select_genres->fetch(PDO::FETCH_OBJ)){
                ?>
                    <option value="<?php echo $sT->id_genre;?>"><?php echo $sT->genre_nom;?></option>
                <?php
            }
            ?>
        </select>

        <div>
            <label for="">Nom du livre</label>
            <input genre="text" name="livre_titre">
        </div>

        <div>
            <label for="">synopsis du livre</label>
            <textarea name="livre_synopsis" id="" placeholder="Ecrire le synopsis ici"></textarea>
        </div>

        <div>
            <label for="">Date d'écriture du livre</label>
            <input genre="date" name="livre_date_create">
        </div>

        <div>
            <input genre="submit" value="Enr" name="addLivre">
        </div>

    </form>

    <div class="records table-responsive">

        <div class="record-header">
            <div class="add">
                <span>Entries</span>
                <select name="" id="">
                    <option value="">ID</option>
                </select>

                <a href="livres.php?zone=livres&action=addgenre">Gérer les genres</a>                
                <a href="livres.php?zone=livres&action=addGenre">Gérer les genres</a>

            </div>

            <div class="browse">
                <input genre="search" placeholder="Search" class="record-search">
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
                        <th><span class="las la-sort"></span> genre</th>
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
                                    genre
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
include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');

?>