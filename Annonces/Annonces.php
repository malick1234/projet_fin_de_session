<?php
session_start();
if (!isset($_SESSION["ok"])) {
    header('Location: ../Connexion/Connexion.php');
}
require_once "navigationAnnonce.php";
require_once "ConnexionBD.php";

$cBD = mysqli_connect($servername, $username, $password, $dbname);
$strEmail = $_SESSION["courriel"];

// Variables de pagination
$annoncesParPage = 6; // Nombre d'annonces par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Page actuelle
$start = ($page - 1) * $annoncesParPage; // Calcul de l'index de départ

// Récupérer le nombre total d'annonces
$totalQuery = mysqli_query($cBD, "SELECT COUNT(*) AS total FROM annonces WHERE Etat=1");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalAnnonces = $totalRow['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalAnnonces / $annoncesParPage);

// Récupérer les annonces pour la page actuelle
$query = mysqli_query($cBD, "SELECT * FROM annonces WHERE Etat=1 LIMIT $start, $annoncesParPage");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<script type="text/javascript">
  function validerInformation() {
    let boolValide = true;
    let docDesC = document.getElementById('txtRecherche');

    if (docDesC.value != "") {
      var strDesC = "sValide";
    } else {
      var strDesC = "sNonValide";
      boolValide = false;
    }

    if (boolValide == true) {
      document.getElementById('formAjoutAnnonce').submit();
    }
  }
</script>

<div class="container">
  <div class="jumbotron text-center">
    <h1>Annonces</h1>
  </div>
  <form id="formAjoutAnnonce" method="post" action="" enctype="multipart/form-data">
    <div class="d-flex justify-content-around">
      <input type="text" class="form-control" id="txtRecherche" name="txtRecherche" required="required">
      <input type="button" value="Rechercher" class="btn btn-primary" id="btnValider" onclick="validerInformation()">
    </div>
  </form>
  <div class="row">
    <?php
    $compteur = $start + 1;
    while ($row = mysqli_fetch_assoc($query)) {
      $categorie = $row['Categorie'];
      $query2 = mysqli_query($cBD, "SELECT * FROM categories WHERE NoCategorie='$categorie'");
      $row2 = mysqli_fetch_assoc($query2);
      $numUtilisateur = $row['NoUtilisateur'];
      $query3 = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE NoUtilisateur='$numUtilisateur'");
      $row3 = mysqli_fetch_assoc($query3);
    ?>
    <div class="d-flex flex-column col-sm-4">
      <div class="p-1">No: <?= $compteur++ ?></div>
      <div class="p-1">Annonce No: <?= $row['NoAnnonce'] ?></div>
      <div class="p-1">parut le: <?= $row['Parution'] ?></div>
      <div class="p-1"><img src="<?= "../photos-annonce/" . $row['Photo'] ?>" class="img-fluid" alt="retour"></div>
      <div class="p-1"><?php if ($row3['Courriel'] != $strEmail) { ?>
        <p>Nom de l'auteur: <?= $row3['Nom'] . " " . $row3['Prenom'] ?></p>
      <?php } ?></div>
      <div class="p-1">Catégories: <?= $row2['Description'] ?></div>
      <div class="p-1">Description: <?= $row['DescriptionAbregee'] ?></div>
      <div class="p-1">Prix: <?= $row['Prix'] ?></div>
    </div>
    <?php } ?>
  </div>

  <!-- Pagination -->
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=1">Première page</a>
      </li>
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page - 1 ?>">Précédente</a>
      </li>
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page + 1 ?>">Suivante</a>
      </li>
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $totalPages ?>">Dernière page</a>
      </li>
    </ul>
  </nav>
</div>
</body>
</html>
