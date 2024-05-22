<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajout d'une annonce</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <script type="text/javascript">
        function validerInformation() {
            let boolValide = true;
            let docPrix = document.getElementById('txtPrix');
            let docDesA = document.getElementById('txtDescriptionA');
            let docDesC = document.getElementById('txtDescriptionC');

            if (isDecimal(parseFloat(docPrix.value))) {
                var strPrix = "sValide";
            } else {
                var strPrix = "sNonValide";
                boolValide = false;
            }

            if (docDesA.value != "") {
                var strDesA = "sValide";
            } else {
                var strDesA = "sNonValide";
                boolValide = false;
            }

            if (docDesC.value != "") {
                var strDesC = "sValide";
            } else {
                var strDesC = "sNonValide";
                boolValide = false;
            }


            if (docPrix.classList.length != 1) {
                docPrix.classList.replace(docPrix.classList[1], strPrix);
                docDesA.classList.replace(docPrix.classList[1], strDesA);
                docDesC.classList.replace(docPrix.classList[1], strDesC);
            } else {
                docPrix.classList.add(strPrix);
                docDesA.classList.add(strDesA);
                docDesC.classList.add(strDesC);
            }

            if (boolValide == true) {
                document.getElementById('formAjoutAnnonce').submit()
            }
        }

        function isDecimal(value) {
            return typeof value === 'number' && !Number.isNaN(value) && value % 1 !== 0;
        }
    </script>
    <?php require_once "navigationGestionAnnonce.php";
    require_once "ConnexionBD.php";
    session_start();

    $cBD = mysqli_connect($servername, $username, $password, $dbname);
    $strEmail = $_SESSION["courriel"];

    if (isset($_POST)) {
        if (
            isset($_FILES['file'])
        ) {
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];
            $width = imagesx($sourceImage);

            $query = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel='$strEmail'");
            $row = mysqli_fetch_assoc($query);
            $numUtilisateur = $row['NoUtilisateur'];
            $categorie = strip_tags($_POST['categories']);
            $desA = strip_tags($_POST['txtDescriptionA']);
            $desC = strip_tags($_POST['txtDescriptionC']);
            $prix = (float)strip_tags($_POST['txtPrix']);
            $etat = (int)strip_tags($_POST['etat']);
            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));

            $extensions = ['jpg', 'png', 'jpeg', 'gif'];
            //Taille max que l'on accepte
            $maxSize = 600000;

            if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){

                $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                $file = $uniqueName.".".$extension;
                //$file = 5f586bf96dcd38.73540086.jpg

                $widthImg = imagesx($sourceImage);
                if($widthImg <= 600){
                    move_uploaded_file($tmpName, '../photos-annonce/'.$file);
                    createThumbnail('../photos-annonce/'.$file, '../photos-annonce/' . 'thumb_' . basename($file), 144);
                    date_default_timezone_set("America/New_York");
                    
                    $date = date("Y-m-d H:i:s");
                    $image = 'thumb_'.$file;
    
                    $query = mysqli_query($cBD, "INSERT INTO annonces (NoUtilisateur, Parution, Categorie, 
                    DescriptionAbregee, DescriptionComplete, Prix, Photo, MiseAJour, Etat) 
                  VALUES ('$numUtilisateur', '$date', '$categorie', '$desA', '$desC', '$prix', '$image', '$date', '$etat')");
                    header('Location: gestionAnnonces.php');
                }
                else{
                    ?>
                    <script type="text/javascript">
                        alert("Erreur! un problème est survenue avec votre image. Vérifier que la taille est inférieur à 601 pixel.");
                  window.location.href = 'http://localhost/projet_fin_de_session/Annonces/ajoutAnnonce.php';
                    </script>
                    <?php
                }
            }
            else{
                ?>
                <script type="text/javascript">
                    alert("Erreur! un problème est survenue avec votre image.");
              window.location.href = 'http://localhost/projet_fin_de_session/Annonces/ajoutAnnonce.php';
                </script>
                <?php
            }
        }
        else {
            $query = mysqli_query($cBD, "SELECT * FROM categories");
        }
    } else {
        $query = mysqli_query($cBD, "SELECT * FROM categories");
    }
    // Fonction pour créer une vignette
    function createThumbnail($src, $dest, $desiredWidth) {
        $sourceImage = imagecreatefromjpeg($src);
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        // Calculer la nouvelle hauteur
        $desiredHeight = floor($height * ($desiredWidth / $width));

        // Créer une nouvelle image temporaire
        $virtualImage = imagecreatetruecolor($desiredWidth, $desiredHeight);

        // Copier la source dans l'image temporaire
        imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $width, $height);

        // Sauvegarder la vignette
        imagejpeg($virtualImage, $dest);
    }
    ?>
    <br>
    <div class="container col-md-10 jumbotron">
        <h2 class="text-center">Ajout d'une annonce</h2><br>
        <form id="formAjoutAnnonce" method="post" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <h2 class="text-center"><?= $strEmail ?></h2>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Catégories</label>
                    <select name="categories" required="required">
                        <?php
                        while ($categories = mysqli_fetch_assoc($query)) {
                            if ($categories['NoCategorie'] == 1) {
                                ?>
                                <option value="<?= $categories['NoCategorie'] ?>" selected><?= $categories['Description'] ?>
                                </option>
                                <?php
                            } else {
                                ?>
                                <option value="<?= $categories['NoCategorie'] ?>"><?= $categories['Description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Catégories invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Description abrégées</label>
                    <input type="text" class="form-control" id="txtDescriptionA" name="txtDescriptionA"
                        required="required">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Description abrégées invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Description complète</label>
                    <input type="text" class="form-control" id="txtDescriptionC" name="txtDescriptionC"
                        required="required">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Description complète invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Prix</label>
                    <input type="text" class="form-control" id="txtPrix" name="txtPrix" required="required">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Prix invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Televerser une photo de l'article</label>
                    <div class="form-group">
                        <label for="fileToUpload">Sélectionnez une image à téléverser :</label>
                        <input type="file" class="form-control-file" id="file" name="file" required>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>État</label>
                    <select name="etat" required="required">
                        <option value="1" selected>Actif</option>
                        <option value="2">Inactif</option>
                    </select>
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">État invalide</div>
                </div>
            </div>
            <input type="button" value="Valider" class="btn btn-primary col-md-12" id="btnValider"
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