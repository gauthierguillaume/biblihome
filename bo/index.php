<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

if(isset($_SESSION['auth'])){

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

    $domaine = "Dashboard";
    $sousDomaine = "Home / Dashboard";

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/analytics.php');

    var_dump($_SESSION['auth']);

    ?>

                
    <?php

    include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');

}else{
    echo "<script language='javascript'>
            document.location.replace('_views/login.php')
            </script>";
}

?>