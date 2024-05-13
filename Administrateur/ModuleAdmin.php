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
  <title>Annonces</title>
</head>

<body>
  <?php require_once "navigationAdmin.php" ?>
  <h1 class="text-center">Affichage de toutes les annonces</h1>
  <div class="container-fluid">
    <div class="grid text-center">
      <span>
        Nombre d'annonces par page
        <select name="AdPerPage" id="AdPerPage" onchange="change()">
          <option value="3" <?php if ($affichageParPage == 3) { ?> selected="selected" <?php } ?>>3</option>
          <option value="6" <?php if ($affichageParPage == 6) { ?> selected="selected" <?php } ?>>6</option>
          <option value="9" <?php if ($affichageParPage == 9) { ?> selected="selected" <?php } ?>>9</option>
          <option value="12" <?php if ($affichageParPage == 12) { ?> selected="selected" <?php } ?>>12</option>
        </select>
      </span>

    </div>
  </div>

  <br><br><br><br>

  <div class="container-fluid">
    <div class="row justify-content-center">
      <!-- Boucle pour afficher les annonces -->
      <?php foreach ($annonces as $annonce): ?>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 text-center">
          <!-- Affichage des détails de l'annonce -->
          <div class="annonce">
            <h2><?php echo $annonce['titre']; ?></h2>
            <p><?php echo $annonce['description']; ?></p>
            <img src="<?php echo $annonce['image']; ?>" alt="Image de l'annonce">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <br><br><br><br>

  <footer>
    <div class="text-center">
      <p>
        <img src="../logoCGG.jpg" alt="" srcset="">
        © Département d'informatique G.-G.
      </p>
    </div>
  </footer>

</body>

<script>
  // JavaScript pour gérer les changements de page, de tri, etc.
  function change() {
    // Récupération des valeurs des filtres
    var adPerPage = document.getElementById("AdPerPage").value;
    var sort = document.getElementById("ddlSorting").value;
    var auteur = document.getElementById("rechAuteur").value;
    var description = document.getElementById("rechDescription").value;
    var cat = document.getElementById("rechCat").value;
    var date1 = document.getElementById("rechParution").value;
    var date2 = document.getElementById("rechParution2").value;

    // Redirection vers la page de résultats avec les paramètres de filtre et de tri
    window.location = "./ModuleAdmin.php?page=1&adpp=" + adPerPage + "&sort=" + sort + 
    "&Auteur=" + auteur + "&Description=" + description + "&Categorie=" + cat + "&date1=" + 
    date1 + "&date2=" + date2;
  }
</script>

</html>