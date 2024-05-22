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

$row = mysqli_fetch_row(mysqli_query($cBD, "SELECT ETAT FROM annonces WHERE NOANNONCE = $noAnnonce "));
$etat = 0;
if ($row[0] == 1)
    $etat = 2;
else if ($row[0] == 2)
    $etat = 1;

mysqli_query($cBD, "UPDATE annonces SET ETAT = $etat, MiseAJour = '$dateString' WHERE NOANNONCE = $noAnnonce");
header("Location: ./AnnoncesUtilisateur.php");
?>