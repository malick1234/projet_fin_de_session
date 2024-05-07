<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style.css">
  <title>Confirmation</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer-6.8.0/PHPMailer-6.8.0/src/Exception.php';
  require 'PHPMailer-6.8.0/PHPMailer-6.8.0/src/PHPMailer.php';
  require 'PHPMailer-6.8.0/PHPMailer-6.8.0/src/SMTP.php';

  ?>


  <?php require_once "navigationPreConnexion.php" ?>

  <br>
  <div class="jumbotron text-center">
    <h3>Projet 2 : Confirmation d'inscription </h3>

    <?php
    require $_SERVER['DOCUMENT_ROOT'] . "ConnexionBD.php";
    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'robotcupcake420@gmail.com';
    $mail->Password = 'wridpjxrbiywbiqn';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->setFrom('robotcupcake420@gmail.com', 'robotCupcake');

    $strEmail = $_POST["email"];
    $strEmail2 = $_POST["email2"];
    $strPassword = $_POST["password"];
    $strPassword2 = $_POST["password2"];

    $cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
    $query = mysqli_query($cBD, "SELECT Courriel FROM utilisateurs WHERE Courriel='$strEmail'");
    $racine = $_SERVER['DOCUMENT_ROOT'];

    if ($strPassword != "" && $strEmail != "" && $strPassword2 != "" && $strEmail2 != "") {
      if ($strPassword == $strPassword2) {
        if ($strEmail == $strEmail2) {
          if (filter_var($strEmail, FILTER_VALIDATE_EMAIL)) {
            if (mysqli_num_rows($query) == 0) {
              $mailEncrypt = base64_encode($strEmail);
              $mail->addAddress($strEmail, 'recipient');
              $mail->isHTML(true);
              $mail->CharSet = 'UTF-8';
              $mail->Subject = 'Confirmation du Email';
              $mail->Body = "
                  <html>
                  <head>
                    <title>Confirmation du courriel</title>
                  </head>
                  <body>
                    <a href=http://robotcupcake420.alwaysdata.net/Connexion/CourrielConfirme.php?Email=$mailEncrypt' class='nav-item nav-link'>Cliquer ici pour confirmer le courriel</a>
                  </body>
                  </head>
                  ";
              $mail->send();

              date_default_timezone_set("America/New_York");
              $date = date("Y-m-d H:i:s");

              $cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
              $query = mysqli_query($cBD, "INSERT INTO utilisateurs (NoUtilisateur, Courriel, MotDePasse, Creation, NbConnexions, Statut) 
                  VALUES (0, '$strEmail', '$strPassword', '$date', 0, 0)");
            } else {
              ?>
              <script type="text/javascript">
                alert("Erreur lors de la création du compte\nEmail déjà utilisé");
                window.location.href = 'http://localhost/ProjetFinal/Connexion/Inscription.php';
              </script>
              <?php
            }
          }
        }
      }
    }
    ?>

    <span class="sAuteurApp">

    </span>
  </div>

  <div class="row">
    <div class="col"></div>
    <p class="col bg-warning text-center"><b class="h5">Inscription</b></p>
    <div class="col"></div>
    <div class="w-100"></div>
    <div class="col"></div>
    <p class="col mb-0">Merci pour votre inscription. Un Email de confirmation a été envoyer à l'adresse:
      <?php echo $strEmail ?>
    </p>
    <div class="col"></div>

    <!-- Mettre un bouton pour retourner à la connexion ici-->
  </div>
  <br>


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