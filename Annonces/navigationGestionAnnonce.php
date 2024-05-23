<nav class="navbar navbar-expand-md navbar-dark bg-dark static-top">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="Annonces.php">
        <div class="bg-secondary p-3">
          <img src="../photos-annonce/retour.png" class="img-fluid" style="width: 25px; height: 30px;" alt="retour">
        </div>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="gestionAnnonces.php">Menu gestion</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="ajoutAnnonce.php">Ajout d'une annonce</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="btnDeconnexion" name="deconnexion" href="Deconnexion.php">Déconnexion</a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto text-white">
    <?php
    if (isset($_SESSION["Nom"]) && isset($_SESSION["Prenom"])) {
      $nom = $_SESSION["Nom"];
      $prenom = $_SESSION["Prenom"];
      echo "$prenom $nom";
    } else
      echo "Anonyme";
    ?>
    <ul>
      <ul>
        Nom de l'équipe: Abel Malick
      </ul>
</nav>