<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');
include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Utilisateurs";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');
?>

<div class="records table-responsive">
    <div class="record-header">
        <div class="add">
            <h3>Gestion des utilisateurs</h3>
            <p style="opacity:.7;">Cette page peut rediriger vers Admin, ou contenir une liste dédiée.</p>
            <a href="admin.php?zone=admin" class="btn">Aller à Admin</a>
        </div>
    </div>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');
?>
