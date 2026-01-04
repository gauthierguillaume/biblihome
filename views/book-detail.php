<?php
include($_SERVER['DOCUMENT_ROOT'] . '/host.php');

// ================== HEADER ==================

include($_SERVER['DOCUMENT_ROOT'] . '/blocks/nav.php');
?>

<!-- ================== MAIN ================== -->

<section class="book-detail flex-row wrap">
    <div class="cover flex-col ai-center">
        <img src="../assets/fo/img/books/les-deux-tours.jpg" alt="">
        <div class="book-btn flex-row jc-center ai-center">
            <button class="bd-btn">Emprunter</button>
            <button class="bd-btn">Favoris</button>
        </div>
    </div>

    <div class="content flex-col">
        <h2 class="title">Le Seigneur des Anneaux : Les Deux Tours</h2>
        <h3>J.R.R. Tolkien</h3>
        <p class="description">La Fraternité de l'Anneau poursuit son voyage vers la Montagne du Feu où l'Anneau Unique fut forgé, et où Frodo a pour mission de le détruire.<br><br>
            Cette quête terrible est parsemée d'embûches : Gandalf a disparu dans les Mines de la Moria et Boromir a succombé au pouvoir de l'Anneau. Frodo et Sam se sont échappés afin de poursuivre leur voyage jusqu'au coeur du Mordor.<br><br>
            À présent, ils cheminent seuls dans la désolation qui entoure le pays de Sauron - mais c'est sans compter la mystérieuse silhouette qui les suit partout où ils vont. Chef-d'oeuvre de la fantasy, découverte d'un monde imaginaire, de sa géographie, de son histoire et de ses langues, mais aussi réflexion sur le pouvoir et la mort, Le Seigneur des Anneaux est sans équivalent par sa puissance d'évocation, son souffle et son ampleur.</p>
        <div class="book-tags flex-row ai-center wrap">

        <?php
        $selectGenres = $db->prepare(
            'SELECT * 
            FROM livres_genres 
            NATURAL JOIN genres 
            WHERE id_livre = ?');
        $selectGenres->execute([3]);
        $genres = $selectGenres->fetchAll();

        foreach ($genres as $genre) {
            echo '<button class="bd-btn">' . $genre['genre_tag'] . '</button>';
        }
        ?>
            <button class="bd-btn">tag 1</button>

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
                <p>Le Seigneur des Anneaux</p>
                <p>Christian Bourgois</p>
                <p>Francais</p>
                <p>9782267044706</p>
                <p>11 Novembre 1954</p>

            </div>
        </div>


    </div>

    <div class="author flex-col">
        <a href="author-detail.php" class="author-header flex-row ai-center">
            <img src="../assets/fo/img/books/tolkien.jpeg" alt="">
            <h2 class="">J.R.R. Tolkien</h2>
        </a>
        <div class="content-details flex-row">
            <div class="cd-title flex-col">
                <p>Nationalité</p>
                <p>Née le</p>
                <p>Décédé le</p>
            </div>
            <div class="cd-content flex-col">
                <p>Britannique</p>
                <p>3 Janvier 1892</p>
                <p>2 Septembre 1973</p>
            </div>
        </div>
        <p>Né en 1892 à Bloemfontein (Afrique du Sud), John Ronald Reuel Tolkien passe son enfance, après la mort de son père en 1896, au village de Sarehole près de Birmingham (Agleterre), ville dont sa famille est originaire.<br><br>
            Diplômé d'Oxford en 1919 (après avoir servi dans les Lancashire), il travaille au célèbre Dictionnaire d'Oxford, obtient ensuite un poste de maître assistant à Leeds, puis une chaire de langues anciennes (anglo-saxon) à Oxford de 1925 à 1945 - et de langue et littérature anglaises de 1945 à sa retraite en 1959.<br><br>
            Spécialiste de philologie faisant autorité dans le monde entier, J.R.R. Tolkien a écrit en 1936 Le Hobbit, considéré comme un classique de la littérature enfantine; en 1938-1939: un essai sur les contes de fées. Paru en 1949, Farmer Giles of Ham a séduit également adultes et enfants. J.R.R. Tolkien a travaillé quatorze ans au cycle intitulé Les Seigneur des Anneaux composé de: La Communauté de l'anneau (1954), Les Deux tours (1954), Le retour du roi (1955) -œuvre magistrale qui s'est imposée dans tous les pays.<br><br>
            Dans Les Aventures de Tom Bombadil (1962), J.R.R. Tolkien déploie son talent pour les assonances ingénieuses. En 1968, il enregistre sur disque les Poèmes et Chansons de la Terre du Milieu, tiré des Aventures de Tom Bombadil et du Seigneur des Anneaux. Le conte de Smith of Wootton Major a paru en 1967.<br><br>
            John Ronald Reuel Tolkien est mort en 1973.</p>
    </div>

</section>

<!-- ================== FOOTER ================== -->

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/blocks/footer.php');
?>