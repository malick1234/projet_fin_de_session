<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if (!isset($_SESSION["ok"])) {
  header('Location: ../Connexion/Connexion.php');
}
$page = 1;
$affichageParPage = 3;
$typeSort = "default";
$sort = "ORDER BY PARUTION DESC";
$rechAuteur = "";
$rechDescription = "";
$rechCat = "";
$rechDate1 = "";
$rechDate2 = "";

if (isset($_GET["page"]) && is_numeric($_GET["page"]))
  $page = (int) $_GET["page"];
if (isset($_GET["adpp"]) && is_numeric($_GET["adpp"]))
  $affichageParPage = (int) $_GET["adpp"];
if (isset($_GET["sort"])) {
  $typeSort = $_GET["sort"];
  switch ($typeSort) {
    case "dateAsc":
      $sort = "ORDER BY PARUTION ASC";
      break;
    case "nomPrenomAsc":
      $sort = "ORDER BY Nom Asc, Prenom";
      break;
    case "nomPrenomDesc":
      $sort = "ORDER BY Nom Desc, Prenom";
      break;
    case "catAsc":
      $sort = "ORDER BY description ASC";
      break;
    case "catDesc":
      $sort = "ORDER BY description Desc";
      break;
    default:
      $sort = "ORDER BY PARUTION DESC";
      break;
  }
} else {
  $sort = "ORDER BY PARUTION DESC";
  $typeSort = "default";
}
if (isset($_GET["Auteur"]))
  $rechAuteur = $_GET["Auteur"];
if (isset($_GET["Description"]))
  $rechDescription = $_GET["Description"];
if (isset($_GET["Categorie"]))
  $rechCat = $_GET["Categorie"];
if (isset($_GET["date1"]))
  $rechDate1 = $_GET["date1"];
if (isset($_GET["date2"]))
  $rechDate2 = $_GET["date2"];
if ($rechDate2 == "" || $rechDate2 < $rechDate1)
  $rechDate2 = $rechDate1;
if ($rechDate1 == "" && $rechDate2 != "")
  $rechDate2 = "";

$requeteDate = "";

if ($rechDate1 != "")
  $requeteDate = "and (Parution BETWEEN '$rechDate1' and '$rechDate2')";

require $_SERVER['DOCUMENT_ROOT'] . "DBAUTH.php";
$cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
$tableau = mysqli_fetch_all(mysqli_query($cBD, "SELECT * FROM annonces A INNER JOIN utilisateurs U ON U.NOUTILISATEUR = A.NOUTILISATEUR 
INNER JOIN categories C ON C.NOCATEGORIE = CATEGORIE WHERE ETAT = 1 and (nom like '%$rechAuteur%'
 or prenom like '%$rechAuteur%') and (descriptionAbregee like '%$rechDescription%' or descriptionComplete like '%$rechDescription%')
  and description like '%$rechCat%' $requeteDate $sort"));
$nbrAnnonces = count($tableau);
$nbrPagesTot = (int) ceil($nbrAnnonces / ($affichageParPage < 1 ? 3 : $affichageParPage));
if ($page > $nbrPagesTot)
  $page = $nbrPagesTot;
$index = ($page - 1) * $affichageParPage;
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
  <?php require_once "navigationAdmin.php" ?>
  <h1 class="text-center">Affichage de toutes les annonces</h1>
  <div class="container-fluid">
    <div class="grid text-center">
      <span>
        Nombre d'annonces par page
        <select name="AdPerPage" id="AdPerPage" onchange="change()">
          <option value="3" <?php if ($affichageParPage == 3) { ?> selected="selected" <?php } ?>>3</option>
          <option value="6" <?php if ($affichageParPage == 6) { ?> selected="selected" <?php } ?>>6</option>
          <option value="9" <?php if ($affichageParPage == 9) { ?> selected="selected" <?php } ?>>9</option>
          <option value="12" <?php if ($affichageParPage == 12) { ?> selected="selected" <?php } ?>>12</option>
        </select>
      </span>
      <span>
        &nbsp;&nbsp;<a
          href="./ModuleAdmin.php?page=<?php echo ($page <= 1 ? 1 : $page - 1) ?>&adpp=<?php echo $affichageParPage ?>&sort=<?php echo $typeSort ?>&Auteur=<?php echo $rechAuteur ?>&Description=<?php echo $rechDescription ?>&Categorie=<?php echo $rechCat ?>&date1=<?php echo $rechDate1 ?>&date2=<?php echo $rechDate2 ?>">Page
          Arrière (<?php echo ($page <= 1 ? 1 : $page - 1) ?>)</a> &nbsp;&nbsp;
        Page <?php echo $page ?> &nbsp;&nbsp;
        <a
          href="./ModuleAdmin.php?page=<?php echo ($page == $nbrPagesTot ? $nbrPagesTot : $page + 1) ?>&adpp=<?php echo $affichageParPage ?>&sort=<?php echo $typeSort ?>&Auteur=<?php echo $rechAuteur ?>&Description=<?php echo $rechDescription ?>&Categorie=<?php echo $rechCat ?>&date1=<?php echo $rechDate1 ?>&date2=<?php echo $rechDate2 ?>">Page
          Avant (<?php echo ($page == $nbrPagesTot ? $nbrPagesTot : $page + 1) ?>)</a>&nbsp;&nbsp;
        <input id="page" type="text" placeholder="page" style="width: 5ch;">
        <button text="Aller à cette page" class="btn btn-outline-dark" onclick="buttonClick()">Aller à cette
          page</button>
      </span> <br>
      <span class="text-center">il y a <?php echo $nbrAnnonces ?> annonce(s) au totale</span>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span>Tri par &nbsp;<select name="ddlSorting" id="ddlSorting" onchange="change()">
          <option value="default" <?php if ($typeSort == "default") { ?> selected="selected" <?php } ?>>Parution
            récent->ancien</option>
          <option value="dateAsc" <?php if ($typeSort == "dateAsc") { ?> selected="selected" <?php } ?>>Parution
            ancien->récent</option>
          <option value="nomPrenomAsc" <?php if ($typeSort == "nomPrenomAsc") { ?> selected="selected" <?php } ?>>Nom et
            prénom croissant</option>
          <option value="nomPrenomDesc" <?php if ($typeSort == "nomPrenomDesc") { ?> selected="selected" <?php } ?>>Nom et
            prénom décroissant</option>
          <option value="catAsc" <?php if ($typeSort == "catAsc") { ?> selected="selected" <?php } ?>>Catégorie croissant
          </option>
          <option value="catDesc" <?php if ($typeSort == "catDesc") { ?> selected="selected" <?php } ?>>Catégorie
            décroissant</option>
        </select></span> <br>
      <span>Recherche sur <br>Auteur: <input style="width: 20ch;" id="rechAuteur" type="text" placeholder="Auteur"
          onchange="change()" value="<?php echo $rechAuteur ?>"></span>
      <span>Description: <input style="width: 40ch;" id="rechDescription" type="text" placeholder="Description"
          onchange="change()" value="<?php echo $rechDescription ?>"></span> <br>
      <span>Catégorie: <input style="width: 13ch;" id="rechCat" type="text" placeholder="Catégorie" onchange="change()"
          value="<?php echo $rechCat ?>"></span>
      <span>Date de parution: <input style="width: 20ch;" id="rechParution" type="date" onchange="change()"
          value="<?php echo $rechDate1 ?>"> à</span>
      <span><input style="width: 20ch;" id="rechParution2" type="date" onchange="change()"
          value="<?php echo $rechDate2 ?>"></span>
    </div>
  </div>

  <br><br><br><br>
  </div>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <?php
      for ($i = 0; $i < $affichageParPage && $nbrAnnonces > 0; $i++) {
        if ($index < $nbrAnnonces) {
          $noAnnonce = $tableau[$index][0];
          $noUtilisateur = $tableau[$index][1];
          $nom = $tableau[$index][17];
          $prenom = $tableau[$index][18];
          ?>
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 text-center">
            <?php if ($i > 2) { ?> <br><br><br><br> <?php } ?>
            <img src="../Annonces/<?php echo $tableau[$index][7] ?>" alt="not loaded"
              style="width: 300px; height: 200px"><br>
            <span>Annonce #<?php echo $index + 1 ?>: No d'annonce: <?php echo $tableau[$index][0] ?></span><br>
            <span>Date de parution: <?php echo $tableau[$index][2] ?> </span> <br>
            <span>Auteur <?php if ($_SESSION["ok"] != $noUtilisateur) { ?>
                <a href="../Annonces/Contacter.php?vendeur=<?php echo $noUtilisateur ?>"><?php echo "$prenom $nom" ?></a> <?php
            } else {
              echo "$prenom $nom";
            } ?>
            </span> <br>
            <span>Catégorie: <?php echo $tableau[$index][25] ?>, Prix ($):
              <?php echo ($tableau[$index][6] == 0 ? "N/A" : $tableau[$index][6]) ?></span> <br>
            <span><a href="../Annonces/DescriptionDetail.php?annonce=<?php echo $noAnnonce ?>">Description abrégée: <br>
                <?php echo $tableau[$index][4] ?></a></span>
          </div>
          <?php
        }
        $index++;
      }
      ?>
    </div>
  </div>
  </div>
  <br><br><br><br>

  <footer>
    <div class="text-center">
      <p>
        <img src="../logoCGG.jpg" alt="" srcset="">
        © Département d'informatique G.-G.
      </p>
    </div>
  </footer>

</body>

<script>
  var page = document.getElementById("page").value
  var adpp = document.getElementById("AdPerPage").value
  var sort = document.getElementById("ddlSorting").value
  var auteur = document.getElementById("rechAuteur").value
  var description = document.getElementById("rechDescription").value
  var cat = document.getElementById("rechCat").value
  var date1 = document.getElementById("rechParution").value
  var date2 = document.getElementById("rechParution2").value
  function buttonClick() {
    date1 = document.getElementById("rechParution").value
    date2 = document.getElementById("rechParution2").value
    cat = document.getElementById("rechCat").value
    page = document.getElementById("page").value
    page = parseInt(page)
    adpp = document.getElementById("AdPerPage").value
    sort = document.getElementById("ddlSorting").value
    auteur = document.getElementById("rechAuteur").value
    description = document.getElementById("rechDescription").value
    if (isNaN(page)) {
      page = 1
      document.getElementById("page").value = ""
      alert("Veuillez mettre un nombre pour la page")
    }
    else if (page < 1) {
      document.getElementById("page").value = ""
      alert("Veuillez mettre un nombre positif")
    }
    else
      window.location = "./ModuleAdmin.php?page=" + page + "&adpp=" + adpp + "&sort=" + sort + "&Auteur=" + auteur + "&Description=" + description + "&Categorie=" + cat + "&date1=" + date1 + "&date2=" + date2
  }
  function change() {
    date1 = document.getElementById("rechParution").value
    date2 = document.getElementById("rechParution2").value
    cat = document.getElementById("rechCat").value
    description = document.getElementById("rechDescription").value
    auteur = document.getElementById("rechAuteur").value
    adpp = document.getElementById("AdPerPage").value
    sort = document.getElementById("ddlSorting").value
    window.location = "./ModuleAdmin.php?page=1&adpp=" + adpp + "&sort=" + sort + "&Auteur=" + auteur + "&Description=" + description + "&Categorie=" + cat + "&date1=" + date1 + "&date2=" + date2
  }
</script>

</html>