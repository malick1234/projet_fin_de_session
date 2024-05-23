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

$userQuery = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel = '$strEmail'");
$userRow = mysqli_fetch_assoc($userQuery);
$numUser = $userRow['NoUtilisateur'];

$totalQuery = mysqli_query($cBD, "SELECT COUNT(*) AS total FROM annonces WHERE NoUtilisateur = '$numUser'");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalAnnonces = $totalRow['total'];

$totalPages = ceil($totalAnnonces / $annoncesParPage);

$query = mysqli_query($cBD, "SELECT * FROM annonces LIMIT $start, $annoncesParPage");

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
                    <div class="p-1">Paru le: <?= $row['Parution'] ?></div>
                    <div class="p-1"><img src="<?= "../photos-annonce/" . $row['Photo'] ?>" class="img-fluid" alt="retour">
                    </div>
                    <div class="p-1">
                        <?php if ($row3['Courriel'] != $strEmail) { ?>
                            Nom de l'auteur: <?= $row3['Nom'] . " " . $row3['Prenom'] ?>
                        <?php } ?>
                    </div>
                    <div class="p-1">Catégories: <?= $row2['Description'] ?></div>
                    <div class="p-1">Description: <?= $row['DescriptionAbregee'] ?></div>
                    <div class="p-1">
                        <?php if ($row['Etat'] == 1) { ?>
                            État: Actif
                        <?php } else if ($row['Etat'] == 2) { ?>
                                État: Inactif
                        <?php } ?>
                    </div>
                    <div class="p-1">Prix: <?= $row['Prix'] ?></div>
                    <div class="p-1">
                        <a href="MiseAJourAnnonce.php?id=<?= $row['NoAnnonce'] ?>" class="btn btn-warning">Mise à jour
                            d'annonce</a>
                    </div>
                    <div class="p-1">
                        <a href="retirerAnnonce.php?id=<?= $row['NoAnnonce'] ?>" class="btn btn-danger">Retrait
                            d'annonce</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="col-sm-10">
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

    </div>
</body>

</html>