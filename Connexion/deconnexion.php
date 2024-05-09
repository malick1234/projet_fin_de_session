<?php
session_start();
$_SESSION["identifiant"] = null;
$_SESSION["password"] = null;
header('Location: connexion.php');
die();
