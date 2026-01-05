<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/sidebar.php');
include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Livres / Liste";

include($_SERVER['DOCUMENT_ROOT'] . '/bo/_blocks/ariane.php');

if (isset($_GET['action']) && $_GET['action'] == "modifLivre") {

    // Je récupère l'id dans le Get qui correspond au livre que je veux modifier.
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo "<script language='javascript'>document.location.replace('livres.php?zone=livres')</script>";
        exit;
    }

    // ====== Récup livre
    $selectLivre = $db->prepare('SELECT * FROM livres WHERE id_livre = ?');
    $selectLivre->execute([$id]);
    $livre = $selectLivre->fetch(PDO::FETCH_OBJ);

    if (!$livre) {
        echo "<script language='javascript'>document.location.replace('livres.php?zone=livres')</script>";
        exit;
    }

    // ====== Valeurs actuelles pivot (1 seul auteur/genre/série dans ton UI)
    $getAuteur = $db->prepare("SELECT id_auteur FROM livres_auteurs WHERE id_livre = ? LIMIT 1");
    $getAuteur->execute([$id]);
    $currentAuteur = (int)($getAuteur->fetchColumn() ?: 0);

    $getGenre = $db->prepare("SELECT id_genre FROM livres_genres WHERE id_livre = ? LIMIT 1");
    $getGenre->execute([$id]);
    $currentGenre = (int)($getGenre->fetchColumn() ?: 0);

    $getSerie = $db->prepare("SELECT id_serie FROM livres_series WHERE id_livre = ? LIMIT 1");
    $getSerie->execute([$id]);
    $currentSerie = (int)($getSerie->fetchColumn() ?: 0);

    // ====== Lists
    $selectLangues = $db->prepare('SELECT * FROM langues');
    $selectLangues->execute();

    $selectAuteursAll = $db->prepare('SELECT * FROM auteurs');
    $selectAuteursAll->execute();

    $selectGenresAll = $db->prepare('SELECT * FROM genres');
    $selectGenresAll->execute();

    $selectSeriesAll = $db->prepare('SELECT * FROM series');
    $selectSeriesAll->execute();

    // ====== DELETE (depuis la page d'édition)
    if (isset($_POST['deleteLivre'])) {

        // Optionnel : récupérer la couverture pour supprimer le fichier ensuite
        $getCover = $db->prepare("SELECT livre_couverture FROM livres WHERE id_livre = ?");
        $getCover->execute([$id]);
        $coverToDelete = $getCover->fetchColumn();

        // Suppressions (ordre important si FK)
        $db->prepare("DELETE FROM exemplaires WHERE id_livre = ?")->execute([$id]);
        $db->prepare("DELETE FROM livres_auteurs WHERE id_livre = ?")->execute([$id]);
        $db->prepare("DELETE FROM livres_genres  WHERE id_livre = ?")->execute([$id]);
        $db->prepare("DELETE FROM livres_series  WHERE id_livre = ?")->execute([$id]);

        // Livre
        $db->prepare("DELETE FROM livres WHERE id_livre = ?")->execute([$id]);

        // Optionnel : supprimer le fichier de couverture (sauf placeholder)
        if (!empty($coverToDelete)) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/bo/img/' . $coverToDelete;
            if (basename($coverToDelete) !== '1.jpeg' && file_exists($path)) {
                @unlink($path);
            }
        }

        $_SESSION['flash']['success'] = "Livre supprimé.";
        echo "<script language='javascript'>document.location.replace('livres.php?zone=livres')</script>";
        exit;
    }

    // ====== UPDATE
    if (isset($_POST['updateLivre'])) {

        $titre    = trim($_POST['livre_titre'] ?? '');
        $isbn     = trim($_POST['livre_isbn'] ?? '');
        $langue   = (int)($_POST['id_langue'] ?? 0);
        $auteur   = (int)($_POST['id_auteur'] ?? 0);
        $genre    = (int)($_POST['id_genre'] ?? 0);
        $serie    = (int)($_POST['id_serie'] ?? 0);
        $editeur  = trim($_POST['livre_editeur'] ?? '');
        $synopsis = $_POST['livre_synopsis'] ?? '';
        $datepub  = $_POST['livre_date_publication'] ?? null;

        // obligatoires (comme ton add)
        if ($titre !== '' && $isbn !== '' && $langue > 0) {

            // Update livres
            $upLivre = $db->prepare("UPDATE livres SET
                livre_titre = ?,
                livre_isbn = ?,
                id_langue = ?,
                livre_editeur = ?,
                livre_synopsis = ?,
                livre_date_publication = ?
                WHERE id_livre = ?
            ");
            $upLivre->execute([$titre, $isbn, $langue, $editeur, $synopsis, $datepub, $id]);

            // Update pivots (on remplace tout, simple et fiable)
            $db->prepare("DELETE FROM livres_auteurs WHERE id_livre = ?")->execute([$id]);
            if ($auteur > 0) {
                $db->prepare("INSERT INTO livres_auteurs (id_livre, id_auteur) VALUES (?, ?)")->execute([$id, $auteur]);
            }

            $db->prepare("DELETE FROM livres_genres WHERE id_livre = ?")->execute([$id]);
            if ($genre > 0) {
                $db->prepare("INSERT INTO livres_genres (id_livre, id_genre) VALUES (?, ?)")->execute([$id, $genre]);
            }

            $db->prepare("DELETE FROM livres_series WHERE id_livre = ?")->execute([$id]);
            if ($serie > 0) {
                $db->prepare("INSERT INTO livres_series (id_livre, id_serie) VALUES (?, ?)")->execute([$id, $serie]);
            }

            // Upload couverture (optionnel)
            if (!empty($_FILES['livre_couverture']) && !empty($_FILES['livre_couverture']['name'])) {

                $error = $_FILES['livre_couverture']['error'];
                $size  = $_FILES['livre_couverture']['size'];
                $type  = $_FILES['livre_couverture']['type'];

                if ($error == 0) {
                    if ($size < 5000000) {

                        $ext = explode('/', $type);
                        $img_ext = strtolower(end($ext));

                        if (in_array($img_ext, ['jpeg', 'png', 'jpg', 'webp'])) {

                            $img_name = $id . '.' . $img_ext;

                            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/bo/img/';
                            if (!is_dir($uploadDir)) {
                                @mkdir($uploadDir, 0777, true);
                            }

                            move_uploaded_file($_FILES['livre_couverture']['tmp_name'], $uploadDir . $img_name);

                            $insertImg = $db->prepare("UPDATE livres SET livre_couverture = ? WHERE id_livre = ?");
                            $insertImg->execute([$img_name, $id]);
                        } else {
                            $_SESSION['flash']['danger'] = "Sélectionnez un fichier image (jpg/png/webp).";
                        }
                    } else {
                        $_SESSION['flash']['danger'] = "Votre image est trop lourde (maximum 5Mo)";
                    }
                } else {
                    $_SESSION['flash']['danger'] = "Le téléchargement a échoué.";
                }
            }

            // retour liste
            echo "<script language='javascript'>document.location.replace('livres.php?zone=livres')</script>";
            exit;
        } else {
            $_SESSION['flash']['danger'] = "Vous n'avez pas renseigné tout les champs obligatoires";
        }
    }

    // Refresh livre + pivots après update / affichage
    $selectLivre->execute([$id]);
    $livre = $selectLivre->fetch(PDO::FETCH_OBJ);

    $getAuteur->execute([$id]);
    $currentAuteur = (int)($getAuteur->fetchColumn() ?: 0);

    $getGenre->execute([$id]);
    $currentGenre = (int)($getGenre->fetchColumn() ?: 0);

    $getSerie->execute([$id]);
    $currentSerie = (int)($getSerie->fetchColumn() ?: 0);

    // cover
    $cover = $livre->livre_couverture;
    if (empty($cover)) {
        $cover = '1.jpeg';
    }
?>

    <!-- Visuel inchangé: on reste sur des <div> simples + CKEditor -->
    <form method="POST" enctype="multipart/form-data">

        <p>Couverture actuelle :</p>
        <div class="client" style="margin-bottom: 10px;">
            <div class="client-img bg-img" style="background-image: url(/assets/bo/img/<?php echo $cover; ?>)"></div>
            <div class="client-info">
                <h4><?php echo $livre->livre_titre; ?></h4>
                <small>#<?php echo (int)$livre->id_livre; ?></small>
            </div>
        </div>

        <div>
            <label for="">Changer la couverture</label>
            <input type="file" name="livre_couverture">
        </div>

        <hr>

        <div>
            <label for="">Nom du livre (obligatoire)</label>
            <input type="text" name="livre_titre" value="<?php echo htmlspecialchars($livre->livre_titre); ?>">
        </div>

        <div>
            <label for="">ISBN (obligatoire)</label>
            <input type="text" name="livre_isbn" value="<?php echo htmlspecialchars($livre->livre_isbn); ?>">
        </div>

        <div>
            <label for="">Langue (obligatoire)</label>
            <select name="id_langue">
                <?php while ($sL = $selectLangues->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sL->id_langue; ?>" <?php if ((int)$livre->id_langue === (int)$sL->id_langue) echo "selected"; ?>>
                        <?php echo $sL->langue_nom; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Auteur</label>
            <select name="id_auteur">
                <option value="" class="nullSelect">(Aucun)</option>
                <?php while ($sA = $selectAuteursAll->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sA->id_auteur; ?>" <?php if ($currentAuteur === (int)$sA->id_auteur) echo "selected"; ?>>
                        <?php echo $sA->auteur_prenom; ?> <?php echo $sA->auteur_nom; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Genre</label>
            <select name="id_genre">
                <option value="" class="nullSelect">(Aucun)</option>
                <?php while ($sG = $selectGenresAll->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sG->id_genre; ?>" <?php if ($currentGenre === (int)$sG->id_genre) echo "selected"; ?>>
                        <?php echo $sG->genre_tag; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Serie</label>
            <select name="id_serie">
                <option value="" class="nullSelect">(Aucune)</option>
                <?php while ($sSe = $selectSeriesAll->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sSe->id_serie; ?>" <?php if ($currentSerie === (int)$sSe->id_serie) echo "selected"; ?>>
                        <?php echo $sSe->serie_nom; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Editeur</label>
            <input type="text" name="livre_editeur" value="<?php echo htmlspecialchars($livre->livre_editeur); ?>">
        </div>

        <div>
            <label for="">synopsis du livre</label>
            <textarea class="ckeditor" name="livre_synopsis" id="" placeholder="Ecrire le synopsis ici"><?php echo htmlspecialchars($livre->livre_synopsis); ?></textarea>
        </div>

        <div>
            <label for="">Date d'écriture du livre</label>
            <input type="date" name="livre_date_publication" value="<?php echo htmlspecialchars($livre->livre_date_publication); ?>">
        </div>

        <div>
            <input type="submit" value="Enregistrer" name="updateLivre">

            <button type="submit"
                name="deleteLivre"
                onclick="return confirm('Supprimer ce livre ? Cette action est irréversible.');"
                style="margin-left:10px;">
                Supprimer
            </button>

            <a href="livres.php?zone=livres" style="margin-left:10px;">Retour</a>
        </div>


    </form>

    <script>
        CKEDITOR.replace('livre_synopsis');
    </script>

<?php

} else {

    $selectLivres = $db->prepare(
        'SELECT * FROM livres
        NATURAL JOIN langues'
    );
    $selectLivres->execute();

    $selectGenres = $db->prepare('SELECT * FROM genres');
    $selectGenres->execute();

    $selectSeries = $db->prepare('SELECT * FROM series');
    $selectSeries->execute();

    $selectLangues = $db->prepare('SELECT * FROM langues');
    $selectLangues->execute();

    $selectAuteurs = $db->prepare('SELECT * FROM auteurs');
    $selectAuteurs->execute();

    if (isset($_POST['addLivre'])) {

        // champs
        $titre   = htmlspecialchars($_POST['livre_titre'] ?? '');
        $isbn    = htmlspecialchars($_POST['livre_isnb'] ?? ''); // garde ton name actuel
        $langue  = $_POST['id_langue'] ?? '';
        $auteur  = $_POST['id_auteur'] ?? '';
        $genre   = $_POST['id_genre'] ?? '';
        $serie   = $_POST['id_serie'] ?? '';
        $synopsis = $_POST['livre_synopsis'] ?? ''; // html possible
        $editeur = htmlspecialchars($_POST['livre_editeur'] ?? '');
        $date    = $_POST['livre_date_publication'] ?? '';

        // upload
        $img = '';
        $tmp_img = '';
        $error = 0;
        $size = 0;
        $type = '';
        $img_ext = '';

        if (!empty($_FILES['livre_couverture']) && !empty($_FILES['livre_couverture']['name'])) {
            // si tu as une fonction noAccent/noaccent dans functions.php, elle sera utilisée.
            $img = $_FILES['livre_couverture']['name'];
            if (function_exists('noAccent')) {
                $img = noAccent($img);
            } elseif (function_exists('noaccent')) {
                $img = noaccent($img);
            }

            $tmp_img = $_FILES['livre_couverture']['tmp_name'];
            $error   = $_FILES['livre_couverture']['error'];
            $size    = $_FILES['livre_couverture']['size'];
            $type    = $_FILES['livre_couverture']['type'];

            $ext = explode('/', $type);
            $img_ext = strtolower(end($ext));
        }

        // obligatoire
        if (!empty($titre) && !empty($isbn) && !empty($langue)) {

            $insert_livre = $db->prepare('INSERT INTO livres SET
                livre_titre = ?,
                livre_isbn = ?,
                id_langue = ?
            ');
            $insert_livre->execute([$titre, $isbn, $langue]);
            $newId = $db->lastInsertId();

            // dossier réel du projet (il existe dans ton zip)
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/bo/img/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            // image
            if (!empty($img)) {
                if ($error == 0) {
                    if ($size < 5000000) {
                        if (in_array($img_ext, ['jpeg', 'png', 'jpg'])) {

                            $img_name = $newId . '.' . $img_ext;

                            $insertImg = $db->prepare("UPDATE livres SET
                                livre_couverture = ?
                                WHERE id_livre = ?
                            ");
                            $insertImg->execute([$img_name, $newId]);

                            move_uploaded_file($tmp_img, $uploadDir . $img_name);
                        } else {
                            $_SESSION['flash']['danger'] = "Sélectionnez un fichier image (jpg/png).";
                        }
                    } else {
                        $_SESSION['flash']['danger'] = "Votre image est trop lourde (maximum 5Mo)";
                    }
                } else {
                    $_SESSION['flash']['danger'] = "Le téléchargement a échoué.";
                }
            }

            // auteur
            if (!empty($auteur)) {
                $insertauteur = $db->prepare("INSERT INTO livres_auteurs SET
                    id_auteur = ?,
                    id_livre = ?
                ");
                $insertauteur->execute([$auteur, $newId]);
            }

            // genre
            if (!empty($genre)) {
                $insertGenre = $db->prepare("INSERT INTO livres_genres SET
                    id_genre = ?,
                    id_livre = ?
                ");
                $insertGenre->execute([$genre, $newId]);
            }

            // serie
            if (!empty($serie)) {
                $insertSerie = $db->prepare("INSERT INTO livres_series SET
                    id_serie = ?,
                    id_livre = ?
                ");
                $insertSerie->execute([$serie, $newId]);
            }

            // synopsis
            if (!empty($synopsis)) {
                $insertSynopsis = $db->prepare("UPDATE livres SET
                    livre_synopsis = ?
                    WHERE id_livre = ?
                ");
                $insertSynopsis->execute([$synopsis, $newId]);
            }

            // editeur
            if (!empty($editeur)) {
                $insertEditeur = $db->prepare("UPDATE livres SET
                    livre_editeur = ?
                    WHERE id_livre = ?
                ");
                $insertEditeur->execute([$editeur, $newId]);
            }

            // date
            if (!empty($date)) {
                $insertDate = $db->prepare("UPDATE livres SET
                    livre_date_publication = ?
                    WHERE id_livre = ?
                ");
                $insertDate->execute([$date, $newId]);
            }
        } else {
            $_SESSION['flash']['danger'] = "Vous n'avez pas renseigné tout les champs obligatoires";

            unset($_SESSION['old']);
            $_SESSION['old'] = [
                'livre_titre' => $titre,
                'livre_isnb' => $isbn,         // ✅ bon nom
                'livre_editeur' => $editeur,
                'livre_synopsis' => $synopsis,
            ];

            echo "<script language='javascript'>
                document.location.replace('livres.php?zone=livres')
            </script>";
        }
    }

    // repop si erreur
    if (!empty($_SESSION['old'])) {

        $old = $_SESSION['old'];

        if (!empty($old['livre_titre'])) {
            $old_titre = $old['livre_titre'];
        }

        if (!empty($old['livre_isnb'])) {   // ✅ bon nom
            $old_isbn = $old['livre_isnb'];
        }

        if (!empty($old['livre_editeur'])) {
            $old_editeur = $old['livre_editeur'];
        }

        if (!empty($old['livre_synopsis'])) {
            $old_synopsis = $old['livre_synopsis'];
        }

        unset($_SESSION['old']);
    }
?>

    <!-- ✅ IMPORTANT: enctype sur le FORM (pas sur l'input) -->
    <form method="POST" enctype="multipart/form-data">

        <div>
            <label for="">Nom (obligatoire)</label>
            <input type="text" name="livre_titre" value="<?php if (!empty($old_titre)) {
                                                                echo $old_titre;
                                                            } ?>">
        </div>

        <div>
            <label for="">ISBN (obligatoire)</label>
            <input type="text" name="livre_isnb" value="<?php if (!empty($old_isbn)) {
                                                            echo $old_isbn;
                                                        } ?>">
        </div>

        <div>
            <label for="">Langue (obligatoire)</label>
            <select name="id_langue" id="" value="">
                <?php while ($sL = $selectLangues->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sL->id_langue; ?>"><?php echo $sL->langue_nom; ?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Auteur</label>
            <select name="id_auteur" id="" value="">
                <?php while ($sA = $selectAuteurs->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sA->id_auteur; ?>"><?php echo $sA->auteur_prenom; ?> <?php echo $sA->auteur_nom; ?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Genre</label>
            <select name="id_genre" id="">
                <option value="" class="nullSelect">(Aucun)</option>
                <?php while ($sG = $selectGenres->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sG->id_genre; ?>"><?php echo $sG->genre_tag; ?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Serie</label>
            <select name="id_serie" id="">
                <option value="" class="nullSelect">(Aucune)</option>
                <?php while ($sSe = $selectSeries->fetch(PDO::FETCH_OBJ)) { ?>
                    <option value="<?php echo $sSe->id_serie; ?>"><?php echo $sSe->serie_nom; ?></option>
                <?php } ?>
            </select>
        </div>

        <div>
            <label for="">Ajouter une image de couverture</label>
            <input type="file" name="livre_couverture">
        </div>

        <div>
            <label for="">Editeur</label>
            <input type="text" name="livre_editeur" value="<?php if (!empty($old_editeur)) {
                                                                echo $old_editeur;
                                                            } ?>">
        </div>

        <div>
            <label for="">Synopsis</label>
            <textarea name="livre_synopsis" id="" placeholder="Ecrire le synopsis ici"><?php if (!empty($old_synopsis)) {
                                                                                            echo $old_synopsis;
                                                                                        } ?></textarea>
        </div>

        <div>
            <label for="">Date de publication</label>
            <input type="date" name="livre_date_publication">
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
                        <th><span class="las la-sort"></span> AUTEUR</th>
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
                    while ($sL = $selectLivres->fetch(PDO::FETCH_OBJ)) {

                        $id = $sL->id_livre;

                        $selectLivresGenres = $db->prepare('SELECT * FROM livres_genres
                            NATURAL JOIN genres
                            WHERE id_livre = ?');
                        $selectLivresGenres->execute([$id]);

                        $selectLivresAuteurs = $db->prepare('SELECT * FROM livres_auteurs
                            NATURAL JOIN auteurs
                            WHERE id_livre = ?');
                        $selectLivresAuteurs->execute([$id]);

                        $selectLivresSeries = $db->prepare('SELECT * FROM livres_series
                            NATURAL JOIN series
                            WHERE id_livre = ?');
                        $selectLivresSeries->execute([$id]);

                        $selectExemplaires = $db->prepare('SELECT * FROM exemplaires
                            WHERE id_livre = ?');
                        $selectExemplaires->execute([$id]);
                        $sE = count($selectExemplaires->fetchAll());

                        $synopsis_short = $sL->livre_synopsis;
                        $short = '';
                        if (!empty($synopsis_short)) {
                            $short = mb_substr($synopsis_short, 0, 15, 'UTF-8');
                            if (mb_strlen($synopsis_short, 'UTF-8') > 14) {
                                $short .= '…';
                            }
                        }

                        // affichage couverture
                        $cover = $sL->livre_couverture;
                        if (empty($cover)) {
                            $cover = '1.jpeg';
                        }
                    ?>
                        <tr>
                            <td>#<?php echo $id; ?></td>

                            <td>
                                <div class="client">
                                    <div class="client-img bg-img" style="background-image: url(/assets/bo/img/<?php echo $cover; ?>)"></div>
                                    <div class="client-info">
                                        <h4><?php echo $sL->livre_titre; ?></h4>
                                        <small><?php echo $sE; ?> exemplaire<?php if ($sE != 1) {
                                                                                echo "s";
                                                                            } ?></small>
                                    </div>
                                </div>
                            </td>

                            <td><?php echo $sL->livre_isbn; ?></td>
                            <td><?php echo $sL->langue_nom; ?></td>

                            <td>
                                <?php while ($sLA = $selectLivresAuteurs->fetch(PDO::FETCH_OBJ)) {
                                    echo '<div>' . $sLA->auteur_prenom . ' ' . $sLA->auteur_nom . '</div>';
                                } ?>
                            </td>

                            <td>
                                <?php while ($sLG = $selectLivresGenres->fetch(PDO::FETCH_OBJ)) {
                                    echo '<div>' . $sLG->genre_tag . '</div>';
                                } ?>
                            </td>

                            <td>
                                <?php while ($sLS = $selectLivresSeries->fetch(PDO::FETCH_OBJ)) {
                                    echo '<div>' . $sLS->serie_nom . '</div>';
                                } ?>
                            </td>

                            <td><?php echo $short; ?></td>
                            <td><?php echo $sL->livre_editeur; ?></td>
                            <td><?php echo $sL->livre_date_publication; ?></td>

                            <td>
                                <div class="actions">
                                    <span class="lab la-telegram-plane"></span>
                                    <a href="livres.php?zone=livres&action=modifLivre&id=<?php echo $sL->id_livre; ?>">
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