<!DOCTYPE html>
<?php

?>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Confirmation</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../style.css">
</head>

<br>
<div class="jumbotron text-center">
  <?php
  $msg = 'Courriel confirmé';
  $msg2 = 'Vous pouvez maintenant vous connecter avec votre courriel';
  if (isset($_GET["Email"])) {
    $mailEncrypt = $_GET["Email"];
    $mailDecrypt = base64_decode($mailEncrypt);
    require_once "ConnexionBD.php";
    $cBD = mysqli_connect($servername, $username, $password, $dbname);
    $query2 = mysqli_query($cBD, "SELECT Statut FROM utilisateurs WHERE Courriel='$mailDecrypt'");
    $row = mysqli_fetch_assoc($query2);
    if (mysqli_num_rows($query2) > 0 && $row["Statut"] == 0) {
      $query = mysqli_query($cBD, "UPDATE utilisateurs SET Statut=9 WHERE Courriel='$mailDecrypt' AND Statut<>9");
    } else {
      $msg = 'ERREUR Déjà confirmé ou Lien modifié';
      $msg2 = '';
    }
  } else {
    $msg = 'ERREUR Lien modifié';
    $msg2 = '';
  }

  ?>
  <h3><?php echo $msg ?></h3>

  <span class="sAuteurApp">
    <?php echo $msg2 ?>
  </span>
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