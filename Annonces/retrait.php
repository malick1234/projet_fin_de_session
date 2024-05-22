<?php
session_start();
if (!isset($_SESSION["ok"])){
  header('Location: ../Connexion/Connexion.php');
}
if (!isset($_GET["annonce"]) || !is_numeric($_GET["annonce"]))
    header("Location: ./AnnoncesUtilisateur.php");
$noAnnonce = (int) $_GET["annonce"];
require $_SERVER['DOCUMENT_ROOT']. "DBAUTH.php";
$cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
$timeZone = new DateTimeZone("America/New_York");
$date = new DateTime("now", $timeZone);
$dateString = $date->format("Y-m-d H:i:s");

mysqli_query($cBD, "UPDATE annonces SET ETAT = 3, MiseAJour = '$dateString' WHERE NOANNONCE = $noAnnonce");
header("Location: ./AnnoncesUtilisateur.php");
?>