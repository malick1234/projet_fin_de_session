<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style.css">
  <title>Confirmation</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php
// Inclure la classe PHPMailer
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';

// Créer une instance de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

?>


<?php require_once "navigationPreConnexion.php";
require_once "ConnexionBD.php";
?>

<br>
<div class="jumbotron text-center">
  <h3>Projet 2 : Confirmation d'inscription </h3>

  <?php

  $mail = new PHPMailer(true);
  //$mail->SMTPDebug = 3;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';  // Entrez votre serveur SMTP
  $mail->SMTPAuth = true;
  $mail->Username = 'maximedrogue@gmail.com'; // Entrez votre adresse email
  $mail->Password = 'Maxime123'; // Entrez votre mot de passe
  $mail->SMTPSecure = 'tls'; // TLS ou SSL
  $mail->Port = 587; // Port SMTP
  
  $mail->setFrom('maximedrogue@gmail.com', 'Application');

  $strEmail = $_POST["email"];
  $strEmail2 = $_POST["email2"];
  $strPassword = $_POST["password"];
  $strPassword2 = $_POST["password2"];

  $cBD = mysqli_connect($servername, $username, $password, $dbname);

  if (!$cBD) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
  } else {
    echo "Connexion réussie à la base de données.";
  }

  $query = mysqli_query($cBD, "SELECT Courriel FROM utilisateurs WHERE Courriel='$strEmail'");

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
                    <a href='http://robotcupcake420.alwaysdata.net/Connexion/CourrielConfirme.php?Email=$mailEncrypt' class='nav-item nav-link'>Cliquer ici pour confirmer le courriel</a>
                  </body>
                  </head>
                  ";
            //$mail->send();
  
            date_default_timezone_set("America/New_York");
            $date = date("Y-m-d H:i:s");

            $cBD = mysqli_connect($servername, $username, $password, $dbname);
            $query = mysqli_query($cBD, "INSERT INTO utilisateurs (Courriel, MotDePasse, Creation, NbConnexions, Statut) 
                  VALUES ('$strEmail', '$strPassword', '$date', 0, 9)");
          } else {
            ?>
            <script type="text/javascript">
              alert("Erreur lors de la création du compte\nEmail déjà utilisé");
              window.location.href = 'http://localhost/projet_fin_de_session/Connexion/Inscription.php';
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