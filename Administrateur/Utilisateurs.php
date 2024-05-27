<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if (!isset($_SESSION["ok"])) {
    header('Location: ../Connexion/Connexion.php');
    exit;
}
require_once "ConnexionBD.php";

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style.css">
    <title>Utilisateurs</title>
</head>

<body>
    <?php require_once "navigationAdmin.php"; ?>
    <h1 class="text-center">Affichage des utilisateurs</h1>
    <div class="container-fluid">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Liste des utilisateurs</div>
            <div class="card">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Numéro d'utilisateur</th>
                            <th>Courriel</th>
                            <th>Date de création</th>
                            <th>Nombre de connexions</th>
                            <th>Statut</th>
                            <th>Numéro d'employé</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Téléphone (Maison)</th>
                            <th>Téléphone (Travail)</th>
                            <th>Téléphone (Cellulaire)</th>
                            <th>Date de modification</th>
                            <th>Connexions récentes</th>
                            <th>Déconnexions récentes</th>
                            <th>Nombre d'annonces</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once ("connexionBD.php");

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT NoUtilisateur, Courriel, Creation, NbConnexions, Statut, NoEmpl, Nom, Prenom, NoTelMaison, NoTelTravail, NoTelCellulaire, Modification
                                FROM utilisateurs
                                ORDER BY Nom ASC, Prenom ASC";

                        $resultat = $conn->query($sql);

                        if ($resultat) {
                            while ($util = mysqli_fetch_object($resultat)) {
                                $no = $util->NoUtilisateur;
                                $courriel = $util->Courriel;
                                $creation = $util->Creation;
                                $nbConnexions = $util->NbConnexions;
                                $statut = $util->Statut;
                                $noEmpl = $util->NoEmpl;
                                $nom = $util->Nom;
                                $prenom = $util->Prenom;

                                $telMaison = $util->NoTelMaison ;
                                $telTravail = $util->NoTelTravail ;
                                $telCellulaire = $util->NoTelCellulaire ;
                                $dateMod = $util->Modification;
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $courriel; ?></td>
                                    <td><?php echo $creation; ?></td>
                                    <td><?php echo $nbConnexions; ?></td>
                                    <td><?php echo $statut; ?></td>
                                    <td><?php echo $noEmpl; ?></td>
                                    <td><?php echo $nom; ?></td>
                                    <td><?php echo $prenom; ?></td>
                                    <td><?php echo $telMaison; ?></td>
                                    <td><?php echo $telTravail; ?></td>
                                    <td><?php echo $telCellulaire; ?></td>
                                    <td><?php echo $dateMod; ?></td>
                                    <td>
                                        <ol>
                                            <?php
                                            $sql2 = "SELECT Connexion FROM connexions 
                                                     WHERE NoUtilisateur = '$no'
                                                     ORDER BY Connexion DESC
                                                     LIMIT 5";
                                            $resultat2 = $conn->query($sql2);
                                            if ($resultat2) {
                                                while ($connexion = mysqli_fetch_object($resultat2)) {
                                                    echo "<li>" . $connexion->Connexion . "</li>";
                                                }
                                            } else {
                                                echo "<li>N/A</li>";
                                            }
                                            ?>
                                        </ol>
                                    </td>
                                    <td>
                                        <ol>
                                            <?php
                                            $sql3 = "SELECT Deconnexion FROM connexions 
                                                     WHERE NoUtilisateur = '$no'
                                                     ORDER BY Connexion DESC
                                                     LIMIT 5";
                                            $resultat3 = $conn->query($sql3);
                                            if ($resultat3) {
                                                while ($deconnexion = mysqli_fetch_object($resultat3)) {
                                                    if (is_null($deconnexion->Deconnexion)) {
                                                        echo "<li>N/A</li>";
                                                    } else {
                                                        echo "<li>" . $deconnexion->Deconnexion . "</li>";
                                                    }
                                                }
                                            } else {
                                                echo "<li>N/A</li>";
                                            }
                                            ?>
                                        </ol>
                                    </td>
                                    <td>
                                        <?php
                                        $etatAnnonces = ['1' => 'Actives', '2' => 'Inactives', '3' => 'Retirées'];
                                        foreach ($etatAnnonces as $etat => $label) {
                                            $sql4 = "SELECT COUNT(*) AS nbAnnonces
                                                     FROM annonces
                                                     WHERE NoUtilisateur = '$no' AND Etat = '$etat'";
                                            $resultat4 = $conn->query($sql4);
                                            if ($resultat4 && $annonces = mysqli_fetch_object($resultat4)) {
                                                echo "$label: " . $annonces->nbAnnonces . "<br>";
                                            } else {
                                                echo "$label: 0 <br>";
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>