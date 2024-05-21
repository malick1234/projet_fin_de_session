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
            let docDesA = document.getElementById('textDescriptionA');
            let docDesC = document.getElementById('textDescriptionC');

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
                docPrix.classList.add(strDesA);
                docPrix.classList.add(strDesC);
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
            isset($_POST['txtNom']) && !empty($_POST['txtNom'])
            && isset($_POST['txtPrenom']) && !empty($_POST['txtPrenom'])
        ) {
 

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $targetDir = "photos-annonce/";
                $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                
                // Vérifiez si le fichier est une image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "Le fichier est une image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "Le fichier n'est pas une image.";
                    $uploadOk = 0;
                }
            
                // Vérifiez si le fichier existe déjà
                if (file_exists($targetFile)) {
                    echo "Désolé, le fichier existe déjà.";
                    $uploadOk = 0;
                }
            
                // Limitez la taille du fichier
                if ($_FILES["fileToUpload"]["size"] > 500000) {
                    echo "Désolé, votre fichier est trop grand.";
                    $uploadOk = 0;
                }
            
                // Limitez les formats de fichier autorisés
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                    $uploadOk = 0;
                }
            
                // Vérifiez si $uploadOk est mis à 0 par une erreur
                if ($uploadOk == 0) {
                    echo "Désolé, votre fichier n'a pas été téléversé.";
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                        echo "Le fichier " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " a été téléversé.";
                        // Créez la vignette
                        createThumbnail($targetFile, $targetDir . 'thumb_' . basename($_FILES["fileToUpload"]["name"]), 100);
                    } else {
                        echo "Désolé, une erreur s'est produite lors du téléversement de votre fichier.";
                    }
                }
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

            date_default_timezone_set("America/New_York");
            $date = date("Y-m-d H:i:s");

            $query = mysqli_query($cBD, "INSERT INTO annonces (Courriel, MotDePasse, Creation, NbConnexions, Statut) 
      VALUES ('$strEmail', '$strPassword', '$date', 0, 9)");
            header('Location: gestionAnnonces.php');
        } else {
            $query = mysqli_query($cBD, "SELECT * FROM categories");
        }
    } else {
        $query = mysqli_query($cBD, "SELECT * FROM categories");
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
                    <input type="text" class="form-control" id="textDescriptionA" name="textDescriptionA"
                    required="required">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Description abrégées invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Description complète</label>
                    <input type="text" class="form-control" id="textDescriptionC" name="textDescriptionC"
                    required="required">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Description complète invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Prix</label>
                    <input type="text" class="form-control" id="txtPrix" name="txtPrix"
                    required="required">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Prix invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Televerser une photo de l'article</label>
                        <div class="form-group">
                            <label for="fileToUpload">Sélectionnez une image à téléverser :</label>
                            <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload" required>
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