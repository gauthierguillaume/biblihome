<?php $user = $_SESSION['user'] ?? null; ?>

<section class="hero flex-row jc-center">
    <div class="banner-text flex-col jc-center ac-center ai-center ta-center">
        <h1 class="underline-hug">UNE BIBLIOTHÈQUE,<br>
            ACCESSIBLE À TOUS</h1>
        <p>Que vous soyez étudiant pressé, retraité passionné ou en situation de mobilité réduite, nos livres viennent à vous.</p>
        <h2 class="underline-hug">Prêts à libérer votre imagination ?</h2>
        <?php if (!$user): ?>
            <p>Identifiez-vous pour commencer l’aventure.</p>
            <a id="identify-button" class="flex-row" href="/views/login.php">
                <img src="../assets/fo/img/icons/login.png" alt="S'identifier"> S'identifier
            </a>
        <?php else: ?>
            <p>Accédez dès maintenant à l’ensemble de la bibliothèque.</p>
            <a id="identify-button" class="flex-row" href="/views/catalog.php">
                <img src="../assets/fo/img/icons/search dm.png" alt="Catalogue"> Voir le catalogue
            </a>
        <?php endif; ?>
    </div>
</section>

</header>