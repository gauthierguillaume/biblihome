<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliHome</title>
    <meta name="description" content="BibliHome - Une bibliothèque accessible à tous">
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/assets/fo/css/base.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <header>

        <?php
        $user = $_SESSION['user'] ?? null;

        function bh_initials(string $first, string $last): string
        {
            $i1 = mb_strtoupper(mb_substr($first, 0, 1));
            $i2 = mb_strtoupper(mb_substr($last, 0, 1));
            return $i1 . $i2;
        }
        ?>

        <!-- ------------- NAVBAR ------------- -->
        <nav class="flex-row jc-space-between ai-center wrap desktop">
            <div class="left-side flex-row jc-space-between ai-center">
                <a href="/index.php"><img src="../assets/fo/img/logos/text circle fill.png" alt="BibliHome"></a>
                <!-- ------------- SEARCHBAR ------------- -->
                <!-- From Uiverse.io by satyamchaudharydev -->
                <form class="searchbar">
                    <button>
                        <svg width="17" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="search">
                            <path d="M7.667 12.667A5.333 5.333 0 107.667 2a5.333 5.333 0 000 10.667zM14.334 14l-2.9-2.9" stroke="currentColor" stroke-width="1.333" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <input class="input" placeholder="Le Seigneur des Anneaux..." required="" type="text">
                    <button class="reset" type="reset">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="right-side flex-row jc-space-between ai-center">
                <a id="accueil" href="/index.php">Accueil</a>
                <a id="catalogue" href="/views/catalog.php">Catalogue</a>
                <?php if (!$user): ?>
                    <a id="identify-button" class="flex-row" href="/views/login.php">
                        <img src="../assets/fo/img/icons/login.png" alt="S'identifier"> S'identifier
                    </a>
                <?php
                else:
                    $first = htmlspecialchars($user['first_name']);
                    $last  = htmlspecialchars($user['last_name']);
                    $name  = $first . ' ' . $last;
                    $avatar = $user['avatar'] ? htmlspecialchars($user['avatar']) : '';
                    $initials = bh_initials($first, $last);
                ?>
                    <div class="user-profile">
                        <?php if ($avatar): ?>
                            <img src="<?php echo $avatar; ?>" alt="<?php echo $name; ?>" class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar user-initials"><?php echo $initials; ?></div>
                        <?php endif; ?>

                        <div class="user-meta">
                            <span class="user-name"><?php echo $name; ?></span>
                            <a href="/views/logout.php" class="logout-link">Déconnexion</a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </nav>

    </header>

    <main>