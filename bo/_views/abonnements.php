<?php
include($_SERVER['DOCUMENT_ROOT'].'/host.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/sidebar.php');

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/header.php');

$domaine = "Dashboard";
$sousDomaine = "Auteurs / Liste";

include($_SERVER['DOCUMENT_ROOT'].'/bo/_blocks/ariane.php');

// Récuperer les infos clients/utilisateurs

$selectUser = $db->prepare('SELECT * FROM users NATURAL JOIN civilites
');
$selectUser->execute();

// $select_user_infos = $db->prepare('SELECT * FROM users');
// $select_user_infos->execute();

if($_POST){
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

?>

<!-- Fourmulaire d'entrée des abonnements -->

<h3>Entrée un abonnement</h3>

<form method="POST" name="add_abo" class="flexCol" style="width:30%; gap: 5px;">
  <label for="abo_name">Titre de l'abonnement</label>
  <input type="text" name="abo_name" placeholder="Nom de l'abonnement" required>

  <label for="abo_text">Phrase d'accroche</label>
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
    <input type="submit" value="Ajouter l'abonnement">
  </div>
</form>

<!-- Modifier un abonnement -->

<h3>Modifier un abonnement</h3>

<form method="POST" class="flexCol" style="width:40%; gap: 5px;">
  <label for="abo_select_update">Sélectionner un abonnement</label>
  <select name="abo_select_update">
    <option value="Abo 1">Abo 1</option>
    <option value="Abo 2">Abo 2</option>
    <option value="Abo 1">Abo 3</option>
  </select>
  <label for="abo_name">Modifier le titre de l'abonnement</label>
  <input type="text" name="abo_name" placeholder="Nom de l'abonnement">
  <label for="abo_text">Modifier la phrase d'accroche</label>
  <input type="text" placeholder="Phrase d'accroche">
  <label for="abo_price">Modifier le prix de l'abonnement</label>
  <input type="number" placeholder="Prix de l'abonnement" name="abo_price">
  <div>
    <input type="submit" value="Valider">
  </div>

</form>

<!-- Supprimer un abonnement -->

<h3>Supprimer un abonnement</h3>

<form method="POST" class="flexCol" style="width:30%; gap: 5px;">
  <label for="abo_select">Sélectionner un abonnement</label>
  <select name="abo_select">
    <option value="Abo 1">Abo 1</option>
    <option value="Abo 2">Abo 2</option>
    <option value="Abo 1">Abo 3</option>
  </select>
  <div>
    <input type="submit" value="Supprimer l'abonnement">
  </div>
</form>

<!-- Tableaux des clients/employés -->

<?php

?>

  <table width="100%" style="text-align: center;">
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