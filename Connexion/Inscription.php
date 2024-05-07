<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <script type="text/javascript">
    function validerInformation() {
      let boolValide = true;
      let docEmail = document.getElementById('txtEmailIns');
      let docEmailConfirm = document.getElementById('txtEmailInsConfirm');
      let docPassword = document.getElementById('txtPassIns');
      let docPasswordConfirm = document.getElementById('txtPassInsConfirm');


      var validRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      var regexMDP = /^[0-9A-Za-z!-`]{5,15}$/

      if (docEmail.value != "" && docEmail.value.match(validRegex)) {
        var strMfEmail = "sValide";
      } else {
        var strMfEmail = "sNonValide";
        boolValide = false;
      }

      if (docEmailConfirm.value != docEmail.value) {
        var strMfEmailConfirm = "sNonValide";
        boolValide = false;
      } else if (docEmailConfirm.value != "") {
        var strMfEmailConfirm = "sValide";
      } else {
        var strMfEmailConfirm = "sNonValide";
        boolValide = false;
      }

      if (docPassword.value != "" && docPassword.value.match(regexMDP)) {
        var strMfPassword = "sValide";
      } else {
        var strMfPassword = "sNonValide";
        boolValide = false;
      }

      if (docPasswordConfirm.value != "") {
        var strMfPasswordConfirm = "sValide";
      } else {
        var strMfPasswordConfirm = "sNonValide";
        boolValide = false;
      }

      if (docPasswordConfirm.value != docPassword.value) {
        var strMfPasswordConfirm = "sNonValide";
        boolValide = false;
      } else if (docPasswordConfirm.value != "") {
        var strMfPasswordConfirm = "sValide";
      }

      if (docPassword.classList.length != 1) {
        docPassword.classList.replace(docPassword.classList[1], strMfPassword);
        docEmail.classList.replace(docEmail.classList[1], strMfEmail);
        docPasswordConfirm.classList.replace(docPasswordConfirm.classList[1], strMfPasswordConfirm);
        docEmailConfirm.classList.replace(docEmailConfirm.classList[1], strMfEmailConfirm);
      } else {
        docPassword.classList.add(strMfPassword);
        docEmail.classList.add(strMfEmail);
        docPasswordConfirm.classList.add(strMfPasswordConfirm);
        docEmailConfirm.classList.add(strMfEmailConfirm);
      }



      if (boolValide == true) {
        document.getElementById('formInscription').submit()
      } else {
        if (docPassword.value == "" || docEmail.value == "" || docPasswordConfirm.value == "" || docEmailConfirm.value == "") {
          alert("Veuillez remplir tout les champs");
        } else if (!docEmail.value.match(validRegex)) {
          alert("Le Email est invalide")
        } else {
          alert("Veuillez confirmer votre mot de passe\nIl doit être entre 5-15 caractère")
        }
      }
    }
  </script>
  <nav class="navbar navbar-expand-md navbar navbar-dark bg-success fixed-top">

    <div class="collapse navbar-collapse" id="navbarCollapse3">
      <ul class="navbar-nav">

      </ul>
      <div class="navbar-nav ml-auto">

        <a href="Inscription.php" class="nav-item nav-link">S'inscrire</a>
      </div>
    </div>
    <a href="Connexion.php" class="navbar-brand">Connexion</a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse3">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>


  <br>
  <div class="container col-md-5 jumbotron">
    <h2 class="text-center">Inscription</h2><br>
    <form id="formInscription" method="post" action="ConfirmInscription.php"
      oninput="password2.setCustomValidity(password2.value != password.value ? &quot;Veuillez entrer votre Mot de passe&quot; : &quot;&quot;)">
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Courriel</label>
          <input type="email" class="form-control" id="txtEmailIns" name="email" required="required">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Veuillez entrer votre Courriel</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Confirmer le Courriel</label>
          <input type="email" class="form-control" id="txtEmailInsConfirm" name="email2" required="required">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Mot de passe</label>
          <input type="password" class="form-control" id="txtPassIns" required="" name="password">
          <div class="valid-feedback">Valide</div>
          <div class="invalid-feedback">Veuillez entrer votre Mot de passe</div>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-12">
          <label>Confirmer le Mot de passe</label>
          <input type="password" class="form-control" id="txtPassInsConfirm" required="" name="password2">
          <div class="valid-feedback">Valide</div>
          <div id="confirmMessage" class="invalid-feedback"></div>
        </div>
      </div>
      <input type="button" value="S'inscrire" class="btn btn-primary col-md-12" id="btnInscription"
        onclick="validerInformation()">
    </form>
  </div>

  <div class="container-fluid">

  </div>
  <footer>
    <div class="text-center">
      <p>
        <img src="../logoCGG.jpg" alt="" srcset="">
        © Département d'informatique G.-G.
      </p>
    </div>


  </footer>


</body>

</html>