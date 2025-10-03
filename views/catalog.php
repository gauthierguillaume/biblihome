<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

// ================== HEADER ==================

include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');
?>

<!-- ================== MAIN ================== -->

<section class="catalog flex-row">

    <div class="catalog-filters flex-col">
        <h2 class="underline-fill">FILTRES</h2>

        <form class="filters flex-col">
            <label class="field">
                <input class="input" type="text" name="title" placeholder="Titre">
            </label>

            <label class="field">
                <input class="input" type="text" name="author" placeholder="Auteur">
            </label>

            <!-- Liste déroulante + recherche (datalist) -->
            <label class="field flex-row ai-center">
                <input class="input" list="genre-list" name="genre" placeholder="Genre">
                <datalist id="genre-list">
                    <option value="Fantasy"></option>
                    <option value="Science-Fiction"></option>
                    <option value="Policier"></option>
                    <option value="Horreur"></option>
                    <option value="Romance"></option>
                </datalist>
            </label>

            <label class="field">
                <input class="input input--combo" list="lang-list" name="lang" placeholder="Langue">
                <datalist id="lang-list">
                    <option value="Français"></option>
                    <option value="Anglais"></option>
                    <option value="Espagnol"></option>
                    <option value="Allemand"></option>
                    <option value="Italien"></option>
                </datalist>
            </label>

            <label class="field">
                <input class="input input--combo" list="year-list" name="year" placeholder="Année de publication">
                <datalist id="year-list">
                    <option value="2025"></option>
                    <option value="2024"></option>
                    <option value="2023"></option>
                    <option value="2022"></option>
                    <option value="2021"></option>
                    <option value="2020"></option>
                </datalist>
            </label>
        </form>

        <div class="btns flex-col ai-center">
            <button class="btn flex-row ai-center" type="submit">
                <img src="../assets/fo/img/icons/search dm.png" alt="">
                Rechercher
            </button>
            <button class="btn flex-row ai-center" type="reset">
                <img src="../assets/fo/img/icons/playful button.png" alt="">
                Annuler
            </button>
        </div>

    </div>

    <div class="catalog-content flex-col">
        <div class="catalog-header flex-row jc-space-between ai-center">
            <h2 class="underline-hug">CATALOGUE</h2>
            <div class="catalog-pagination flex-row ai-center">
                <a href="#">
                    <img src="../assets/fo/img/icons/arrow for left and up.png" alt="">
                </a> <span class="page-info">Page 1 / 10</span>
                <a href="#">
                    <img src="../assets/fo/img/icons/arrow for right and down.png" alt="">
                </a>
            </div>
        </div>

        <div class="catalog-row flex-row jc-space-between ai-center wrap">
            <div class="books flex-row jc-flex-start ai-center wrap">
                <a href="book-detail.php" class="books-1">
                    <img src="../assets/fo/img/books/le-silmarillion.png" alt="Livre 1">
                    <p class="title">Le Silmarillion</p>
                    <p class="author">JRR Tolkien</p>
                </a>
                <a href="book-detail.php" class="books-2">
                    <img src="../assets/fo/img/books/it.png" alt="Livre 2">
                    <p class="title">It</p>
                    <p class="author">Stephen King</p>
                </a>
                <a href="book-detail.php" class="books-3">
                    <img src="../assets/fo/img/books/the-witcher.png" alt="Livre 3">
                    <p class="title">The Witcher</p>
                    <p class="author">Andrzej Sapkowski</p>
                </a>
                <a href="book-detail.php" class="books-4">
                    <img src="../assets/fo/img/books/la-route.png" alt="Livre 4">
                    <p class="title">La Route</p>
                    <p class="author">Cormac McCarthy</p>
                </a>
                <a href="book-detail.php" class="books-1">
                    <img src="../assets/fo/img/books/le-silmarillion.png" alt="Livre 1">
                    <p class="title">Le Silmarillion</p>
                    <p class="author">JRR Tolkien</p>
                </a>
                <a href="book-detail.php" class="books-1">
                    <img src="../assets/fo/img/books/le-silmarillion.png" alt="Livre 1">
                    <p class="title">Le Silmarillion</p>
                    <p class="author">JRR Tolkien</p>
                </a>
                <a href="book-detail.php" class="books-1">
                    <img src="../assets/fo/img/books/le-silmarillion.png" alt="Livre 1">
                    <p class="title">Le Silmarillion</p>
                    <p class="author">JRR Tolkien</p>
                </a>
                <a href="book-detail.php" class="books-1">
                    <img src="../assets/fo/img/books/le-silmarillion.png" alt="Livre 1">
                    <p class="title">Le Silmarillion</p>
                    <p class="author">JRR Tolkien</p>
                </a>
                <a href="book-detail.php" class="books-1">
                    <img src="../assets/fo/img/books/le-silmarillion.png" alt="Livre 1">
                    <p class="title">Le Silmarillion</p>
                    <p class="author">JRR Tolkien</p>
                </a>
            </div>
        </div>

        <div class="catalog-footer flex-row jc-flex-end flex-end ai-center">
            <div class="catalog-pagination flex-row ai-center">
                <a href="#">
                    <img src="../assets/fo/img/icons/arrow for left and up.png" alt="">
                </a> <span class="page-info">Page 1 / 10</span>
                <a href="#">
                    <img src="../assets/fo/img/icons/arrow for right and down.png" alt="">
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ================== FOOTER ================== -->

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php');
?>