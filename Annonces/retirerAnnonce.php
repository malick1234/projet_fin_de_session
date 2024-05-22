<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["ok"])) {
        header('Location: ../Connexion/Connexion.php');
        exit();
    }
    require_once "navigationGestionAnnonce.php";
    require_once "ConnexionBD.php";

    $cBD = mysqli_connect($servername, $username, $password, $dbname);
    if (!$cBD) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_GET['id'])) {
        $annonceId = $_GET['id'];
        $strEmail = $_SESSION["courriel"];

        $query = mysqli_query($cBD, "SELECT * FROM annonces WHERE NoAnnonce='$annonceId'");
        $row = mysqli_fetch_assoc($query);

        if ($row) {
            $categorie = $row['Categorie'];
            $query2 = mysqli_query($cBD, "SELECT * FROM categories WHERE NoCategorie='$categorie'");
            $row2 = mysqli_fetch_assoc($query2);
            $numUtilisateur = $row['NoUtilisateur'];
            $query3 = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE NoUtilisateur='$numUtilisateur'");
            $row3 = mysqli_fetch_assoc($query3);
        } else {
            echo "Annonce non trouvée.";
            exit();
        }
    } else {
        echo "<b>ID D'ANNONCE MANQUANT!!! <br/> VEUILLEZ SÉLECTIONNER UNE ANNONCE EXISTANTE.</b>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
        $deleteQuery = "DELETE FROM annonces WHERE NoAnnonce='$annonceId'";
        if (mysqli_query($cBD, $deleteQuery)) {
            header('Location: gestionAnnonces.php');
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erreur lors du retrait de l'annonce: " . mysqli_error($cBD) . "</div>";
        }
    }
    ?>

    <div class="container">
        <div class="jumbotron text-center">
            <h1>Retrait de l'annonce</h1>
        </div>
        <div class="row">
            <div class="d-flex flex-column col-sm-12">
                <div class="p-1">Annonce No: <?= $row['NoAnnonce'] ?></div>
                <div class="p-1">Parut le: <?= $row['Parution'] ?></div>
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
                    <form method="post" action="">
                        <div class="form-group">
                            <label><b>Êtes-vous sûr de vouloir retirer cette annonce ?</b></label>
                            <div class="d-flex justify-content-between">
                                <button type="submit" name="confirm" class="btn btn-danger">Confirmer le
                                    retrait
                                </button>
                                <a href="javascript:history.back()" class="btn btn-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>