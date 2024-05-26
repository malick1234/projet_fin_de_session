<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style.css">
  <title>Confirmation</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php require_once "navigationPreConnexion.php";
require_once "ConnexionBD.php";?>

<?php 
    $cBD = mysqli_connect($servername, $username, $password, $dbname);
if (isset($_GET["nbValidation"]) && isset($_GET["strMail"])) {
  $validation = (int) $_GET["nbValidation"];
  $strEmail = $_GET["strMail"];

  $verifQuery = mysqli_query($cBD,"SELECT * from utilisateurs WHERE Courriel='$strEmail'");
  $row = mysqli_fetch_array($verifQuery);
  echo $row["Statut"];
  
  if($row["Statut"] != 0){
    header("Location:validationEchoue.php");
  }
  else{
    $statut = 9;
    $query = mysqli_query($cBD, "UPDATE utilisateurs SET Statut='$statut' WHERE Courriel='$strEmail'");
    ?>
<div class="container-fluid p-5 bg-primary text-white text-center">
  <h1>Confirmation d'inscription</h1>
  <p>Site d'annonce Abel&Malick</p> 
</div>
<div class="bg-warning text-center">
      <h3>Contact</h3>
    </div>
    <div class="text-center">
      <p>Merci pour votre patience. Vous êtes maintenant capable de vous connecter.</p>
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
    <?php 
  }
}
else{
  header("Location:validationEchoue.php");

}
?>
