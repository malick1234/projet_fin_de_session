<?php
session_start();
if (!isset($_SESSION["ok"])) {
    header('Location: ../Connexion/Connexion.php');
}
require_once "navigationAnnonce.php";
require_once "ConnexionBD.php";

$cBD = mysqli_connect($servername, $username, $password, $dbname);
$strEmail = $_SESSION["courriel"];

// Paramètres de tri
$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'Parution';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'asc' ? 'asc' : 'desc';

// Paramètres de recherche
$searchDateStart = isset($_GET['searchDateStart']) ? $_GET['searchDateStart'] : '';
$searchDateEnd = isset($_GET['searchDateEnd']) ? $_GET['searchDateEnd'] : '';
$searchAuthor = isset($_GET['searchAuthor']) ? $_GET['searchAuthor'] : '';
$searchCategory = isset($_GET['searchCategory']) ? $_GET['searchCategory'] : '';
$searchDescription = isset($_GET['searchDescription']) ? $_GET['searchDescription'] : '';

// Variables de pagination
$annoncesParPage = isset($_GET['annoncesParPage']) ? (int)$_GET['annoncesParPage'] : 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $annoncesParPage;

// Construire la clause WHERE pour la recherche
$whereClauses = ["annonces.Etat=1"];
if (!empty($searchDateStart) && !empty($searchDateEnd)) {
    $whereClauses[] = "annonces.Parution BETWEEN '$searchDateStart' AND '$searchDateEnd'";
}
if (!empty($searchAuthor)) {
    $utilisateurQuery = mysqli_query($cBD, "SELECT NoUtilisateur FROM utilisateurs WHERE Nom LIKE '%$searchAuthor%' OR Prenom LIKE '%$searchAuthor%'");
    $noUtilisateurs = [];
    while ($utilisateurRow = mysqli_fetch_assoc($utilisateurQuery)) {
        $noUtilisateurs[] = $utilisateurRow['NoUtilisateur'];
    }
    if (!empty($noUtilisateurs)) {
        $noUtilisateurs = implode(',', $noUtilisateurs);
        $whereClauses[] = "annonces.NoUtilisateur IN ($noUtilisateurs)";
    }
}
if (!empty($searchCategory)) {
    $whereClauses[] = "annonces.Categorie='$searchCategory'";
}
if (!empty($searchDescription)) {
    $whereClauses[] = "annonces.DescriptionAbregee LIKE '%$searchDescription%'";
}
$whereSql = implode(' AND ', $whereClauses);

// Récupérer le nombre total d'annonces correspondant aux critères
$totalQuery = mysqli_query($cBD, "SELECT COUNT(*) AS total FROM annonces WHERE $whereSql");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalAnnonces = $totalRow['total'];

// Calculer le nombre total de pages
$totalPages = ceil($totalAnnonces / $annoncesParPage);

// Récupérer les annonces pour la page actuelle avec les critères de recherche et de tri
$query = mysqli_query($cBD, "SELECT annonces.*, utilisateurs.Nom, utilisateurs.Prenom, utilisateurs.Courriel, categories.Description AS CategorieDescription
    FROM annonces
    LEFT JOIN utilisateurs ON annonces.NoUtilisateur = utilisateurs.NoUtilisateur
    LEFT JOIN categories ON annonces.Categorie = categories.NoCategorie
    WHERE $whereSql
    ORDER BY $sortField $sortOrder
    LIMIT $start, $annoncesParPage");

// Récupérer les catégories pour le filtre
$categoriesQuery = mysqli_query($cBD, "SELECT * FROM categories");
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
  <form id="formAjoutAnnonce" method="get" action="" enctype="multipart/form-data">
    <div class="form-row">
      <div class="form-group col-md-3">
        <label for="searchDateStart">Date de début</label>
        <input type="date" class="form-control" id="searchDateStart" name="searchDateStart" value="<?= $searchDateStart ?>">
      </div>
      <div class="form-group col-md-3">
        <label for="searchDateEnd">Date de fin</label>
        <input type="date" class="form-control" id="searchDateEnd" name="searchDateEnd" value="<?= $searchDateEnd ?>">
      </div>
      <div class="form-group col-md-3">
        <label for="searchAuthor">Auteur</label>
        <input type="text" class="form-control" id="searchAuthor" name="searchAuthor" value="<?= $searchAuthor ?>">
      </div>
      <div class="form-group col-md-3">
        <label for="searchCategory">Catégorie</label>
        <select class="form-control" id="searchCategory" name="searchCategory">
          <option value="">Toutes</option>
          <?php while ($category = mysqli_fetch_assoc($categoriesQuery)): ?>
          <option value="<?= $category['NoCategorie'] ?>" <?= $searchCategory == $category['NoCategorie'] ? 'selected' : '' ?>>
            <?= $category['Description'] ?>
          </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="searchDescription">Description</label>
        <input type="text" class="form-control" id="searchDescription" name="searchDescription" value="<?= $searchDescription ?>">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3">
        <label for="annoncesParPage">Annonces par page</label>
        <select class="form-control" id="annoncesParPage" name="annoncesParPage">
          <option value="5" <?= $annoncesParPage == 5 ? 'selected' : '' ?>>5</option>
          <option value="10" <?= $annoncesParPage == 10 ? 'selected' : '' ?>>10</option>
          <option value="15" <?= $annoncesParPage == 15 ? 'selected' : '' ?>>15</option>
          <option value="20" <?= $annoncesParPage == 20 ? 'selected' : '' ?>>20</option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-3">
        <label for="sortField">Trier par</label>
        <select class="form-control" id="sortField" name="sortField">
          <option value="Parution" <?= $sortField == 'Parution' ? 'selected' : '' ?>>Date de parution</option>
          <option value="Nom" <?= $sortField == 'Nom' ? 'selected' : '' ?>>Nom de l'auteur</option>
          <option value="Prenom" <?= $sortField == 'Prenom' ? 'selected' : '' ?>>Prénom de l'auteur</option>
          <option value="CategorieDescription" <?= $sortField == 'CategorieDescription' ? 'selected' : '' ?>>Catégorie</option>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="sortOrder">Ordre</label>
        <select class="form-control" id="sortOrder" name="sortOrder">
          <option value="asc" <?= $sortOrder == 'asc' ? 'selected' : '' ?>>Croissant</option>
          <option value="desc" <?= $sortOrder == 'desc' ? 'selected' : '' ?>>Décroissant</option>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Rechercher</button>
  </form>

  <div class="row mt-4">
    <?php
    $compteur = $start + 1;
    while ($row = mysqli_fetch_assoc($query)) {
    ?>
    <div class="d-flex flex-column col-sm-4 mb-4">
      <div class="p-1">No: <?= $compteur++ ?></div>
      <div class="p-1">Annonce No: <?= $row['NoAnnonce'] ?></div>
      <div class="p-1">Parut le: <?= $row['Parution'] ?></div>
      <div class="p-1"><img src="<?= "../photos-annonce/" . $row['Photo'] ?>" class="img-fluid" alt="Annonce"></div>
      <div class="p-1"><?php if ($row['Courriel'] != $strEmail) { ?>
        <p>Nom de l'auteur: <?= $row['Nom'] . " " . $row['Prenom'] ?></p>
      <?php } ?></div>
      <div class="p-1">Catégorie: <?= $row['CategorieDescription'] ?></div>
      <div class="p-1">Description: <?= $row['DescriptionAbregee'] ?></div>
      <div class="p-1">Prix: <?= $row['Prix'] ?></div>
    </div>
    <?php } ?>
  </div>

  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=1&annoncesParPage=<?= $annoncesParPage ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>&searchDateStart=<?= $searchDateStart ?>&searchDateEnd=<?= $searchDateEnd ?>&searchAuthor=<?= $searchAuthor ?>&searchCategory=<?= $searchCategory ?>&searchDescription=<?= $searchDescription ?>">Première page</a>
      </li>
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page - 1 ?>&annoncesParPage=<?= $annoncesParPage ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>&searchDateStart=<?= $searchDateStart ?>&searchDateEnd=<?= $searchDateEnd ?>&searchAuthor=<?= $searchAuthor ?>&searchCategory=<?= $searchCategory ?>&searchDescription=<?= $searchDescription ?>">Précédente</a>
      </li>
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page + 1 ?>&annoncesParPage=<?= $annoncesParPage ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>&searchDateStart=<?= $searchDateStart ?>&searchDateEnd=<?= $searchDateEnd ?>&searchAuthor=<?= $searchAuthor ?>&searchCategory=<?= $searchCategory ?>&searchDescription=<?= $searchDescription ?>">Suivante</a>
      </li>
      <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $totalPages ?>&annoncesParPage=<?= $annoncesParPage ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>&searchDateStart=<?= $searchDateStart ?>&searchDateEnd=<?= $searchDateEnd ?>&searchAuthor=<?= $searchAuthor ?>&searchCategory=<?= $searchCategory ?>&searchDescription=<?= $searchDescription ?>">Dernière page</a>
      </li>
    </ul>
  </nav>
</div>
</body>
</html>
