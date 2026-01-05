<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

// ================== HEADER ==================
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');

// ================== FILTERS (GET) ==================
$title  = trim($_GET['title']  ?? '');
$author = trim($_GET['author'] ?? '');
$genre  = trim($_GET['genre']  ?? '');
$lang   = trim($_GET['lang']   ?? '');
$year   = trim($_GET['year']   ?? '');

// ✅ Tri (raccourcis)
$sort = $_GET['sort'] ?? 'recent';

$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

$perPage = 8;
$offset  = ($page - 1) * $perPage;

// ================== DATALISTS dynamiques ==================
$genresList = $db->query("SELECT genre_tag FROM genres ORDER BY genre_tag ASC")->fetchAll(PDO::FETCH_COLUMN);
$langList   = $db->query("SELECT langue_nom FROM langues ORDER BY langue_nom ASC")->fetchAll(PDO::FETCH_COLUMN);

$yearsList = $db->query("
    SELECT DISTINCT YEAR(
        STR_TO_DATE(
            NULLIF(CAST(livre_date_publication AS CHAR), ''),
            '%Y-%m-%d'
        )
    ) AS y
    FROM livres
    WHERE NULLIF(CAST(livre_date_publication AS CHAR), '') IS NOT NULL
      AND CAST(livre_date_publication AS CHAR) <> '0000-00-00'
    ORDER BY y DESC
")->fetchAll(PDO::FETCH_COLUMN);

// ================== Construction WHERE ==================
$where  = [];
$params = [];

// titre
if ($title !== '') {
    $where[]  = "l.livre_titre LIKE ?";
    $params[] = "%" . $title . "%";
}

// auteur (prenom ou nom)
if ($author !== '') {
    $where[]  = "(a.auteur_prenom LIKE ? OR a.auteur_nom LIKE ?)";
    $params[] = "%" . $author . "%";
    $params[] = "%" . $author . "%";
}

// genre (tag)
if ($genre !== '') {
    $where[]  = "g.genre_tag = ?";
    $params[] = $genre;
}

// langue (nom)
if ($lang !== '') {
    $where[]  = "la2.langue_nom = ?";
    $params[] = $lang;
}

// année
if ($year !== '' && ctype_digit($year)) {
    $where[]  = "CAST(l.livre_date_publication AS CHAR) LIKE ?";
    $params[] = $year . "-%";
}

$whereSql = "";
if (!empty($where)) {
    $whereSql = "WHERE " . implode(" AND ", $where);
}

// ================== helper URL (garde les filtres) ==================
function buildCatalogUrl(array $overrides = []): string
{
    $q = $_GET;
    foreach ($overrides as $k => $v) {
        if ($v === null) unset($q[$k]);
        else $q[$k] = $v;
    }

    // évite page vide
    if (isset($q['page']) && ((int)$q['page'] <= 1)) unset($q['page']);

    $qs = http_build_query($q);
    return "catalog.php" . ($qs ? "?" . $qs : "");
}

// ================== SORT whitelist ==================
$sortMap = [
    'recent'      => "l.id_livre DESC",
    'title_asc'   => "l.livre_titre ASC",
    'title_desc'  => "l.livre_titre DESC",
    'author_asc'  => "auteur_nom ASC",
    'author_desc' => "auteur_nom DESC",
    'date_desc'   => "l.livre_date_publication DESC",
    'date_asc'    => "l.livre_date_publication ASC",
    'genre_asc'   => "genre_tag ASC",
    'lang_asc'    => "la2.langue_nom ASC",
];

$orderSql = $sortMap[$sort] ?? $sortMap['recent'];

// ================== COUNT total (pagination) ==================
$countSql = "
    SELECT COUNT(DISTINCT l.id_livre)
    FROM livres l
    LEFT JOIN livres_auteurs la ON la.id_livre = l.id_livre
    LEFT JOIN auteurs a ON a.id_auteur = la.id_auteur
    LEFT JOIN livres_genres lg ON lg.id_livre = l.id_livre
    LEFT JOIN genres g ON g.id_genre = lg.id_genre
    LEFT JOIN langues la2 ON la2.id_langue = l.id_langue
    $whereSql
";
$stmtCount = $db->prepare($countSql);
$stmtCount->execute($params);
$totalBooks = (int)$stmtCount->fetchColumn();

$totalPages = (int)ceil(max(1, $totalBooks) / $perPage);
if ($page > $totalPages) $page = $totalPages;

// ✅ recalcul offset si page ajustée
$offset = ($page - 1) * $perPage;

// ================== SELECT books page ==================
$sqlBooks = "
    SELECT 
        l.id_livre,
        l.livre_titre,
        l.livre_couverture,
        la2.langue_nom,
        l.livre_date_publication,
        MIN(CONCAT(a.auteur_prenom, ' ', a.auteur_nom)) AS auteur_nom,
        MIN(g.genre_tag) AS genre_tag
    FROM livres l
    LEFT JOIN livres_auteurs la ON la.id_livre = l.id_livre
    LEFT JOIN auteurs a ON a.id_auteur = la.id_auteur
    LEFT JOIN livres_genres lg ON lg.id_livre = l.id_livre
    LEFT JOIN genres g ON g.id_genre = lg.id_genre
    LEFT JOIN langues la2 ON la2.id_langue = l.id_langue
    $whereSql
    GROUP BY l.id_livre, l.livre_titre, l.livre_couverture, la2.langue_nom, l.livre_date_publication
    ORDER BY $orderSql
    LIMIT $perPage OFFSET $offset
";
$stmtBooks = $db->prepare($sqlBooks);
$stmtBooks->execute($params);
$books = $stmtBooks->fetchAll(PDO::FETCH_ASSOC);

// ================== pagination links ==================
$prevUrl = ($page > 1) ? buildCatalogUrl(['page' => $page - 1]) : "#";
$nextUrl = ($page < $totalPages) ? buildCatalogUrl(['page' => $page + 1]) : "#";
?>

<!-- ================== MAIN ================== -->

<section class="catalog flex-row">

    <div class="catalog-filters flex-col">
        <h2 class="underline-fill">FILTRES</h2>

        <!-- ✅ Raccourcis de tri -->
        <div class="filter-shortcuts">
            <div class="shortcuts-wrap">

                <a class="chip" href="<?php echo htmlspecialchars(buildCatalogUrl(['sort' => 'recent', 'page' => 1])); ?>">Récent</a>
                <a class="chip" href="<?php echo htmlspecialchars(buildCatalogUrl(['sort' => 'title_asc', 'page' => 1])); ?>">Titre A→Z</a>
                <a class="chip" href="<?php echo htmlspecialchars(buildCatalogUrl(['sort' => 'author_asc', 'page' => 1])); ?>">Auteur A→Z</a>
                <a class="chip" href="<?php echo htmlspecialchars(buildCatalogUrl(['sort' => 'date_desc', 'page' => 1])); ?>">Date ↓</a>
                <a class="chip" href="<?php echo htmlspecialchars(buildCatalogUrl(['sort' => 'genre_asc', 'page' => 1])); ?>">Genre</a>
                <a class="chip" href="<?php echo htmlspecialchars(buildCatalogUrl(['sort' => 'lang_asc', 'page' => 1])); ?>">Langue</a>
            </div>
        </div>

        <!-- ✅ On passe en GET pour filtrer -->
        <form id="filtersForm" class="filters flex-col" method="GET" action="catalog.php">

            <!-- garde le tri actif quand tu recherches -->
            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

            <label class="field">
                <input class="input" type="text" name="title" placeholder="Titre" value="<?php echo htmlspecialchars($title); ?>">
            </label>

            <label class="field">
                <input class="input" type="text" name="author" placeholder="Auteur" value="<?php echo htmlspecialchars($author); ?>">
            </label>

            <label class="field flex-row ai-center">
                <input class="input" list="genre-list" name="genre" placeholder="Genre" value="<?php echo htmlspecialchars($genre); ?>">
                <datalist id="genre-list">
                    <?php foreach ($genresList as $gTag): ?>
                        <option value="<?php echo htmlspecialchars($gTag); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </label>

            <label class="field">
                <input class="input input--combo" list="lang-list" name="lang" placeholder="Langue" value="<?php echo htmlspecialchars($lang); ?>">
                <datalist id="lang-list">
                    <?php foreach ($langList as $ln): ?>
                        <option value="<?php echo htmlspecialchars($ln); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </label>

            <label class="field">
                <input class="input input--combo" list="year-list" name="year" placeholder="Année de publication" value="<?php echo htmlspecialchars($year); ?>">
                <datalist id="year-list">
                    <?php foreach ($yearsList as $y): ?>
                        <?php if (!empty($y)): ?>
                            <option value="<?php echo (int)$y; ?>"></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </datalist>
            </label>

            <!-- ✅ page à 1 quand on filtre -->
            <input type="hidden" name="page" value="1">
        </form>

        <div class="btns flex-col ai-center">
            <button class="btn flex-row ai-center" type="submit" form="filtersForm">
                <img src="../assets/fo/img/icons/search dm.png" alt="">
                Rechercher
            </button>

            <a class="btn flex-row ai-center" href="catalog.php" style="text-decoration:none;">
                <img src="../assets/fo/img/icons/playful button.png" alt="">
                Annuler
            </a>
        </div>

    </div>

    <div class="catalog-content flex-col">
        <div class="catalog-header flex-row jc-space-between ai-center">
            <h2 class="underline-hug">CATALOGUE</h2>

            <div class="catalog-pagination flex-row ai-center">
                <a href="<?php echo htmlspecialchars($prevUrl); ?>">
                    <img src="../assets/fo/img/icons/arrow for left and up.png" alt="">
                </a>
                <span class="page-info">Page <?php echo (int)$page; ?> / <?php echo (int)$totalPages; ?></span>
                <a href="<?php echo htmlspecialchars($nextUrl); ?>">
                    <img src="../assets/fo/img/icons/arrow for right and down.png" alt="">
                </a>
            </div>
        </div>

        <div class="catalog-row flex-row jc-space-between ai-center wrap">
            <div class="books flex-row jc-flex-start ai-center wrap">

                <?php
                $i = 1;
                foreach ($books as $b):

                    $class = "books-" . (($i - 1) % 4 + 1);

                    $coverFile = !empty($b['livre_couverture']) ? $b['livre_couverture'] : '1.jpeg';
                    $coverSrc  = "/assets/bo/img/" . $coverFile;

                    $bookTitle  = $b['livre_titre'] ?? 'Titre';
                    $bookAuthor = !empty($b['auteur_nom']) ? $b['auteur_nom'] : 'Auteur inconnu';
                ?>
                    <a href="book-detail.php?id=<?php echo (int)$b['id_livre']; ?>" class="<?php echo $class; ?>">
                        <img src="<?php echo htmlspecialchars($coverSrc); ?>" alt="<?php echo htmlspecialchars($bookTitle); ?>">
                        <p class="title"><?php echo htmlspecialchars($bookTitle); ?></p>
                        <p class="author"><?php echo htmlspecialchars($bookAuthor); ?></p>
                    </a>
                <?php
                    $i++;
                endforeach;

                if (empty($books)) {
                    echo "<p style='opacity:.8;'>Aucun résultat.</p>";
                }
                ?>

            </div>
        </div>

        <div class="catalog-footer flex-row jc-flex-end flex-end ai-center">
            <div class="catalog-pagination flex-row ai-center">
                <a href="<?php echo htmlspecialchars($prevUrl); ?>">
                    <img src="../assets/fo/img/icons/arrow for left and up.png" alt="">
                </a>
                <span class="page-info">Page <?php echo (int)$page; ?> / <?php echo (int)$totalPages; ?></span>
                <a href="<?php echo htmlspecialchars($nextUrl); ?>">
                    <img src="../assets/fo/img/icons/arrow for right and down.png" alt="">
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ================== FOOTER ================== -->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php'); ?>