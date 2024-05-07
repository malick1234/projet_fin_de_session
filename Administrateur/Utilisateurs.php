<!DOCTYPE html>
<html lang="en">
<?php

session_start();
if (!isset($_SESSION["ok"])) {
    header('Location: ../Connexion/Connexion.php');
}

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
    <?php require_once "navigationAdmin.php" ?>
    <h1 class="text-center">Affichage des utilisateurs</h1>
    <div class="container-fluid">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Liste des utilisateurs</div>
            <div class="card">
                <table class="table text-center">
                    <tbody>
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
                        <?php

                        require $_SERVER['DOCUMENT_ROOT'] . "ConnexionBD.php";

                        $conn = new mysqli($SERVER, $USER, $PASSWORD, $DATABASE);
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * from utilisateurs";

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

                                $telMaison = $util->NoTelMaison ?? 'N/A';
                                if ($telMaison != 'N/A') {
                                    $telMaison = substr($util->NoTelMaison, 0, -1);
                                }

                                $telTravail = $util->NoTelTravail ?? 'N/A';
                                if ($telTravail != 'N/A') {
                                    $telTravail = substr($util->NoTelTravail, 0, strlen($util->NoTelTravail) - 5);
                                }

                                $telCellulaire = $util->NoTelCellulaire ?? 'N/A';
                                if ($telCellulaire != 'N/A') {
                                    $telCellulaire = substr($util->NoTelCellulaire, 0, -1);
                                }

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
                                            $sql2 = "SELECT Connexion from connexions 
                                    where NoUtilisateur = '$no'
                                    order by Connexion desc
                                    limit 5;";

                                            $resultat2 = $conn->query($sql2);

                                            if ($resultat2) {
                                                while ($connexion = mysqli_fetch_object($resultat2)) {
                                                    echo "<li>" . $connexion->Connexion . "</li>";
                                                }
                                            } else {
                                                echo "Error: " . $sql . "<br>" . $conn->error;
                                            }
                                            ?>
                                        </ol>
                                    </td>
                                    <td>
                                        <ol>
                                            <?php
                                            $sql3 = "SELECT Deconnexion from connexions 
                                    where NoUtilisateur = '$no'
                                    order by Connexion desc
                                    limit 5";

                                            $resultat3 = $conn->query($sql3);

                                            if ($resultat3) {
                                                while ($connexion = mysqli_fetch_object($resultat3)) {
                                                    if (is_null($connexion->Deconnexion)) {
                                                        echo "<li> N/A </li> <br>";
                                                    } else {
                                                        echo "<li>" . $connexion->Deconnexion . "</li>";
                                                    }

                                                }
                                            } else {
                                                echo "Error: " . $sql . "<br>" . $conn->error;
                                            }
                                            ?>
                                        </ol>
                                    </td>
                                    <td>
                                        <?php
                                        for ($i = 1; $i < 4; $i++) {
                                            $sql4 = "SELECT Etat, count(*) as 'nbAnnonces'
                                        from annonces
                                        where NoUtilisateur = '$no' and Etat = '$i'
                                        group by Etat, NoUtilisateur
                                        order by NoUtilisateur";

                                            $resultat4 = $conn->query($sql4);

                                            if (mysqli_num_rows($resultat4) != 0) {
                                                while ($annonces = mysqli_fetch_object($resultat4)) {
                                                    $etat = $annonces->Etat;
                                                    $nbAnnonces = $annonces->nbAnnonces;

                                                    if ($etat == 1) {
                                                        echo "Actives: " . $nbAnnonces . "<br>";
                                                    } else if ($etat == 2) {
                                                        echo "Inactives: " . $nbAnnonces . "<br>";
                                                    } else if ($etat == 3) {
                                                        echo "Retirées: " . $nbAnnonces . "<br>";
                                                    }

                                                }
                                            } else {
                                                switch ($i) {
                                                    case 1:
                                                        echo "Actives: 0 <br>";
                                                        break;
                                                    case 2:
                                                        echo "Inactives: 0 <br>";
                                                        break;
                                                    case 3:
                                                        echo "Retirées: 0 <br>";
                                                        break;
                                                }
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