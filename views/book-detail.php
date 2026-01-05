<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

// ================== HEADER ==================
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');

// ================== ID livre ==================
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    // si pas d'id => retour catalogue
    header("Location: /views/catalog.php");
    exit;
}

// petit helper: récupérer une colonne possible dans un row (si elle existe)
function pickField(array $row, array $keys, $default = '')
{
    foreach ($keys as $k) {
        if (array_key_exists($k, $row) && $row[$k] !== null && $row[$k] !== '') {
            return $row[$k];
        }
    }
    return $default;
}

// ================== Livre ==================
$selectLivre = $db->prepare("
    SELECT livres.*, langues.langue_nom
    FROM livres
    LEFT JOIN langues ON livres.id_langue = langues.id_langue
    WHERE livres.id_livre = ?
    LIMIT 1
");
$selectLivre->execute([$id]);
$livre = $selectLivre->fetch(PDO::FETCH_ASSOC);

if (!$livre) {
    header("Location: /views/catalog.php");
    exit;
}

// ================== Genres (tags) ==================
$selectGenres = $db->prepare("
    SELECT genres.*
    FROM livres_genres
    NATURAL JOIN genres
    WHERE id_livre = ?
");
$selectGenres->execute([$id]);
$genres = $selectGenres->fetchAll(PDO::FETCH_ASSOC);

// ================== Série ==================
$selectSeries = $db->prepare("
    SELECT series.*
    FROM livres_series
    NATURAL JOIN series
    WHERE id_livre = ?
");
$selectSeries->execute([$id]);
$series = $selectSeries->fetchAll(PDO::FETCH_ASSOC); // parfois plusieurs, on affichera la première

// ================== Auteurs ==================
$selectAuteurs = $db->prepare("
    SELECT auteurs.*
    FROM livres_auteurs
    NATURAL JOIN auteurs
    WHERE id_livre = ?
");
$selectAuteurs->execute([$id]);
$auteurs = $selectAuteurs->fetchAll(PDO::FETCH_ASSOC);

// auteur principal pour l’affichage (si plusieurs, on prend le 1er)
$auteurMain = $auteurs[0] ?? null;

// ================== Vars affichage ==================
$titre = $livre['livre_titre'] ?? 'Titre';
$isbn  = $livre['livre_isbn'] ?? '';
$editeur = $livre['livre_editeur'] ?? '';
$langue = $livre['langue_nom'] ?? '';
$synopsis = $livre['livre_synopsis'] ?? '';

$datePubRaw = $livre['livre_date_publication'] ?? '';
$datePub = $datePubRaw;
if (!empty($datePubRaw) && $datePubRaw !== '0000-00-00') {
    $ts = strtotime($datePubRaw);
    if ($ts) {
        // format FR simple
        $datePub = date('d/m/Y', $ts);
    }
}

$serieNom = $series[0]['serie_nom'] ?? '';

$auteurNom = 'Auteur inconnu';
$auteurId = 0;
if ($auteurMain) {
    $auteurNom = trim(($auteurMain['auteur_prenom'] ?? '') . ' ' . ($auteurMain['auteur_nom'] ?? ''));
    $auteurId = (int)($auteurMain['id_auteur'] ?? 0);
}

// cover
$coverFile = !empty($livre['livre_couverture']) ? $livre['livre_couverture'] : '1.jpeg';
$coverSrc = "/assets/bo/img/" . $coverFile;

// photo auteur (on tente plusieurs noms possibles, sinon on garde ton image existante)
$auteurPhoto = $auteurMain ? pickField($auteurMain, ['auteur_photo', 'auteur_image', 'photo', 'image'], '') : '';
$auteurPhotoSrc = !empty($auteurPhoto) ? ("/assets/bo/img/" . $auteurPhoto) : "../assets/fo/img/books/tolkien.jpeg";

// nationalité / dates / bio (si ça existe en BDD, on le prend)
$auteurNat = $auteurMain ? pickField($auteurMain, ['auteur_nationalite', 'nationalite'], '') : '';
$auteurNaissance = $auteurMain ? pickField($auteurMain, ['auteur_date_naissance', 'date_naissance', 'naissance'], '') : '';
$auteurDeces = $auteurMain ? pickField($auteurMain, ['auteur_date_deces', 'date_deces', 'deces'], '') : '';
$auteurBio = $auteurMain ? pickField($auteurMain, ['auteur_bio', 'bio', 'auteur_description', 'description'], '') : '';
?>

<!-- ================== MAIN ================== -->

<section class="book-detail flex-row wrap">

    <div class="cover flex-col ai-center">
        <img src="<?php echo htmlspecialchars($coverSrc); ?>" alt="<?php echo htmlspecialchars($titre); ?>">

        <div class="book-btn flex-row jc-center ai-center">
            <button class="bd-btn">Emprunter</button>
            <button class="bd-btn">Favoris</button>
        </div>
    </div>

    <div class="content flex-col">
        <h2 class="title"><?php echo htmlspecialchars($titre); ?></h2>
        <h3><?php echo htmlspecialchars($auteurNom); ?></h3>

        <p class="description">
            <?php
            // synopsis peut contenir du HTML si tu utilises CKEditor côté BO
            echo !empty($synopsis) ? $synopsis : "Aucun synopsis.";
            ?>
        </p>

        <div class="book-tags flex-row ai-center wrap">
            <?php
            if (!empty($genres)) {
                foreach ($genres as $g) {
                    $tag = $g['genre_tag'] ?? '';
                    if ($tag !== '') {
                        echo '<button class="bd-btn">' . htmlspecialchars($tag) . '</button>';
                    }
                }
            }
            ?>
        </div>

        <div class="content-details flex-row">
            <div class="cd-title flex-col">
                <p>Série</p>
                <p>Éditeur</p>
                <p>Language</p>
                <p>ISBN</p>
                <p>Publié le</p>
            </div>

            <div class="cd-content flex-col">
                <p><?php echo htmlspecialchars($serieNom); ?></p>
                <p><?php echo htmlspecialchars($editeur); ?></p>
                <p><?php echo htmlspecialchars($langue); ?></p>
                <p><?php echo htmlspecialchars($isbn); ?></p>
                <p><?php echo htmlspecialchars($datePub); ?></p>
            </div>
        </div>
    </div>

    <div class="author flex-col">
        <a href="author-detail.php<?php echo ($auteurId > 0 ? ('?id=' . $auteurId) : ''); ?>" class="author-header flex-row ai-center">
            <img src="<?php echo htmlspecialchars($auteurPhotoSrc); ?>" alt="">
            <h2 class=""><?php echo htmlspecialchars($auteurNom); ?></h2>
        </a>

        <div class="content-details flex-row">
            <div class="cd-title flex-col">
                <p>Nationalité</p>
                <p>Née le</p>
                <p>Décédé le</p>
            </div>
            <div class="cd-content flex-col">
                <p><?php echo htmlspecialchars($auteurNat ?: ''); ?></p>
                <p><?php echo htmlspecialchars($auteurNaissance ?: ''); ?></p>
                <p><?php echo htmlspecialchars($auteurDeces ?: ''); ?></p>
            </div>
        </div>

        <p>
            <?php
            // Si pas de bio en BDD, on laisse vide (tu pourras remplir plus tard)
            echo !empty($auteurBio) ? $auteurBio : "";
            ?>
        </p>
    </div>

</section>

<!-- ================== FOOTER ================== -->
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php');
?>