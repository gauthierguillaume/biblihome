<?php
if (!isset($db)) {
    include($_SERVER['DOCUMENT_ROOT'] . '/host.php');
}

$selectBooks = $db->prepare("
    SELECT 
        l.id_livre,
        l.livre_titre,
        l.livre_couverture,
        MIN(CONCAT(a.auteur_prenom, ' ', a.auteur_nom)) AS auteur_nom
    FROM livres l
    LEFT JOIN livres_auteurs la ON la.id_livre = l.id_livre
    LEFT JOIN auteurs a ON a.id_auteur = la.id_auteur
    GROUP BY l.id_livre, l.livre_titre, l.livre_couverture
    ORDER BY l.id_livre DESC
    LIMIT 24
");
$selectBooks->execute();
$books = $selectBooks->fetchAll(PDO::FETCH_ASSOC);

$booksJson = htmlspecialchars(json_encode($books, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
?>

<section class="catalog-preview flex-col ta-center">
    <a href="../views/catalog.php">
        <h2 class="underline-hug">CATALOGUE</h2>
    </a>

    <div class="catalog-preview-row flex-row jc-center ai-center">

        <button type="button" class="catalog-arrow" id="catPrev" aria-label="Précédent">
            <img src="../assets/fo/img/icons/arrow for left and up.png" alt="">
        </button>

        <div class="books flex-row jc-center ai-center wrap" id="catBooks" data-books="<?php echo $booksJson; ?>">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <a href="#" class="books-<?php echo $i; ?>" data-slot="<?php echo $i - 1; ?>">
                    <img alt="">
                    <p class="title"></p>
                    <p class="author"></p>
                </a>
            <?php endfor; ?>
        </div>

        <button type="button" class="catalog-arrow" id="catNext" aria-label="Suivant">
            <img src="../assets/fo/img/icons/arrow for right and down.png" alt="">
        </button>

    </div>
</section>

<script src="/assets/fo/js/catalog-preview.js"></script>