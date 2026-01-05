<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

// ================== HEADER ==================
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');

// ================== ID auteur ==================
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /views/catalog.php");
    exit;
}

// petit helper: récupérer une colonne possible si elle existe
function pickField(array $row, array $keys, $default = '')
{
    foreach ($keys as $k) {
        if (array_key_exists($k, $row) && $row[$k] !== null && $row[$k] !== '') {
            return $row[$k];
        }
    }
    return $default;
}

// ================== Auteur ==================
$selectAuteur = $db->prepare("SELECT * FROM auteurs WHERE id_auteur = ? LIMIT 1");
$selectAuteur->execute([$id]);
$auteur = $selectAuteur->fetch(PDO::FETCH_ASSOC);

if (!$auteur) {
    header("Location: /views/catalog.php");
    exit;
}

$auteurNom = trim(($auteur['auteur_prenom'] ?? '') . ' ' . ($auteur['auteur_nom'] ?? ''));
if ($auteurNom === '') $auteurNom = "Auteur";

$auteurNat = pickField($auteur, ['auteur_nationalite', 'nationalite'], '');
$auteurNaissance = pickField($auteur, ['auteur_date_naissance', 'date_naissance', 'naissance'], '');
$auteurDeces = pickField($auteur, ['auteur_date_deces', 'date_deces', 'deces'], '');
$auteurBio = pickField($auteur, ['auteur_biographie', 'auteur_bio', 'bio', 'auteur_description', 'description'], '');

// photo auteur : plusieurs noms possibles selon ta BDD
$auteurPhoto = pickField($auteur, ['auteur_photo', 'auteur_image', 'photo', 'image'], '');
$auteurPhotoSrc = !empty($auteurPhoto)
    ? ("/assets/bo/img/" . $auteurPhoto)
    : "../assets/fo/img/books/tolkien.jpeg"; // fallback (garde ton visuel)

// ================== Bibliographie (livres de l’auteur) ==================
$selectLivres = $db->prepare("
    SELECT
        l.id_livre,
        l.livre_titre,
        l.livre_couverture,
        MIN(CONCAT(a2.auteur_prenom, ' ', a2.auteur_nom)) AS auteur_nom
    FROM livres l
    INNER JOIN livres_auteurs la ON la.id_livre = l.id_livre
    INNER JOIN auteurs a ON a.id_auteur = la.id_auteur

    -- Pour afficher un auteur “principal” (si multi-auteurs, on affiche le 1er trouvé)
    LEFT JOIN livres_auteurs la2 ON la2.id_livre = l.id_livre
    LEFT JOIN auteurs a2 ON a2.id_auteur = la2.id_auteur

    WHERE a.id_auteur = ?
    GROUP BY l.id_livre, l.livre_titre, l.livre_couverture
    ORDER BY l.id_livre DESC
");
$selectLivres->execute([$id]);
$livres = $selectLivres->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ================== MAIN ================== -->

<section class="author-detail flex-row wrap">

    <div class="cover flex-col ai-center">
        <img src="<?php echo htmlspecialchars($auteurPhotoSrc); ?>" alt="">
    </div>

    <div class="author flex-col">
        <h2 class=""><?php echo htmlspecialchars($auteurNom); ?></h2>

        <div class="content-details flex-row">
            <div class="cd-title flex-col">
                <p>Nationalité</p>
                <p>Née le</p>
                <p>Décédé le</p>
            </div>
            <div class="cd-content flex-col">
                <p><?php echo htmlspecialchars($auteurNat); ?></p>
                <p><?php echo htmlspecialchars($auteurNaissance); ?></p>
                <p><?php echo htmlspecialchars($auteurDeces); ?></p>
            </div>
        </div>

        <p>
            <?php
            // bio peut contenir du HTML si tu le gères comme ça (sinon simple texte)
            echo !empty($auteurBio) ? $auteurBio : "";
            ?>
        </p>
    </div>

    <div class="biblio flex-col">
        <h2 class="title underline-hug">BIBLIOGRAPHIE</h2>

        <div class="books flex-row ai-center wrap">
            <?php
            if (!empty($livres)) {
                $i = 1;
                foreach ($livres as $l) {
                    $class = "books-" . (($i - 1) % 4 + 1);

                    $coverFile = !empty($l['livre_couverture']) ? $l['livre_couverture'] : '1.jpeg';
                    $coverSrc  = "/assets/bo/img/" . $coverFile;

                    $titre = $l['livre_titre'] ?? 'Titre';
                    $authorLabel = !empty($l['auteur_nom']) ? $l['auteur_nom'] : $auteurNom;
            ?>
                    <a href="book-detail.php?id=<?php echo (int)$l['id_livre']; ?>" class="<?php echo $class; ?>">
                        <img src="<?php echo htmlspecialchars($coverSrc); ?>" alt="<?php echo htmlspecialchars($titre); ?>">
                        <p class="title"><?php echo htmlspecialchars($titre); ?></p>
                        <p class="author"><?php echo htmlspecialchars($authorLabel); ?></p>
                    </a>
            <?php
                    $i++;
                }
            } else {
                echo "<p style='opacity:.8;'>Aucun livre trouvé pour cet auteur.</p>";
            }
            ?>
        </div>
    </div>

</section>

<!-- ================== FOOTER ================== -->
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php');
?>