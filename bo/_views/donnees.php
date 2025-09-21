<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Auteurs / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');


if(isset($_GET['action']) && $_GET['action'] == "modifGenres"){
    
    echo "Ici se trouvera l'environnement de modification des genres.";

}else if(isset($_GET['action']) && $_GET['action'] == "modifSeries"){

    echo "Ici se trouvera l'environnement de modification des séries.";

}else if(isset($_GET['action']) && $_GET['action'] == "modifLangues"){

    echo "Ici se trouvera l'environnement de modification des langues.";

}else{


    //fonction add genre
    $selectgenres = $db->prepare('SELECT * FROM genres');
    $selectgenres->execute();

    if(isset($_POST['addgenre'])){
        $genre_tag = htmlspecialchars($_POST['genre_tag']);

        $insert_genre = $db->prepare('INSERT INTO genres SET
            genre_tag = ?
        ');
        $insert_genre->execute([$genre_tag]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=donnees&action=modifGenres')
            </script>";
    }


    //fonction add série
    $selectseries = $db->prepare('SELECT * FROM series');
    $selectseries->execute();

    if(isset($_POST['addserie'])){
        $serie_nom = htmlspecialchars($_POST['serie_nom']);

        $insert_genre = $db->prepare('INSERT INTO series SET
            serie_nom = ?
        ');
        $insert_genre->execute([$serie_nom]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=donnees&action=modifSeries')
            </script>";
    }

    //fonction add langue
    $selectlangues = $db->prepare('SELECT * FROM langues');
    $selectlangues->execute();


    if(isset($_POST['addlangue'])){
        $langue_nom = htmlspecialchars($_POST['langue_nom']);

        $insert_genre = $db->prepare('INSERT INTO langues SET
            langue_nom = ?
        ');
        $insert_genre->execute([$langue_nom]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=donnees&action=modifLangues')
            </script>";
    }

    ?>




    <div class="records table-responsive">
        
        <div width="100%" class="flexRow spaceAround">

            <!-- genres -->
            <div class="flexCol donnees">
                <!-- button add genre -->
                <div class="add">
                    <a href="livres.php?zone=livres&action=modifGenres">Gérer les genres</a>     
                </div>

                <!--form genre-->
                <form method="POST">

                    <div>
                        <label for="genre_tag">Nom du genre</label>
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
                            while($sG = $selectgenres->fetch(PDO::FETCH_OBJ)){
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
                                            <a href="livres.php?zone=livres&action=modifGenres&id=<?php echo $sG->id_genre;?>">
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
                <!-- button add serie -->
                <div class="add">              
                    <a href="livres.php?zone=livres&action=modifSeries">Gérer les séries</a>   
                </div>

                <!--form series-->
                <form method="POST">

                    <div>
                        <label for="serie_nom">Nom de la série</label>
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
                            <th><span class="las la-sort"></span> SERIES</th>
                            <th><span class="las la-sort"></span> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($sSe = $selectseries->fetch(PDO::FETCH_OBJ)){
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
                                            <a href="livres.php?zone=livres&action=modifSeries&id=<?php echo $sSe->id_serie;?>">
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
                
                <div class="add">           
                    <a href="livres.php?zone=livres&action=modifLangues">Gérer les langues</a>
                </div>
                
                <!--form langues-->
                <form method="POST">

                    <div>
                        <label for="langue_nom">Nom de la langue</label>
                        <input type="text" name="langue_nom">
                    </div>

                    <div class="add">
                        <input type="submit" value="Enregistrer" name="addlangue">
                    </div>

                </form>

                <table width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><span class="las la-sort"></span> LANGUES</th>
                            <th><span class="las la-sort"></span> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($sL = $selectlangues->fetch(PDO::FETCH_OBJ)){
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
                                            <a href="livres.php?zone=livres&action=modifLangues&id=<?php echo $sL->id_langue;?>">
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

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');
?>