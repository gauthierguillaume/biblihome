<?php

include($_SERVER['DOCUMENT_ROOT'].'/host.php');

$select_abo_delete = $db->prepare('SELECT * FROM abonnements');
$select_abo_delete->execute();

if(isset($_POST['delete_abo'])) {
  $id_delete = $_POST['abo_select'] ?? null;

  if($id_delete){
    $delete = $db->prepare('DELETE FROM abonnements WHERE id_abonnement = :id');

    $delete->execute([':id' => $id_delete]);
  }

  header("Location: " . $_SERVER['PHP_SELF'] . "?zone=abonnements");
  exit;
}
?>

<?php

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Auteurs / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');

// Récuperer les infos clients/utilisateurs

$selectUser = $db->prepare('SELECT * FROM users NATURAL JOIN civilites
');
$selectUser->execute();

if(isset($_POST['add_abo'])){
  // Recupère les données des inputs
  $title = htmlspecialchars(trim($_POST['abo_name']));
  $text_abo = htmlspecialchars(trim($_POST['abo_accroche']));
  $price = htmlspecialchars(trim($_POST['abo_price']));
  $abo_desc = htmlspecialchars(trim($_POST['abo_desc']));
  $abo_time = htmlspecialchars(trim($_POST['abo_time']));
  $abo_perks = htmlspecialchars(trim($_POST['abo_perks']));

  // Vérification champ non vide 

  if(!empty($title) && !empty($text_abo) && $price > 0){
    $add_abo = $db->prepare('INSERT INTO abonnements (abonnement_nom,abonnement_blurb,abonnement_prix, abonnement_desc, abonnement_duree, abonnement_perks) VALUES (:abonnement_nom,:abonnement_blurb,:abonnement_prix, :abonnement_desc, :abonnement_duree, :abonnement_perks)');
    $add_abo->execute([
    ':abonnement_nom' => $title,
    'abonnement_blurb' => $text_abo,
    ':abonnement_prix' => $price,
    ':abonnement_desc' => $abo_desc,
    ':abonnement_duree' => $abo_time,
    ':abonnement_perks' => $abo_perks ]);
  } 
}

// modifier un abonnement

// recuperer l'id de l'abonnement dans le select

$select_abo_info = $db->prepare('SELECT * FROM abonnements');
$select_abo_info->execute();

if(isset($_POST['modifAbo'])){
  // Recupère les données des inputs
  $abo_title_modify = htmlspecialchars(trim($_POST['abo_name_modify']));
  $abo_accroche_modify = htmlspecialchars(trim($_POST['abo_accroche_modify']));
  $abo_price_modify = htmlspecialchars(trim($_POST['abo_price_modify']));
  $abo_desc_modify = htmlspecialchars(trim($_POST['abo_desc_modify']));
  $abo_time_modify = htmlspecialchars(trim($_POST['abo_time_modify']));
  $abo_perks_modify = htmlspecialchars(trim($_POST['abo_perks_modify']));

  // Verification de champ non vide

  if(!empty($abo_title_modify) && !empty($abo_accroche_modify) && !empty($abo_price_modify) && !empty($abo_desc_modify) && !empty($abo_time_modify) && !empty($abo_perks_modify)) {
    $modify_abo = $db->prepare('UPDATE abonnements SET 
    abonnement_nom = :abonnement_nom,
    abonnement_blurb = :abonnement_blurb,
    abonnement_prix = :abonnement_prix,
    abonnement_desc = :abonnement_desc,
    abonnement_duree = :abonnement_duree,
    abonnement_perks = :abonnement_perks
    WHERE id_abonnement = :id_abonnement');

    $modify_abo->execute([
    ':abonnement_nom' => $abo_title_modify,
    ':abonnement_blurb' => $abo_accroche_modify,
    ':abonnement_prix' => $abo_price_modify,
    ':abonnement_desc' => $abo_desc_modify,
    ':abonnement_duree' => $abo_time_modify,
    ':abonnement_perks' => $abo_perks_modify,
    ':id_abonnement' => $_POST['abo_select_update']]);
  }
}

// Supprimer un abonnement
// voir en haut du document

?>

<!-- Fourmulaire d'entrée des abonnements -->

<h3>Entrée un abonnement</h3>

<form method="POST" name="add_abo" class="flexCol" style="width:20%; gap: 5px;">
  <label for="abo_name">Titre de l'abonnement</label>
  <input type="text" name="abo_name" placeholder="Nom de l'abonnement" required>

  <label for="abo_accroche">Phrase d'accroche</label>
  <input type="text" placeholder="Phrase d'accroche" name="abo_accroche" required>

  <label for="abo_desc">Description de l'abonnement</label>
  <input type="text" placeholder="Description de l'abonnement" name="abo_desc" required>

  <label for="abo_time">Durée de l'abonnement</label>
  <input type="number" placeholder="Durée de l'abonnement" name="abo_time" min="1" required>

  <label for="abo_perks">Avantages de l'abonnement</label>
  <input type="text" name="abo_perks" placeholder="Avantage de l'abonnement" required>

  <label for="abo_price">Prix de l'abonnement</label>
  <input type="number" step="0.01" placeholder="Prix de l'abonnement" name="abo_price" required>

  <div>
    <input type="submit" value="Ajouter l'abonnement" name="add_abo">
  </div>
</form>

<!-- Modifier un abonnement -->

<h3>Modifier un abonnement</h3>

<form method="POST" name="modifAbo" class="flexCol" style="width:20%; gap: 5px;">
  <label for="abo_select_update">Sélectionner un abonnement</label>
  <select name="abo_select_update">
  <?php while($abo_info = $select_abo_info->fetch(PDO::FETCH_OBJ)){
  ?>
      <option value="<?php echo $abo_info->id_abonnement;?>"><?php echo $abo_info->abonnement_nom;?></option>
      <?php
  }
  ?>
  </select>

  <label for="abo_name_modify">Modifier le titre de l'abonnement</label>
  <input type="text" name="abo_name_modify" placeholder="Nom de l'abonnement">

  <label for="abo_accroche_modify">Modifier la phrase d'accroche</label>
  <input type="text" name="abo_accroche_modify" placeholder="Phrase d'accroche">

  <label for="abo_desc_modify">Modifier la description de l'abonnement</label>
  <input type="text" name="abo_desc_modify" placeholder="Modifier la description">

  <label for="abo_time_modify">Modifier la durée de l'abonnement</label>
  <input type="text" name="abo_time_modify" placeholder="Modifier la durée">

  <label for="abo_perks_modify">Modifier les avantages de l'abonnement</label>
  <input type="text" name="abo_perks_modify" placeholder="Modifier les avantages">

  <label for="abo_price_modify">Modifier le prix de l'abonnement</label>
  <input type="number" placeholder="Prix de l'abonnement" name="abo_price_modify">
  <div>
    <input type="submit" value="Modifier l'abonnement" name="modifAbo">
  </div>

</form>

<!-- Supprimer un abonnement -->

<h3>Supprimer un abonnement</h3>

<form method="POST" class="flexCol" name="delete_abo" style="width:20%; gap: 5px;">
  <label for="abo_select">Sélectionner un abonnement</label>
  <select name="abo_select">
    <?php while($abo_infos = $select_abo_delete->fetch(PDO::FETCH_OBJ)){
  ?>
    <option value="<?php echo $abo_infos->id_abonnement;?>"><?php echo $abo_infos->abonnement_nom;?></option>
    <?php
  }
  ?>
  </select>
  <div>
    <input type="submit" value="Supprimer l'abonnement" name="delete_abo">
  </div>
</form>

<!-- Tableaux des clients/employés -->

<?php

?>

  <table width="100%" style="align-items:center;">
    <thead>
      <tr>
        <th>ID</th>
        <th><span class="las la-sort"></span> INFOS</th>
        <th><span class="las la-sort"></span> RÔLES</th>
        <th><span class="las la-sort"></span> DATE D'AJOUT</th>
        <th><span class="las la-sort"></span> CIVILITÉ</th>
        <th><span class="las la-sort"></span> ABONNEMENTS</th>
      </tr>
    </thead>
    <tbody>
  <?php
    while($users = $selectUser->fetch(PDO::FETCH_OBJ)){
  ?>
      <tr>
        <td>
          <?php echo $users->id_user;?>
        </td>
        <td>
          <div class="client">
            <div class="client-img bg-img" style="background-image: url(img/3.jpeg)"></div>
            <div class="client-info">
              <h4><?php echo $users->user_nom;?> <?php echo $users->user_prenom;?></h4>
              <small><?php echo $users->user_mail;?></small>
            </div>
          </div>
        </td>
        <td><?php echo $users->id_role;?></td>
        <td><?php echo $users->user_date_creation;?></td>
        <td><?php echo $users->civilite_nom?></td>
        <td>
          <div class="actions">
            <span class="lab la-telegram-plane"></span>
            <span class="las la-eye"></span>
            <span class="las la-ellipsis-v"></span>
          </div>
        </td>
      </tr>
    </tbody>
  <?php
  }
  ?>

<?php
include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/footer.php');
?>