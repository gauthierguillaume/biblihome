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
            <input type="text" name="livre_titre" value="<?php echo $livre->livre_titre;?>">
        </div>

        <div>
            <label for="">synopsis du livre</label>
            <textarea class="ckeditor" name="livre_synopsis" id="" placeholder="Ecrire le synopsis ici"><?php echo $livre->livre_synopsis;?></textarea>
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
            <input type="submit" value="Enr" name="addAuteur">
        </div>
    </form>

    <?php

}else{

    $selectLivres = $db->prepare('SELECT * FROM livres');
    $selectLivres->execute();

    $selectGenres = $db->prepare('SELECT * FROM genres');
    $selectGenres->execute();

    $selectSeries = $db->prepare('SELECT * FROM series');
    $selectSeries->execute();

    $selectLangues = $db->prepare('SELECT * FROM langues');
    $selectLangues->execute();

    $selectAuteurs = $db->prepare('SELECT * FROM auteurs');
    $selectAuteurs->execute();


    if(isset($_POST['addLivre'])){

        //gathering all the data, all of it

        $titre = htmlspecialchars($_POST['livre_titre']);
        $isbn = htmlspecialchars($_POST['livre_isnb']);
        $langue = $_POST['id_langue'];
        $genre = $_POST['id_genre'];
        $serie = $_POST['id_serie'];
        $synopsis = $_POST['livre_synopsis']; //this input isn't sanitized cuz it receives html info. it SHOULD however, in practice, be ran through some kind of "remove all the disallowed html tags server side too" script if we were to do this proper
        $editeur = htmlspecialchars($_POST['livre_edition']);
        $date = $_POST['livre_date_create'];

        //if i don't do an empty check it throws an error ig :V
        if(!empty($FILES_['livre_couverture'])){
        $img = noaccent($_FILES['livre_couverture']['name']);
        $tmp_img = $_FILES['livre_couverture']['tmp_name'];
        $error = $_FILES['livre_couverture']['error'];
        $size = $_FILES['livre_couverture']['size'];
        $type = $_FILES['livre_couverture']['type'];
        }

        //checking if the main stuff can be uploaded
        if(!empty($titre) && !empty($isbn) && !empty($langue)){

            $insert_livre = $db->prepare('INSERT INTO livres SET
                livre_titre = ?,
                livre_isbn = ?,
                id_langue = ?
            ');
            $insert_livre->execute([$titre, $isbn, $langue]);
            $newId = $db->lastInsertId();

            //logic for the optional bits
            //image (by far the one with the most checks (upload error, size, type))
            if(!empty($img)){
                if($error == 0){
                    if($size < 2000000){
                        if($img_ext === "jpeg" || $img_ext === "png" || $img_ext === "jpg"){
                                                
                            $ext = explode('/', $type);
                            $img_ext = end($ext);
                            $img_name = $newId.'.'.$img_ext;

                            $insertImg = $db->prepare("UPDATE livres SET
                            livre_couverture = ?
                            WHERE id_livre = ?
                            ");
                            $insertImg->execute([$img_name, $newId]);
                            
                            move_uploaded_file($tmp_img, $_SERVER['DOCUMENT_ROOT'].'/bo/_imgs/admin/'.$img_name);
                            
                        }else{ 
                            $_SESSION['flash']['danger'] = "Sélectionnez un fichier image.";
                        }

                    }else{ 
                        $_SESSION['flash']['danger'] = "Votre image est trop lourde (maximum 2Mo)";
                    }

                }else{ 
                    $_SESSION['flash']['danger'] = "Le téléchargement a échoué.";
                }
            }

            //genre
            if(!empty($genre)){
                $insertGenre = $db->prepare("UPDATE livres SET
                id_genre = ?
                WHERE id_livre = ?
                ");
                $insertGenre->execute([$genre, $newId]);
            }

            //serie
            if(!empty($serie)){
                $insertSerie = $db->prepare("UPDATE livres SET
                id_serie = ?
                WHERE id_livre = ?
                ");
                $insertSerie->execute([$serie, $newId]);
            }

            //synopsis
            if(!empty($synopsis)){
                $insertSynopsis = $db->prepare("UPDATE livres SET
                livre_synopsis = ?
                WHERE id_livre = ?
                ");
                $insertSynopsis->execute([$synopsis, $newId]);
            }

            //editeur
            if(!empty($editeur)){
                $insertSynopsis = $db->prepare("UPDATE livres SET
                livre_editeur = ?
                WHERE id_livre = ?
                ");
                $insertSynopsis->execute([$editeur, $newId]);
            }

            //date
            if(!empty($date)){
                $insertDate = $db->prepare("UPDATE livres SET
                livre_date = ?
                WHERE id_livre = ?
                ");
                $insertDate->execute([$date, $newId]);
            }

            echo "<script language='javascript'>
                document.location.replace('livres.php?zone=livres&action=modifLivre&id=$newId')
                </script>";

        }else{
            $_SESSION['flash']['danger'] = "Vous n'avez pas renseigné tout les champs obligatoires";

            //making it so if they forgot to fill something the user keeps non-dropdown data so they won't want to commit die(); because they just lost 30 lines of synopsis           
            $_SESSION['old']['livre_titre'] = $titre;
            $_SESSION['old']['livre_isnb'] = $isbn;
            $_SESSION['old']['livre_edition'] = $editeur;
            $_SESSION['old']['livre_synopsis'] = $synopsis;

            // echo "<script language='javascript'>
            // document.location.replace('livres.php?zone=livres')
            // </script>"; 
        }
    }

    //pushing the fail's data in & emptying the variable
    if(!empty($_SESSION['old'])){
    $titre = $_SESSION['old']['livre_titre'];
    $isbn = $_SESSION['old']['livre_isnb'];
    $editeur = $_SESSION['old']['livre_edition'];
    $synopsis = $_SESSION['old']['livre_synopsis'];
    unset($_SESSION['old']);
    }
    ?>

    <form method="POST">

        <div>
            <label for="">Nom (obligatoire)</label>
            <input type="text" name="livre_titre" value="<?php if(!empty($titre)){echo $titre ;}?>">
        </div>

        <div>
            <label for="">isbn (obligatoire)</label>
            <input type="text" name="livre_isnb" value="">
        </div>
                
        <div>
            <label for="">Langue (obligatoire)</label>
            <select name="id_langue" id="" value="">
                <?php
                while($sL = $selectLangues->fetch(PDO::FETCH_OBJ)){
                    ?>
                        <option value="<?php echo $sL->id_langue;?>"><?php echo $sL->langue_nom;?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        
        <div>
            <label for="">Genre</label>
            <select name="id_genre" id="">
                <option value="" class="nullSelect">(Aucun)</option>
                <?php
                while($sG = $selectGenres->fetch(PDO::FETCH_OBJ)){
                    ?>
                        <option value="<?php echo $sG->id_genre;?>"><?php echo $sG->genre_tag;?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div>
            <label for="">Serie</label>
            <select name="id_serie" id="">
                <option value="" class="nullSelect">(Aucune)</option>
                <?php
                while($sSe = $selectSeries->fetch(PDO::FETCH_OBJ)){
                    ?>
                        <option value="<?php echo $sSe->id_serie;?>"><?php echo $sSe->serie_nom;?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div>
            <label for="">Ajouter une image de couverture</label>
            <input type="file" enctype="multipart/form-data" name="livre_couverture">
        </div>

        <div>
            <label for="">Editeur</label>
            <input type="text" name="livre_edition">
        </div>

        <div>
            <label for="">Synopsis</label>
            <textarea name="livre_synopsis" id="" placeholder="Ecrire le synopsis ici"></textarea>
        </div>

        <div>
            <label for="">Date de publication</label>
            <input type="date" name="livre_date_create">
        </div>

        <div>
            <input type="submit" value="Enregistrer le livre" id="addLivre" name="addLivre">
        </div>

    </form>

    <div class="records table-responsive">

        <div>
            <table width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><span class="las la-sort"></span> TITRE</th>
                        <th><span class="las la-sort"></span> ISBN</th>
                        <th><span class="las la-sort"></span> LANGUE</th>
                        <th><span class="las la-sort"></span> GENRE</th>
                        <th><span class="las la-sort"></span> SERIE</th>
                        <th><span class="las la-sort"></span> SYNOPSIS</th>
                        <th><span class="las la-sort"></span> EDITEUR</th>
                        <th><span class="las la-sort"></span> PUBLICATION</th>
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