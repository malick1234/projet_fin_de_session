<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if (!isset($_SESSION["ok"])) {
    header('Location: ../Connexion/Connexion.php');
    exit;
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style.css">
    <title>Nettoyer la BD</title>
</head>

<body>
    <?php require_once "navigationAdmin.php"; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6">
                <h2 class="sub-header">Utilisateurs inactifs à supprimer de la BD</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-1">Numéro de l'utilisateur</th>
                                <th class="col-md-2">Courriel</th>
                                <th class="col-md-3">Date de création</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require ("connexionBD.php");

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT NoUtilisateur, Courriel, Creation 
                                    FROM utilisateurs 
                                    WHERE Creation < NOW() - INTERVAL 3 MONTH AND Statut = 0";

                            $resultat = $conn->query($sql);

                            if ($resultat) {
                                while ($util = mysqli_fetch_object($resultat)) {
                                    $no = $util->NoUtilisateur;
                                    $courriel = $util->Courriel;
                                    $creation = $util->Creation;
                                    ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $courriel; ?></td>
                                        <td><?php echo $creation; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Error: " . $sql . "<br>" . $conn->error;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <form method="post" onsubmit="return confirm('Voulez-vous supprimer les utilisateurs inactifs ?')">
                    <button id="btnSupprimerUtilisateurs" name="btnSupprimerUtilisateurs" type="submit"
                        class="btn btn-primary">Supprimer les utilisateurs inactifs</button>
                </form>
            </div>
            <div class="col-xs-6">
                <h2 class="sub-header">Annonces retirées à supprimer de la BD</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-1">Numéro de l'annonce</th>
                                <th class="col-md-2">Numéro de l'utilisateur</th>
                                <th class="col-md-3">Date de parution de l'annonce</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2 = "SELECT NoAnnonce, NoUtilisateur, Parution 
                                     FROM annonces 
                                     WHERE Etat = 3";

                            $resultat2 = $conn->query($sql2);

                            if ($resultat2) {
                                while ($annonce = mysqli_fetch_object($resultat2)) {
                                    $noAnnonce = $annonce->NoAnnonce;
                                    $noUtilisateur = $annonce->NoUtilisateur;
                                    $parution = $annonce->Parution;
                                    ?>
                                    <tr>
                                        <td><?php echo $noAnnonce; ?></td>
                                        <td><?php echo $noUtilisateur; ?></td>
                                        <td><?php echo $parution; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "Error: " . $sql2 . "<br>" . $conn->error;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <form method="post" onsubmit="return confirm('Voulez-vous supprimer les annonces retirées ?')">
                    <button id="btnSupprimerAnnonces" name="btnSupprimerAnnonces" type="submit"
                        class="btn btn-primary">Supprimer les annonces retirées</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['btnSupprimerUtilisateurs'])) {
            $sql3 = "DELETE FROM utilisateurs 
                     WHERE Creation < NOW() - INTERVAL 3 MONTH AND Statut = 0";
            if ($conn->query($sql3) === TRUE) {
                header("Location: ModuleAdmin.php");
                exit;
            } else {
                echo "Error: " . $sql3 . "<br>" . $conn->error;
            }
        }
        if (isset($_POST['btnSupprimerAnnonces'])) {
            $sql4 = "DELETE FROM annonces 
                     WHERE Etat = 3";
            if ($conn->query($sql4) === TRUE) {
                header("Location: ModuleAdmin.php");
                exit;
            } else {
                echo "Error: " . $sql4 . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
    ?>

</body>

</html>
