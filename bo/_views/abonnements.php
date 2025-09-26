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

$select_abo = $db->prepare('SELECT * FROM abonnements');
$select_abo->execute();

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
<div class="flexRow formContent">
  <div class="flexCol formWidth">
    <h3>Entrée un abonnement</h3>
    <form method="POST" name="add_abo" class="flexCol formPadding" style="width:20%; gap: 5px;">
      <div class="flexCol formMargin formGap">
        <label for="abo_name" class="labelWidth">Titre de l'abonnement</label>
        <input type="text" name="abo_name" placeholder="Nom de l'abonnement" required class="inputWidth">
      </div>
      <div class="flexCol formMargin formGap">
        <label for="abo_accroche" class="labelWidth">Phrase d'accroche</label>
        <input type="text" placeholder="Phrase d'accroche" name="abo_accroche" required class="inputWidth">
      </div>
      <div class="flexCol formMargin formGap">
        <label for="abo_desc" class="labelWidth">Description de l'abonnement</label>
        <input type="text" placeholder="Description de l'abonnement" name="abo_desc" required class="inputWidth">
      </div>
      <div class="flexCol formMargin formGap">
        <label for="abo_time" class="labelWidth">Durée de l'abonnement</label>
        <input type="number" placeholder="Durée de l'abonnement" name="abo_time" min="1" required class="inputWidth">
      </div>
      <div class="flexCol formMargin formGap">
        <label for="abo_perks" class="labelWidth">Avantages de l'abonnement</label>
        <input type="text" name="abo_perks" placeholder="Avantage de l'abonnement" required class="inputWidth">
      </div>
      <div class="flexCol formMargin formGap">
        <label for="abo_price" class="labelWidth">Prix de l'abonnement</label>
        <input type="number" step="0.01" placeholder="Prix de l'abonnement" name="abo_price" required class="inputWidth">
      </div>
      <div>
        <input type="submit" value="Ajouter l'abonnement" name="add_abo" class="subBtn">
      </div>
    </form>
  </div>


  <!-- Modifier un abonnement -->
  <div class="flexCol formWidth">
    <h3>Modifier un abonnement</h3>

    <form method="POST" name="modifAbo" class="flexCol formPadding" style="width:20%; gap: 5px;">
      <div class="flexCol formMargin formGap">
        <label for="abo_select_update" class="labelWidth">Sélectionner un abonnement</label>
        <select name="abo_select_update" class="inputWidth">
        <?php while($abo_info = $select_abo_info->fetch(PDO::FETCH_OBJ)){
        ?>
            <option value="<?php echo $abo_info->id_abonnement;?>"><?php echo $abo_info->abonnement_nom;?></option>
            <?php
        }
        ?>
        </select>
      </div>

      <div class="flexCol formMargin formGap">
        <label for="abo_name_modify" class="labelWidth">Modifier le titre de l'abonnement</label>
        <input type="text" name="abo_name_modify" placeholder="Nom de l'abonnement" class="inputWidth">
      </div>

      <div class="flexCol formMargin formGap">
        <label for="abo_accroche_modify" class="labelWidth">Modifier la phrase d'accroche</label>
        <input type="text" name="abo_accroche_modify" placeholder="Phrase d'accroche" class="inputWidth">
      </div>

      <div class="flexCol formMargin formGap">
        <label for="abo_desc_modify" class="labelWidth">Modifier la description de l'abonnement</label>
        <input type="text" name="abo_desc_modify" placeholder="Modifier la description" class="inputWidth">
      </div>

      <div class="flexCol formMargin formGap">
        <label for="abo_time_modify" class="labelWidth">Modifier la durée de l'abonnement</label>
        <input type="text" name="abo_time_modify" placeholder="Modifier la durée" class="inputWidth">
      </div>

      <div class="flexCol formMargin formGap">
        <label for="abo_perks_modify" class="labelWidth">Modifier les avantages de l'abonnement</label>
        <input type="text" name="abo_perks_modify" placeholder="Modifier les avantages" class="inputWidth">
      </div>

      <div class="flexCol formMargin formGap">
        <label for="abo_price_modify" class="labelWidth">Modifier le prix de l'abonnement</label>
        <input type="number" placeholder="Prix de l'abonnement" name="abo_price_modify" class="inputWidth">
      </div>

      <div>
        <input type="submit" value="Modifier l'abonnement" name="modifAbo" class="subBtn">
      </div>

    </form>
  </div>
  <!-- Supprimer un abonnement -->
  <div class="flexCol formWidth">
    <h3>Supprimer un abonnement</h3>

    <form method="POST" class="flexCol formPadding" name="delete_abo" style="width:20%; gap: 5px;">
      
      <div class="flexCol formMargin formGap">
        <label for="abo_select" class="labelWidth">Sélectionner un abonnement</label>
        <select name="abo_select" class="inputWidth">
          <?php while($abo_infos = $select_abo_delete->fetch(PDO::FETCH_OBJ)){
        ?>
          <option value="<?php echo $abo_infos->id_abonnement;?>"><?php echo $abo_infos->abonnement_nom;?></option>
          <?php
        }
        ?>
        </select>
      </div>
      <div>
        <input type="submit" value="Supprimer l'abonnement" name="delete_abo" class="subBtn">
      </div>
    </form>
  </div>
</div>
<!-- Tableaux des clients/employés -->

<?php

?>

  <table width="100%" style="align-items:center;">
    <thead>
      <tr>
        <th>ID</th>
        <th><span class="las la-sort pLeft"></span> INFOS</th>
        <th><span class="las la-sort pLeft"></span> TITRES ET PRIX</th>
        <th><span class="las la-sort pLeft"></span> PHRASE D'ACCROCHE</th>
        <th><span class="las la-sort pLeft"></span> DESCRIPTION</th>
        <th><span class="las la-sort pLeft"></span> AVANTAGES</th>
        <th>ACTIONS</th>
      </tr>
    </thead>
    <tbody>
  <?php
    while($abo = $select_abo->fetch(PDO::FETCH_OBJ)){
  ?>
      <tr>
        <td class="pLeftTd maxWidth">
          <?php echo $abo->id_abonnement;?>
        </td>
        <td class="pLeftTd maxWidth">
          <div class="client">
            <div class="client-info">
              <h4><?php echo $abo->abonnement_nom;?></h4>
              <small><?php echo $abo->abonnement_prix;?>€</small>
            </div>
          </div>
        </td>
        <td class="pLeftTd maxWidth"><?php echo $abo->abonnement_duree;?></td>
        <td class="pLeftTd maxWidth"><?php echo $abo->abonnement_blurb;?></td>
        <td class="pLeftTd maxWidth"><?php echo $abo->abonnement_desc;?></td>
        <td class="pLeftTd maxWidth"><?php echo $abo->abonnement_perks;?></td>
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