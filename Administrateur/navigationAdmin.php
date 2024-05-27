<nav class="navbar navbar-expand-md navbar-dark bg-dark static-top">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="ModuleAdmin.php">Affichage de toutes les annonces</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="Utilisateurs.php">Affichage de tous les utilisateurs</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="NettoyageBD.php">Nettoyage de la base de données</a>
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