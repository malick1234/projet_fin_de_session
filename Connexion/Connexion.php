<!DOCTYPE html>
<html lang="fr">
<?php
session_start();
if (isset($_SESSION["ok"]))
  header("Location: ../Annonces/Annonces.php");
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

  <?php require_once "navigationPreConnexion.php" ;
      require_once "ConnexionBD.php";
  ?>

  <?php
  if (isset($_POST["userEmail"]) && isset($_POST["userPassword"])) {
    $email = $_POST["userEmail"];
    $password = $_POST["userPassword"];
    $cBD = mysqli_connect($servername, $username, $password, $dbname);
    $tabUsers = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel='$email' AND MotDePasse='$password'");
    $row = mysqli_fetch_assoc($tabUsers);
    if ($row != null) {
      if ($row["Statut"] != 0) {
        $_SESSION["ok"] = $row["NoUtilisateur"];
        // ajouter le compteur de connexion
        $_SESSION["Nom"] = $row["Nom"];
        $_SESSION["Prenom"] = $row["Prenom"];

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
        if ($row["Nom"] != null && $row["Prenom"] != null) {
          header('Location: ../Annonces/Annonces.php');
        } else if ($row["Statut"] == 1) {
          header('Location: ../Administrateur/ModuleAdmin.php');
        } else {
          header('Location: ../Annonces/ProfilUtilisateur.php');
        }
      }
    } else {
      ?>
      <script type="text/javascript">
        alert("Veuillez vérifier votre courriel et/ou votre mot de passe");
        window.location.href = 'http://localhost/projet_fin_de_session/Connexion/Connexion.php';
      </script>
      <?php
    }
  }
  ?>

  <br>
  <div class="container col-md-6 jumbotron">
    <h2 class="text-center">Connexion</h2>
    <form method="POST" action="Connexion.php" id="formConnexion">
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