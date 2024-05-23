<?php
session_start();
if (!isset($_SESSION["ok"])) {
    header('Location: ../Connexion/Connexion.php');
}

require_once "navigationGestionAnnonce.php";
require_once "ConnexionBD.php";

$cBD = mysqli_connect($servername, $username, $password, $dbname);
$strEmail = $_SESSION["courriel"];

$annoncesParPage = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $annoncesParPage;

// Paramètres de tri
$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'Parution';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'asc' ? 'asc' : 'desc';

$userQuery = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel = '$strEmail'");
$userRow = mysqli_fetch_assoc($userQuery);
$numUser = $userRow['NoUtilisateur'];

$totalQuery = mysqli_query($cBD, "SELECT COUNT(*) AS total FROM annonces WHERE NoUtilisateur = '$numUser'");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalAnnonces = $totalRow['total'];

$totalPages = ceil($totalAnnonces / $annoncesParPage);

$query = mysqli_query($cBD, "SELECT annonces.*, categories.Description AS CategorieDescription
    FROM annonces
    LEFT JOIN categories ON annonces.Categorie = categories.NoCategorie
    WHERE NoUtilisateur = '$numUser'
    ORDER BY $sortField $sortOrder
    LIMIT $start, $annoncesParPage");

?>
<!DOCTYPE html>
<html lang="fr">

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
            let docDesC = document.getElementById('txtDesC');

            if (docDesC.value != "") {
                var strDesC = "sValide";
            } else {
                var strDesC = "sNonValide";
                boolValide = false;
            }

            if (boolValide == true) {
                document.getElementById('formAjoutAnnonce').submit()
            }
        }
    </script>
    <div class="container">
        <div class="jumbotron text-center">
            <h1>Annonces de l'utilisateur</h1>
            <h1><?= $strEmail ?></h1>
        </div>
        <form id="formAjoutAnnonce" method="get" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="sortField">Trier par</label>
                    <select class="form-control" id="sortField" name="sortField">
                        <option value="Parution" <?= $sortField == 'Parution' ? 'selected' : '' ?>>Date de parution</option>
                        <option value="CategorieDescription" <?= $sortField == 'CategorieDescription' ? 'selected' : '' ?>>Catégorie</option>
                        <option value="DescriptionAbregee" <?= $sortField == 'DescriptionAbregee' ? 'selected' : '' ?>>Description abrégée</option>
                        <option value="Etat" <?= $sortField == 'Etat' ? 'selected' : '' ?>>État</option>
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
                <div class="p-1">Paru le: <?= $row['Parution'] ?></div>
                <div class="p-1"><img src="<?= "../photos-annonce/" . $row['Photo'] ?>" class="img-fluid" alt="Annonce"></div>
                <div class="p-1"><?php if ($row['NoUtilisateur'] != $numUser) { ?>
                    <p>Nom de l'auteur: <?= $row['Nom'] . " " . $row['Prenom'] ?></p>
                <?php } ?></div>
                <div class="p-1">Catégorie: <?= $row['CategorieDescription'] ?></div>
                <div class="p-1">Description: <?= $row['DescriptionAbregee'] ?></div>
                <div class="p-1"><?php if ($row['Etat'] == 1) { ?>
                    État: Actif
                <?php } else if ($row['Etat'] == 2) { ?>
                    État: Inactif
                <?php } ?></div>
                <div class="p-1">Prix: <?= $row['Prix'] ?></div>
                <div class="p-1">
                    <a href="MiseAJourAnnonce.php?id=<?= $row['NoAnnonce'] ?>" class="btn btn-warning">Mise à jour d'annonce</a>
                </div>
                <div class="p-1">
                    <a href="retirerAnnonce.php?id=<?= $row['NoAnnonce'] ?>" class="btn btn-danger">Retrait d'annonce</a>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="col-sm-10 justify-content-center text-center">
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=1&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>">Première page</a>
                    </li>
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>">Précédente</a>
                    </li>
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>">Suivante</a>
                    </li>
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $totalPages ?>&sortField=<?= $sortField ?>&sortOrder=<?= $sortOrder ?>">Dernière page</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</body>

</html>
