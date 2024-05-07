<?php
session_start();
// ajouter la date de déconnexion
if (isset($_SESSION["NoConnexion"])) {
    require $_SERVER['DOCUMENT_ROOT'] . "ConnexionBD.php";
    $cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
    $timeZone = new DateTimeZone("America/New_York");
    $date = new DateTime("now", $timeZone);
    $dateString = $date->format("Y-m-d H:i:s");
    $noConnexion = $_SESSION["NoConnexion"];
    mysqli_query($cBD, "UPDATE connexions SET Deconnexion = '$dateString' where NoConnexion = $noConnexion");
}
session_destroy();
header("Location: /Connexion/Connexion.php");
?>