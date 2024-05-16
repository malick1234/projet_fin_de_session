<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Profil utilisateur</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<script type="text/javascript">
    function validerInformation() {
      let boolValide = true;
      let docNom = document.getElementById('txtNom');
      let docPrenom = document.getElementById('txtPrenom');

      if (docNom.value != "") {
        var strNom = "sValide";
      } else {
        var strNom = "sNonValide";
        boolValide = false;
      }

      if (docPrenom.value != "")  {
        var strPrenom = "sValide";
      }else {
        var strPrenom = "sNonValide";
        boolValide = false;
      }

      if (docNom.classList.length != 1) {
        docNom.classList.replace(docNom.classList[1], strNom);
        docPrenom.classList.replace(docPrenom.classList[1], strPrenom);
      } else {
        docNom.classList.add(strNom);
        docPrenom.classList.add(strPrenom);
      }

      if (boolValide == true) {
        document.getElementById('formProfilUtilisateur').submit()
        alert("Validation des données!");
      } else {
        if (docNom.value == "" || docPrenom.value == "") {
          alert("Veuillez remplir au moins le nom et le prénom!");
      }
    }
  }
</script>
<?php require_once "navigationAnnonce.php";
require_once "ConnexionBD.php";
session_start();

  $cBD = mysqli_connect($servername, $username, $password, $dbname);
  $strEmail = $_SESSION["courriel"];


if (isset($_POST)) {
    if (
      isset($_POST['txtNom']) && !empty($_POST['txtNom'])
      && isset($_POST['txtPrenom']) && !empty($_POST['txtPrenom'])
    ) {
      $nom = strip_tags($_POST['txtNom']);
      $prenom = strip_tags($_POST['txtPrenom']);
      $statut = strip_tags($_POST['txtStatut']);
      $numEmploye = strip_tags($_POST['txtNumEmploye']);
      $telM = strip_tags($_POST['txtTelMaison']);
      $telT = strip_tags($_POST['txtTelTravail']);
      $telC = strip_tags($_POST['txtTelCellulaire']);
      $autresInfos = strip_tags($_POST['txtAutresInfos']);
            
      date_default_timezone_set("America/New_York");
      $date = date("Y-m-d H:i:s");

            $query = mysqli_query($cBD, "UPDATE utilisateurs SET Statut = '$statut',
             NoEmpl = '$numEmploye', Nom = '$nom', Prenom = '$prenom', NoTelMaison = '$telM',
              NoTelTravail = '$telT', NoTelCellulaire = '$telC', Modification = '$date', AutresInfos = '$autresInfos'
              WHERE Courriel='$strEmail'");
            header('Location: Annonces.php');
          } else {
            $query = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel='$strEmail'");
            $row = mysqli_fetch_assoc($query);
          }
        }
        else {
          $query = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel='$strEmail'");
          $row = mysqli_fetch_assoc($query);
        }
  ?>
  <br>
  <div class="container col-md-10 jumbotron">
    <h2 class="text-center">Profil utilisateur</h2><br>
    <form id="formProfilUtilisateur" method="post" action="">
      <div class="form-row">
        <div class="form-group col-md-12">
        <h2 class="text-center"><?= $strEmail?></h2>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Nom</label>
          <input type="text" class="form-control" id="txtNom" name="txtNom" required="required" value="<?= $row['Nom']?>">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Nom invalide</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Prénom</label>
          <input type="text" class="form-control" id="txtPrenom" name="txtPrenom" value="<?= $row['Prenom']?>" required="required">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Prénom invalide</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Statut</label>
          <input type="text" class="form-control" id="txtStatut" name="txtStatut" required="required">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Prénom invalide</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Numéro employé</label>
          <input type="text" class="form-control" id="txtNumEmploye" name="txtNumEmploye">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Téléphone maison</label>
          <input type="text" class="form-control" id="txtTelMaison" name="txtTelMaison">
        </div>
      </div>
      <div class="form-row">
      <div class="form-group col-md-12">
          <label>Téléphone travail</label>
          <input type="text" class="form-control" id="txtTelTravail" name="txtTelTravail">
        </div>
      </div>
      <div class="form-row">
      <div class="form-group col-md-12">
          <label>Téléphone maison</label>
          <input type="text" class="form-control" id="txtTelCellulaire" name="txtTelCellulaire">
        </div>
      </div>
      <div class="form-row">
      <div class="form-group col-md-12">
          <label>Autres infos</label>
          <input type="text" class="form-control" id="txtAutresInfos" name="txtAutresInfos">
        </div>
      </div>
      <input type="button" value="Valider" class="btn btn-primary col-md-12" id="btnValider"
        onclick="validerInformation()">
    </form>
  </div>

  <div class="container-fluid">

  </div>
  <footer>
    <div class="text-center">
      <p>
        <img src="../logoCGG.jpg" alt="" srcset="">
        © Département d'informatique G.-G.
      </p>
    </div>
  </footer>
</body>
</html>
