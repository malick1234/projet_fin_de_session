<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["ok"])){
  header('Location: ../Connexion/Connexion.php');
}
    $noAnnonce = 0;
    if (isset($_GET["annonce"]))
        $noAnnonce = $_GET["annonce"];
    require $_SERVER['DOCUMENT_ROOT']. "DBAUTH.php";
    $cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
    $row = mysqli_fetch_row(mysqli_query($cBD, "SELECT * FROM annonces WHERE NOANNONCE = $noAnnonce"));
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../style.css">
  <title>Annonces</title>
  </head>
  <body>
  <?php require_once "navigation.php"?>
  <h1 class="text-center">Description complète de l'annonce #<?php echo $noAnnonce ?></h1>
  <div class="container-fluid"> 
  <br><br>
    <div class="grid row justify-content-center">
        <div class="col-8 text-center ">
            <img src="<?php echo $row[7]?>" alt="not loaded" style="width: 1000px; height: 600px">
            <h2> <br>
            <?php echo $row[5]?>
            </h2> <br> <br>
            <h2>
            Créer le <?php echo $row[2]?> <br> dernière modification : <?php echo $row[8]?>
            </h2>
        </div>
    </div>
  </div>
  <br><br><br><br><br>
  <footer>
    <div class="text-center">
        <p>
            <img src="../logoCGG.jpg" alt="" srcset="">
            © Département d'informatique G.-G.
        </p>
    </div>
    </footer>
  </body>