<!DOCTYPE html>
<html lang="fr">
<?php
session_start();

require_once "navigationPreConnexion.php";
require_once "ConnexionBD.php";
?>

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style.css">
  <title>Connexion</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

  <script type="text/javascript">
    function validerInformation() {
      let boolValide = true;
      let docPassword = document.getElementById('txtUserPassword');
      let docEmail = document.getElementById('txtUserEmail');
      if (docPassword.value != "") {
        var strMfPassword = "sValide";
      }
      else {
        var strMfPassword = "sNonValide";
        boolValide = false;
      }

      if (docEmail.value != "") {
        var strMfEmail = "sValide";
      }
      else {
        var strMfEmail = "sNonValide";
        boolValide = false;
      }

      if (docPassword.classList.length != 1) {
        docPassword.classList.replace(docPassword.classList[1], strMfPassword);
        docEmail.classList.replace(docEmail.classList[1], strMfEmail);
      }
      else {
        docPassword.classList.add(strMfPassword);
        docEmail.classList.add(strMfEmail);
      }

      if (boolValide == true) {
        document.getElementById('formConnexion').submit();
      }
      else {
        alert("Veuillez remplir tout les champs");
      }
    }
  </script>

  <?php
  if (isset($_POST["userEmail"]) && isset($_POST["userPassword"])) {
    $email = strip_tags($_POST["userEmail"]);
    $userPassword = strip_tags($_POST["userPassword"]);
    $cBD = mysqli_connect($servername, $username, $password, $dbname);

    if (!$cBD) {
      die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    } else {
      echo "Connexion réussie à la base de données.";
    }
    $tabUsers = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel='$email' AND MotDePasse='$userPassword'");
    $row = mysqli_fetch_assoc($tabUsers);

    //var_dump($tabUsers);
    if ($row != null) {
      if ($row["Statut"] != 0) {
        $_SESSION["ok"] = $row["NoUtilisateur"];
        // ajouter le compteur de connexion
        $_SESSION["Nom"] = $row["Nom"];
        $_SESSION["Prenom"] = $row["Prenom"];
        $_SESSION["courriel"] = $row["Courriel"];

        $noUtilisateur = $row["NoUtilisateur"];
        $timeZone = new DateTimeZone("America/New_York");
        $date = new DateTime("now", $timeZone);
        $dateString = $date->format("Y-m-d H:i:s");
        var_dump($dateString);
        mysqli_query($cBD, "INSERT INTO connexions VALUES (0, $noUtilisateur, '$dateString', null)");
        $resu = mysqli_query($cBD, "SELECT NoConnexion FROM connexions ORDER BY NoConnexion DESC LIMIT 1");
        $reponse = mysqli_fetch_assoc($resu);
        $_SESSION["NoConnexion"] = $reponse["NoConnexion"];
        $int = $row["NbConnexions"] + 1;
        mysqli_query($cBD, "UPDATE utilisateurs SET Nbconnexions = $int where Courriel='$email'");
        //
        if ($row["Statut"] == 1) {
          header('Location: ../Administrateur/ModuleAdmin.php');
        } else if ($row["Nom"] != null && $row["Prenom"] != null) {
          header('Location: ../Annonces/Annonces.php');
        } else {
          header('Location: ../Annonces/ProfilUtilisateur.php');
        }
      }
    } else {
      echo $row;
      header('../Connexion/Connexion.php');
    }
    mysqli_close($cBD);
  }
  ?>

  <br>
  <div class="container col-md-6 jumbotron">
    <h2 class="text-center">Connexion</h2>
    <form method="POST" action="" id="formConnexion">
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Courriel</label>
          <input type="email" class="form-control" id="txtUserEmail" placeholder="Courriel @" required="required"
            name="userEmail">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Veuillez entrer votre Courriel</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Mot de passe</label>
          <input type="password" class="form-control" id="txtUserPassword" placeholder="Mot de passe"
            required="required" name="userPassword">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Veuillez entrer votre mot de passe</div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-12">
            <a href="RecupMDP.php">Mot de passe oublié</a>
          </div>
        </div>
      </div>
      <input type="button" value="Connexion" class="btn btn-primary col-md-12" id="btnConnexion"
        onclick="validerInformation()">
    </form>
  </div>

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