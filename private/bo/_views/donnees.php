<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Auteurs / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/ariane.php');


if(isset($_GET['action']) && $_GET['action'] == "modifGenre"){
    
    echo "Ici se trouvera l'environnement de modification des genres.";

}else if(isset($_GET['action']) && $_GET['action'] == "modifSeries"){

    echo "Ici se trouvera l'environnement de modification des séries.";

}else if(isset($_GET['action']) && $_GET['action'] == "modifLangues"){

    echo "Ici se trouvera l'environnement de modification des langues.";

}else{

    $selectgenres = $db->prepare('SELECT * FROM genres');
    $selectgenres->execute();

    if(isset($_POST['addgenre'])){
        $nom = htmlspecialchars($_POST['genre_nom']);

        $insert_genre = $db->prepare('INSERT INTO genres SET
            genre_nom = ?
        ');
        $insert_genre->execute([$nom]);

        echo "<script language='javascript'>
            document.location.replace('livres.php?zone=donnees&action=addgenre')
            </script>";
    }

    ?>

    <form method="POST">

        <div>
            <label for="">Nom du genre</label>
            <input genre="text" name="genre_nom">
        </div>

        <div>
            <label for="">Description du genre</label>
            <textarea name="genre_description" id="" placeholder="Décrire le genre"></textarea>
        </div>

        <div>
            <input genre="submit" value="Enr" name="addgenre">
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
                        <th><span class="las la-sort"></span> genres</th>
                        <th><span class="las la-sort"></span> ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($sT = $selectgenres->fetch(PDO::FETCH_OBJ)){
                            ?>
                            <tr>
                                <td>#<?php echo $sT->id_genre;?></td>
                                <td>
                                    <div class="client">
                                        <div class="client-info">
                                            <h4><?php echo $sT->genre_nom;?></h4>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="actions">
                                        <span class="lab la-telegram-plane"></span>
                                        <a href="livres.php?zone=livres&action=modifgenre&id=<?php echo $sT->id_genre;?>">
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






<?php
include($_SERVER['DOCUMENT_ROOT'].'/private/bo/_blocks/footer.php');
?>