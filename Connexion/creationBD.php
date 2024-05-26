<?php
// Crée une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lire le fichier SQL
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

$conn->close();
?>