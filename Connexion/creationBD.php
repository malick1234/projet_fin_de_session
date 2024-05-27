<?php
require_once "ConnexionBD.php";
require_once "classe-fichier-2024-03-19.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_tables = file_get_contents("tables.sql");

// Diviser les requêtes multiples
$sql_array = explode(";", $sql_tables);

foreach ($sql_array as $sql) {
    if (trim($sql) != "") {
        if ($conn->query($sql) === TRUE) {
            echo "Requête exécutée avec succès: " . $sql . "<br>";
        } else {
            echo "Erreur lors de l'exécution de la requête: " . $sql . "<br>" . $conn->error . "<br>";
        }
    }
}

$csvFile = fopen("utilisateurs.csv", "r");
if ($csvFile !== FALSE) {
    fgetcsv($csvFile); // Ignorer la ligne d'en-tête
    while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
        $courriel = $data[0];
        $motDePasse = $data[1];
        $creation = $data[2];
        $nbConnexions = (int) $data[3];
        $statut = (int) $data[4];
        $noEmpl = (int) $data[5];
        $nom = $data[6];
        $prenom = $data[7];
        $noTelMaison = $data[8];
        $noTelTravail = $data[9];
        $noTelCellulaire = $data[10];
        $autresInfos = $data[11];

        $check_sql = "SELECT COUNT(*) as count FROM utilisateurs WHERE Courriel = '$courriel'";
        $result = $conn->query($check_sql);
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo "Erreur: l'utilisateur avec le courriel $courriel existe déjà.<br>";
            continue;
        }

        $sql = "INSERT INTO utilisateurs (Courriel, MotDePasse, Creation, NbConnexions, Statut, NoEmpl, Nom, Prenom, NoTelMaison, NoTelTravail, NoTelCellulaire, AutresInfos) 
                VALUES ('$courriel', '$motDePasse', '$creation', '$nbConnexions', '$statut', '$noEmpl', '$nom', '$prenom', '$noTelMaison', '$noTelTravail', '$noTelCellulaire', '$autresInfos')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Utilisateur inséré avec succès: " . $courriel . "<br>";
        } else {
            echo "Erreur lors de l'insertion de l'utilisateur: " . $courriel . "<br>" . $conn->error . "<br>";
        }
    }
    fclose($csvFile);
}
$csvFile = fopen("annonces.csv", "r");
if ($csvFile !== FALSE) {
    fgetcsv($csvFile);
    while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
        $noUtilisateur = $data[0];
        $parution = $data[1];
        $categorie = $data[2];
        $descriptionAbregee = $data[3];
        $descriptionComplete = $data[4];
        $prix = $data[5];
        $photo = $data[6];
        $etat = $data[7];

        $sql = "INSERT INTO annonces (NoUtilisateur, Parution, Categorie, DescriptionAbregee, DescriptionComplete, Prix, Photo, Etat) 
                VALUES ('$noUtilisateur', '$parution', '$categorie', '$descriptionAbregee', '$descriptionComplete', '$prix', '$photo', '$etat')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Annonce insérée avec succès: " . $descriptionAbregee . "<br>";
        } else {
            echo "Erreur lors de l'insertion de l'annonce: " . $descriptionAbregee . "<br>" . $conn->error . "<br>";
        }
    }
    fclose($csvFile);
}

$conn->close();
?>
