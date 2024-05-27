<?php
session_start();
$_SESSION["ok"] = null;
$_SESSION["Nom"] = null;
$_SESSION["Prenom"] = null;
$_SESSION["courriel"] = null;
header('Location: connexion.php');
die();
