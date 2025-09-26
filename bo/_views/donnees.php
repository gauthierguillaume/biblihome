<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Auteurs / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');

//prep for the tables (used in the ifs and in main)

    $selectGenres = $db->prepare('SELECT * FROM genres');
    $selectGenres->execute();

    $selectSeries = $db->prepare('SELECT * FROM series');
    $selectSeries->execute();
    
    $selectLangues = $db->prepare('SELECT * FROM langues');
    $selectLangues->execute();


//functions that also are used both in the ifs and the main. i am lazy.

    //fonction add genre
    if(isset($_POST['addgenre'])){
        $genre_tag = htmlspecialchars($_POST['genre_tag']);

        $insert_genre = $db->prepare('INSERT INTO genres SET
            genre_tag = ?
        ');
        $insert_genre->execute([$genre_tag]);

        $newId = $db->lastInsertId();

        echo "<script language='javascript'>
            document.location.replace('donnees.php?zone=donnees&action=modifGenres&id=$newId')
            </script>";
    }

    //fonction add série
    if(isset($_POST['addserie'])){
        $serie_nom = htmlspecialchars($_POST['serie_nom']);

        $insert_genre = $db->prepare('INSERT INTO series SET
            serie_nom = ?
        ');
        $insert_genre->execute([$serie_nom]);

        $newId = $db->lastInsertId();

        echo "<script language='javascript'>
            document.location.replace('donnees.php?zone=donnees&action=modifSeries&id=$newId')
            </script>";
    }

    //fonction add langue
    if(isset($_POST['addlangue'])){
        $langue_nom = htmlspecialchars($_POST['langue_nom']);

        $insert_genre = $db->prepare('INSERT INTO langues SET
            langue_nom = ?
        ');
        $insert_genre->execute([$langue_nom]);

        $newId = $db->lastInsertId();

        echo "<script language='javascript'>
            document.location.replace('donnees.php?zone=donnees&action=modifLangues&id=$newId')
            </script>";
    }



//modification des genres
if(isset($_GET['action']) && $_GET['action'] == "modifGenres"){

    //modification d'un élément
    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $selectOneGenre = $db->prepare('SELECT * FROM genres
            WHERE id_genre = ?
            ');
        $selectOneGenre->execute([$id]);
        $sOG = $selectOneGenre->fetch(PDO::FETCH_OBJ);

        //selects for this genre only
        $selectGenreLivre = $db->prepare('SELECT * FROM livres_genres
            WHERE id_genre = ?
            ');
        $selectGenreLivre->execute([$id]);
        $cGL = count($selectGenreLivre->fetchAll());

        $selectGenreEmprunt = $db->prepare('SELECT id_emprunt FROM emprunt
            NATURAL JOIN genres
            NATURAL JOIN livres_genres
            NATURAL JOIN livres
            NATURAL JOIN exemplaires
            WHERE id_genre = ?
            GROUP BY id_emprunt
            ');
        $selectGenreEmprunt->execute([$id]);
        $cGE = count($selectGenreEmprunt->fetchAll());

        $selectGenreFavoris = $db->prepare('SELECT id_favoris FROM favoris
            NATURAL JOIN genres
            NATURAL JOIN livres_genres
            NATURAL JOIN livres
            WHERE id_genre = ?
            GROUP BY id_favoris
            ');
        $selectGenreFavoris->execute([$id]);
        $cGF = count($selectGenreFavoris->fetchAll());

        //general selects
        $countLivres = $db->prepare('SELECT * FROM livres');
        $countLivres->execute();
        $cL = count($countLivres->fetchAll());

        $countEmprunts = $db->prepare('SELECT * FROM emprunt');
        $countEmprunts->execute();
        $cE = count($countEmprunts->fetchALL());

        $countFavoris = $db->prepare('SELECT * FROM favoris');
        $countFavoris->execute();
        $cF = count($countFavoris->fetchAll());

        //nubby's percentages factory
        if($cL > 0){
            $calcLivre = ($sGL / $cL) * 100.0;
            $roundedLivre = number_format($calcLivre, 2);
        }else{
            $roundedLivre = 0;
        }

        if($cE > 0){
            $calcEmprunt = ($sGE / $cE) * 100.0;
            $roundedEmprunt = number_format($calcEmprunt, 2);
        }else{
            $roundedEmprunt = 0;
        }

        if($cF > 0){
            $calcFav = ($sGF / $cF) * 100.0;
            $roundedFav = number_format($calcFav, 2);
        }else{
            $roundedFav = 0;
        }


        //rename form
        if(isset($_POST["rename"])){
            $renameText = $_POST["renameText"];

            $update = $db->prepare('UPDATE genres SET
                genre_tag = ?
                WHERE id_genre = ?
            ');
            $update->execute([$renameText, $id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifGenres&id=$id')
            </script>";
        }

        //transfer form function
        if(isset($_POST["transferGenre"])){
            $selectTransfer = $_POST["selectTransfer"];

            $transfer = $db->prepare('UPDATE livres_genres SET
                id_genre = ?
                WHERE id_genre = ?
            ');
            $transfer->execute([$selectTransfer, $id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifGenres&id=$id')
            </script>";
        }

        //delete form function
        if(isset($_POST["nukeGenre"])){

            $deleteAssoc = $db->prepare('DELETE FROM livres_genres
                WHERE id_genre = ?
            ');
            $deleteAssoc->execute([$id]);

            $deleteMain = $db->prepare('DELETE FROM genres
                WHERE id_genre = ?
            ');
            $deleteMain->execute([$id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifGenres')
            </script>";
        }
        ?>

        <!-- start of html -->
         <!-- fiche info genre -->
        <h2>Fiche d'information du genre</h2>
        <ul class="fiche">
            <li>
                <span class="liTitle">Nom : </span> 
                <label class="label-hover" for="checkbox-hide-genre">
                    <?php echo $sOG->genre_tag; ?> 
                    <i class="las la-pen"></i>
                </label>
                <input type="checkbox" name="checkbox-hide-genre" id="checkbox-hide-genre">
            
                <form id="form-genre-name" method="POST">
                    <input type="text" name="renameText" value="<?php echo $sOG->genre_tag; ?>">
                    <input type="submit" name="rename" value="Renommer">
                </form>
        
            </li>
            <li><span class="liTitle">Nombre d'ouvrages : </span> <?php echo $cGL; ?> <i class="liPercent">soit <?php echo $roundedLivre; ?>% du total (<?php echo $cL; ?>)</i></li>
            <li><span class="liTitle">Emprunts : </span> <?php echo $cGE; ?> <i class="liPercent">soit <?php echo $roundedEmprunt; ?>% du total (<?php echo $cE; ?>)</i></li>
            <li><span class="liTitle">Favoris : </span> <?php echo $cGF; ?> <i class="liPercent">soit <?php echo $roundedFav; ?>% du total (<?php echo $cF; ?>)</i></li>
        </ul>


        <form method="POST" class="formTransfer">
            
            <h2>Transférer les livres de ce genre à un autre genre </h2>
            <select name="selectTransfer">
                <?php while($sG = $selectGenres->fetch(PDO::FETCH_OBJ)){
                    ?>
                    <option value="<?php echo $sG->id_genre; ?>"><?php echo $sG->genre_tag; ?></option>
                    <?php    
                }
                ?>
            </select>
            <button id="openTransferWarning" type="button">Transférer</button>


            <!-- (zany face emoji) it's set to fixed ! just fuckin put it in the form ! who gives a shit ! -->
            <div id="transferWarning" class="delete-warning flexCol justifyCenter alignCenter">
                <p>Transférer les livres du genre <?php echo $sOG->genre_tag; ?> ?</p>
                <p>Cette action est irréversible !</p>
                <div class="flexRow buttonsWarning">
                    <button id="closeTransferWarning" class="close-warning" type="button">Annuler</button>
                    <input type="submit" value="Transférer" name="transferGenre">
                </div>
            </div>

        </form>

        <button id="openDeleteWarning">Supprimer le genre</button>
        
        <div id="deleteWarning" class="delete-warning flexCol justifyCenter alignCenter">
            <p>Supprimer le genre <?php echo $sOG->genre_tag; ?> ?</p>
            <p>Cette action est irréversible !</p>
            <div class="flexRow buttonsWarning">
                <button id="closeDeleteWarning" class="close-warning">Annuler</button>
                <form method="POST">
                    <input type="submit" value="Supprimer" name="nukeGenre">
                </form>
            </div>
        </div>



        <?php

    //renvoi vers la liste principale 
    }else{
        ?>
        <!--form add genre-->
        <form class="addSpecific" method="POST">

            <div class="flexCol">
                <label for="genre_tag">Enregistrer un nouveau genre <i class="las la-hand-point-down"></i></label>
                <input type="text" name="genre_tag">
            </div>

            <div class="add">
                <input type="submit" value="Enregistrer" name="addgenre">
            </div>

        </form>


        <!-- table genre -->
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
                                        <h4><?php echo $sG->genre_tag;?></h4>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <a href="donnees.php?zone=donnees&action=modifGenres&id=<?php echo $sG->id_genre;?>">
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
        <?php
    }


//modification des séries
}else if(isset($_GET['action']) && $_GET['action'] == "modifSeries"){


    //modification d'un élément
    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $selectOneSerie = $db->prepare('SELECT * FROM series
            WHERE id_serie = ?
            ');
        $selectOneSerie->execute([$id]);
        $sOF = $selectOneSerie->fetch(PDO::FETCH_OBJ);

        //selects for this serie only
        $selectSerieLivre = $db->prepare('SELECT * FROM livres_series
            WHERE id_serie = ?
            ');
        $selectSerieLivre->execute([$id]);
        $cSL = count($selectSerieLivre->fetchAll());

        $selectSerieEmprunt = $db->prepare('SELECT id_emprunt FROM emprunt
            NATURAL JOIN series
            NATURAL JOIN livres_series
            NATURAL JOIN livres
            NATURAL JOIN exemplaires
            WHERE id_serie = ?
            GROUP BY id_emprunt
            ');
        $selectSerieEmprunt->execute([$id]);
        $cSE = count($selectSerieEmprunt->fetchAll());

        $selectSerieFavoris = $db->prepare('SELECT id_favoris FROM favoris
            NATURAL JOIN series
            NATURAL JOIN livres_series
            NATURAL JOIN livres
            WHERE id_serie = ?
            GROUP BY id_favoris
            ');
        $selectSerieFavoris->execute([$id]);
        $cSF = count($selectSerieFavoris->fetchAll());

        //general selects
        $countLivres = $db->prepare('SELECT * FROM livres');
        $countLivres->execute();
        $cL = count($countLivres->fetchAll());

        $countEmprunts = $db->prepare('SELECT * FROM emprunt');
        $countEmprunts->execute();
        $cE = count($countEmprunts->fetchALL());

        $countFavoris = $db->prepare('SELECT * FROM favoris');
        $countFavoris->execute();
        $cF = count($countFavoris->fetchAll());

        //nubby's percentages factory
        if($cL > 0){
            $calcLivre = ($sSL / $cL) * 100.0;
            $roundedLivre = number_format($calcLivre, 2);
        }else{
            $roundedLivre = 0;
        }

        if($cE > 0){
            $calcEmprunt = ($sSE / $cE) * 100.0;
            $roundedEmprunt = number_format($calcEmprunt, 2);
        }else{
            $roundedEmprunt = 0;
        }

        if($cF > 0){
            $calcFav = ($sSF / $cF) * 100.0;
            $roundedFav = number_format($calcFav, 2);
        }else{
            $roundedFav = 0;
        }


        //rename form
        if(isset($_POST["rename"])){
            $renameText = $_POST["renameText"];

            $update = $db->prepare('UPDATE series SET
                serie_nom = ?
                WHERE id_serie = ?
            ');
            $update->execute([$renameText, $id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifSeries&id=$id')
            </script>";
        }

        //transfer form function
        if(isset($_POST["transferSerie"])){
            $selectTransfer = $_POST["selectTransfer"];

            $transfer = $db->prepare('UPDATE livres_series SET
                id_serie = ?
                WHERE id_serie = ?
            ');
            $transfer->execute([$selectTransfer, $id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifSeries&id=$id')
            </script>";
        }

        //delete form function
        if(isset($_POST["nukeSerie"])){

            $deleteAssoc = $db->prepare('DELETE FROM livres_series
                WHERE id_serie = ?
            ');
            $deleteAssoc->execute([$id]);

            $deleteMain = $db->prepare('DELETE FROM series
                WHERE id_serie = ?
            ');
            $deleteMain->execute([$id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifSeries')
            </script>";
        }
        ?>

        <!-- start of html -->
         <!-- fiche info séries -->
        <h2>Fiche d'information de la série</h2>
        <ul class="fiche">
            <li>
                <span class="liTitle">Nom : </span> 
                <label class="label-hover" for="checkbox-hide-serie">
                    <?php echo $sOF->serie_nom; ?> 
                    <i class="las la-pen"></i>
                </label>
                <input type="checkbox" name="checkbox-hide-serie" id="checkbox-hide-serie">
            
                <form id="form-serie-name" method="POST">
                    <input type="text" name="renameText" value="<?php echo $sOF->serie_nom; ?>">
                    <input type="submit" name="rename" value="Renommer">
                </form>
        
            </li>
            <li><span class="liTitle">Nombre d'ouvrages : </span> <?php echo $cSL; ?> <i class="liPercent">soit <?php echo $roundedLivre; ?>% du total (<?php echo $cL; ?>)</i></li>
            <li><span class="liTitle">Emprunts : </span> <?php echo $cSE; ?> <i class="liPercent">soit <?php echo $roundedEmprunt; ?>% du total (<?php echo $cE; ?>)</i></li>
            <li><span class="liTitle">Favoris : </span> <?php echo $cSF; ?> <i class="liPercent">soit <?php echo $roundedFav; ?>% du total (<?php echo $cF; ?>)</i></li>
        </ul>


        <form method="POST" class="formTransfer">
            
            <h2>Transférer les livres de cette série à une autre série </h2>
            <select name="selectTransfer">
                <?php while($sSe = $selectSeries->fetch(PDO::FETCH_OBJ)){
                    ?>
                    <option value="<?php echo $sSe->id_serie; ?>"><?php echo $sSe->serie_nom; ?></option>
                    <?php    
                }
                ?>
            </select>
            <button id="openTransferWarning" type="button">Transférer</button>


            <!-- (zany face emoji) it's set to fixed ! just fuckin put it in the form ! who gives a shit ! -->
            <div id="transferWarning" class="delete-warning flexCol justifyCenter alignCenter">
                <p>Transférer les livres de la série <?php echo $sOF->serie_nom; ?> ?</p>
                <p>Cette action est irréversible !</p>
                <div class="flexRow buttonsWarning">
                    <button id="closeTransferWarning" class="close-warning" type="button">Annuler</button>
                    <input type="submit" value="Transférer" name="transferSerie">
                </div>
            </div>

        </form>

        <button id="openDeleteWarning">Supprimer la série</button>
        
        <div id="deleteWarning" class="delete-warning flexCol justifyCenter alignCenter">
            <p>Supprimer la série <?php echo $sOF->serie_nom; ?> ?</p>
            <p>Cette action est irréversible !</p>
            <div class="flexRow buttonsWarning">
                <button id="closeDeleteWarning" class="close-warning">Annuler</button>
                <form method="POST">
                    <input type="submit" value="Supprimer" name="nukeSerie">
                </form>
            </div>
        </div>



        <?php

    //renvoi vers la liste principale 
    }else{
        ?>
        <!--form add serie-->
        <form class="addSpecific" method="POST">

            <div class="flexCol">
                <label for="serie_nom">Enregistrer une nouvelle série <i class="las la-hand-point-down"></i></label>
                <input type="text" name="serie_nom">
            </div>

            <div class="add">
                <input type="submit" value="Enregistrer" name="addserie">
            </div>

        </form>
        <!-- table serie -->
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
                    while($sSe = $selectSeries->fetch(PDO::FETCH_OBJ)){
                        ?>
                        <tr>
                            <td>#<?php echo $sSe->id_serie;?></td>
                            <td>
                                <div class="client">
                                    <div class="client-info">
                                        <h4><?php echo $sSe->serie_nom;?></h4>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <a href="donnees.php?zone=donnees&action=modifSeries&id=<?php echo $sSe->id_serie;?>">
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
        <?php
    }



//modification des langues
}else if(isset($_GET['action']) && $_GET['action'] == "modifLangues"){

    //modification d'un élément
    if(isset($_GET['id'])){
        $id = $_GET['id'];

        $selectOneLangue = $db->prepare('SELECT * FROM langues
            WHERE id_langue = ?
            ');
        $selectOneLangue->execute([$id]);
        $sOL = $selectOneLangue->fetch(PDO::FETCH_OBJ);

        //selects for this langue only
        $selectLangueLivre = $db->prepare('SELECT * FROM livres
            WHERE id_langue = ?
            ');
        $selectLangueLivre->execute([$id]);
        $cLL = count($selectLangueLivre->fetchAll());

        $selectLangueEmprunt = $db->prepare('SELECT id_emprunt FROM emprunt
            NATURAL JOIN langues
            NATURAL JOIN livres
            NATURAL JOIN exemplaires
            WHERE id_langue = ?
            GROUP BY id_emprunt
            ');
        $selectLangueEmprunt->execute([$id]);
        $cLE = count($selectLangueEmprunt->fetchAll());

        $selectLangueFavoris = $db->prepare('SELECT id_favoris FROM favoris
            NATURAL JOIN langues
            NATURAL JOIN livres
            WHERE id_langue = ?
            GROUP BY id_favoris
            ');
        $selectLangueFavoris->execute([$id]);
        $cLF = count($selectLangueFavoris->fetchAll());

        //general selects
        $countLivres = $db->prepare('SELECT * FROM livres');
        $countLivres->execute();
        $cL = count($countLivres->fetchAll());

        $countEmprunts = $db->prepare('SELECT * FROM emprunt');
        $countEmprunts->execute();
        $cE = count($countEmprunts->fetchALL());

        $countFavoris = $db->prepare('SELECT * FROM favoris');
        $countFavoris->execute();
        $cF = count($countFavoris->fetchAll());

        //nubby's percentages factory
        if($cL > 0){
            $calcLivre = ($sLL / $cL) * 100.0;
            $roundedLivre = number_format($calcLivre, 2);
        }else{
            $roundedLivre = 0;
        }

        if($cE > 0){
            $calcEmprunt = ($sLE / $cE) * 100.0;
            $roundedEmprunt = number_format($calcEmprunt, 2);
        }else{
            $roundedEmprunt = 0;
        }

        if($cF > 0){
            $calcFav = ($sLF / $cF) * 100.0;
            $roundedFav = number_format($calcFav, 2);
        }else{
            $roundedFav = 0;
        }


        //rename form
        if(isset($_POST["rename"])){
            $renameText = $_POST["renameText"];

            $update = $db->prepare('UPDATE langues SET
                langue_nom = ?
                WHERE id_langue = ?
            ');
            $update->execute([$renameText, $id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifLangues&id=$id')
            </script>";
        }

        //transfer form function
        if(isset($_POST["transferLangue"])){
            $selectTransfer = $_POST["selectTransfer"];

            $transfer = $db->prepare('UPDATE livres SET
                id_langue = ?
                WHERE id_langue = ?
            ');
            $transfer->execute([$selectTransfer, $id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifLangues&id=$id')
            </script>";
        }

        //delete form function
        if(isset($_POST["nukeLangue"]) && $cLL < 1){

            $deleteMain = $db->prepare('DELETE FROM langues
                WHERE id_langue = ?
            ');
            $deleteMain->execute([$id]);

            echo "<script language='javascript'>
            document.location.replace('donnees.php?action=modifLangues')
            </script>";
        }
        ?>

        <!-- start of html -->
         <!-- fiche info langue -->
        <h2>Fiche d'information de la langue</h2>
        <ul class="fiche">
            <li>
                <span class="liTitle">Nom : </span> 
                <label class="label-hover" for="checkbox-hide-langue">
                    <?php echo $sOL->langue_nom; ?> 
                    <i class="las la-pen"></i>
                </label>
                <input type="checkbox" name="checkbox-hide-langue" id="checkbox-hide-langue">
            
                <form id="form-langue-name" method="POST">
                    <input type="text" name="renameText" value="<?php echo $sOL->langue_nom; ?>">
                    <input type="submit" name="rename" value="Renommer">
                </form>
        
            </li>
            <li><span class="liTitle">Nombre d'ouvrages : </span> <?php echo $cLL; ?> <i class="liPercent">soit <?php echo $roundedLivre; ?>% du total (<?php echo $cL; ?>)</i></li>
            <li><span class="liTitle">Emprunts : </span> <?php echo $cLE; ?> <i class="liPercent">soit <?php echo $roundedEmprunt; ?>% du total (<?php echo $cE; ?>)</i></li>
            <li><span class="liTitle">Favoris : </span> <?php echo $cLF; ?> <i class="liPercent">soit <?php echo $roundedFav; ?>% du total (<?php echo $cF; ?>)</i></li>
        </ul>


        <form method="POST" class="formTransfer">
            
            <h2>Transférer les livres de cette langue à une autre langue </h2>
            <select name="selectTransfer">
                <?php while($sL = $selectLangues->fetch(PDO::FETCH_OBJ)){
                    ?>
                    <option value="<?php echo $sL->id_langue; ?>"><?php echo $sL->langue_nom; ?></option>
                    <?php    
                }
                ?>
            </select>
            <button id="openTransferWarning" type="button">Transférer</button>


            <!-- (zany face emoji) it's set to fixed ! just fuckin put it in the form ! who gives a shit ! -->
            <div id="transferWarning" class="delete-warning flexCol justifyCenter alignCenter">
                <p>Transférer les livres de la langue <?php echo $sOL->langue_nom; ?> ?</p>
                <p>Cette action est irréversible !</p>
                <div class="flexRow buttonsWarning">
                    <button id="closeTransferWarning" class="close-warning" type="button">Annuler</button>
                    <input type="submit" value="Transférer" name="transferLangue">
                </div>
            </div>

        </form>

                <?php
                //brute force for tests 
                //$cLL=7; 
                ?>

        <button id="openDeleteWarning" class="<?php if($cLL > 0){ echo "disabled"; } ?>">Supprimer la langue</button>

        <div id="deleteWarning" class="delete-warning flexCol justifyCenter alignCenter">
            <?php if($cLL === 0){ 
                ?>
                <p>Supprimer la langue <?php echo $sOL->langue_nom; ?> ?</p>
                <p>Cette action est irréversible !</p>
                <div class="flexRow buttonsWarning">
                    <button id="closeDeleteWarning" class="close-warning">Annuler</button>
                    <form method="POST">
                        <input type="submit" value="Supprimer" name="nukeLangue">
                    </form>
                </div> 
                <?php
            }else{
                ?>
                <p>Pour supprimer la langue "<?php echo $sOL->langue_nom; ?>" assurez-vous qu'aucun livre ne l'utilise.</p>
                <div class="flexRow buttonsWarning">
                    <button id="closeDeleteWarning" class="close-warning">Fermer</button>
                </div>
                <?php
            } ?>
        </div>



        <?php

    //renvoi vers la liste principale 
    }else{
        ?>
        <!--form add langue-->
        <form class="addSpecific" method="POST">

            <div class="flexCol">
                <label for="langue_nom">Enregistrer une nouvelle langue <i class="las la-hand-point-down"></i></label>
                <input type="text" name="langue_nom">
            </div>

            <div class="add">
                <input type="submit" value="Enregistrer" name="addlangue">
            </div>

        </form>

        <!-- table langue -->
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
                    while($sL = $selectLangues->fetch(PDO::FETCH_OBJ)){
                        ?>
                        <tr>
                            <td>#<?php echo $sL->id_langue;?></td>
                            <td>
                                <div class="client">
                                    <div class="client-info">
                                        <h4><?php echo $sL->langue_nom;?></h4>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <a href="donnees.php?zone=donnees&action=modifLangues&id=<?php echo $sL->id_langue;?>">
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
        <?php
    }

//main page
}else{

    ?>




    <div class="records table-responsive">
        
        <div class="recordsContainer flexRow spaceAround">

            <!-- genres -->
            <div class="flexCol donnees">
                <!-- button gestion genre -->
                <div class="add">
                    <a href="donnees.php?zone=donnees&action=modifGenres">Gérer les genres</a>     
                </div>

                <!--form add genre-->
                <form method="POST">

                    <div class="flexCol">
                        <label for="genre_tag">Enregistrer un nouveau genre <i class="las la-hand-point-down"></i></label>
                        <input type="text" name="genre_tag">
                    </div>

                    <div class="add">
                        <input type="submit" value="Enregistrer" name="addgenre">
                    </div>

                </form>

                <!-- table genre -->
                <table width="100%">
                    <thead>
                        <tr>
                            <th class="idPadding">#</th>
                            <th><span class="las la-sort"></span> GENRES</th>
                            <th class="tableAction"><span class="las la-sort"></span> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($sG = $selectGenres->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <tr>
                                    <td class="idPadding">#<?php echo $sG->id_genre;?></td>
                                    <td>
                                        <div class="client">
                                            <div class="client-info">
                                                <h4><?php echo $sG->genre_tag;?></h4>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="tableAction">
                                        <div class="actions">
                                            <span class="lab la-telegram-plane"></span>
                                            <a href="donnees.php?zone=donnees&action=modifGenres&id=<?php echo $sG->id_genre;?>">
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

            <!-- series -->
             <div class="flexCol donnees">
                <!-- button gestion serie -->
                <div class="add">              
                    <a href="donnees.php?zone=donnees&action=modifSeries">Gérer les séries</a>   
                </div>

                <!--form add series-->
                <form method="POST">

                    <div class="flexCol">
                        <label for="serie_nom">Enregistrer une nouvelle série <i class="las la-hand-point-down"></i></label>
                        <input type="text" name="serie_nom">
                    </div>

                    <div class="add">
                        <input type="submit" value="Enregistrer" name="addserie">
                    </div>

                </form>

                <!-- table serie -->
                <table width="100%">
                    <thead>
                        <tr>
                            <th class="idPadding">#</th>
                            <th><span class="las la-sort"></span> SERIES</th>
                            <th class="tableAction"><span class="las la-sort"></span> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($sSe = $selectSeries->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <tr>
                                    <td class="idPadding">#<?php echo $sSe->id_serie;?></td>
                                    <td>
                                        <div class="client">
                                            <div class="client-info">
                                                <h4><?php echo $sSe->serie_nom;?></h4>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="tableAction">
                                        <div class="actions">
                                            <span class="lab la-telegram-plane"></span>
                                            <a href="donnees.php?zone=donnees&action=modifSeries&id=<?php echo $sSe->id_serie;?>">
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
            
            <!-- langues -->
            <div class="flexCol donnees">   
                <!-- button gestion langues --> 
                <div class="add">           
                    <a href="donnees.php?zone=donnees&action=modifLangues">Gérer les langues</a>
                </div>
                
                <!--form add langues-->
                <form method="POST">

                    <div class="flexCol">
                        <label for="langue_nom">Enregistrer une nouvelle langue <i class="las la-hand-point-down"></i></label>
                        <input type="text" name="langue_nom">
                    </div>

                    <div class="add">
                        <input type="submit" value="Enregistrer" name="addlangue">
                    </div>

                </form>

                <!--table langues-->
                <table width="100%">
                    <thead>
                        <tr>
                            <th class="idPadding">#</th>
                            <th><span class="las la-sort"></span> LANGUES</th>
                            <th class="tableAction"><span class="las la-sort"></span> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($sL = $selectLangues->fetch(PDO::FETCH_OBJ)){
                                ?>
                                <tr>
                                    <td class="idPadding">#<?php echo $sL->id_langue;?></td>
                                    <td>
                                        <div class="client">
                                            <div class="client-info">
                                                <h4><?php echo $sL->langue_nom;?></h4>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="tableAction">
                                        <div class="actions">
                                            <span class="lab la-telegram-plane"></span>
                                            <a href="donnees.php?zone=donnees&action=modifLangues&id=<?php echo $sL->id_langue;?>">
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

    </div>

    <?php
}
?>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']?>/assets/bo/js/donnees.js"></script>
<?php
include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');
?>