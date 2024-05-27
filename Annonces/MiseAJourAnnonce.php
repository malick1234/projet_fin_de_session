<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mise à jour d'une annonce</title>
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
    <?php
    require_once "navigationGestionAnnonce.php";
    require_once "ConnexionBD.php";
    session_start();
    if (!isset($_SESSION["ok"])) {
        header('Location: ../Connexion/Connexion.php');
    }
    
    $cBD = mysqli_connect($servername, $username, $password, $dbname);
    $strEmail = $_SESSION["courriel"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];

            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));
            $extensions = ['jpg', 'png', 'jpeg', 'gif'];
            $maxSize = 400000;

            if (in_array($extension, $extensions) && $size <= $maxSize) {
                $uniqueName = uniqid('', true);
                $file = $uniqueName . "." . $extension;
                move_uploaded_file($tmpName, '../photos-annonce/' . $file);
                $image = $file;
            } else {
                $image = $_POST['existingImage'];
            }
        } else {
            $image = $_POST['existingImage'];
        }

        $annonceId = (int) $_POST['annonceId'];
        $categorie = strip_tags($_POST['categories']);
        $desA = strip_tags($_POST['txtDescriptionA']);
        $desC = strip_tags($_POST['txtDescriptionC']);
        $prix = (float) strip_tags($_POST['txtPrix']);
        $etat = (int) strip_tags($_POST['etat']);

        $date = date("Y-m-d H:i:s");

        $query = mysqli_query($cBD, "UPDATE annonces SET Categorie='$categorie', DescriptionAbregee='$desA', 
            DescriptionComplete='$desC', Prix='$prix', Photo='$image', MiseAJour='$date', Etat='$etat' 
            WHERE NoAnnonce='$annonceId'");

        header('Location: gestionAnnonces.php');
    } elseif (isset($_GET['id'])) {
        $annonceId = (int) $_GET['id'];
        $queryAnnonce = mysqli_query($cBD, "SELECT * FROM annonces WHERE NoAnnonce='$annonceId'");
        $rowAnnonce = mysqli_fetch_assoc($queryAnnonce);

        if (!$rowAnnonce) {
            echo "Annonce non trouvée.";
            exit();
        }

        $queryCategories = mysqli_query($cBD, "SELECT * FROM categories");
    } else {
        echo "<b>ID D'ANNONCE MANQUANT!!! <br/> VEUILLEZ SÉLECTIONNER UNE ANNONCE EXISTANTE.</b>";
        exit();
    }
    ?>

    <br>
    <div class="container col-md-10 jumbotron">
        <h2 class="text-center">Mise à jour d'une annonce</h2><br>
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
                        while ($categories = mysqli_fetch_assoc($queryCategories)) {
                            $selected = $categories['NoCategorie'] == $rowAnnonce['Categorie'] ? 'selected' : '';
                            echo "<option value='{$categories['NoCategorie']}' $selected>{$categories['Description']}</option>";
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
                        required="required" value="<?= $rowAnnonce['DescriptionAbregee'] ?>">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Description abrégées invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Description complète</label>
                    <input type="text" class="form-control" id="txtDescriptionC" name="txtDescriptionC"
                        required="required" value="<?= $rowAnnonce['DescriptionComplete'] ?>">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Description complète invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Prix</label>
                    <input type="text" class="form-control" id="txtPrix" name="txtPrix" required="required"
                        value="<?= $rowAnnonce['Prix'] ?>">
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">Prix invalide</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Televerser une nouvelle photo de l'article (optionnel)</label>
                    <div class="form-group">
                        <label for="fileToUpload">Sélectionnez une image à téléverser :</label>
                        <input type="file" class="form-control-file" id="file" name="file">
                        <input type="hidden" name="existingImage" value="<?= $rowAnnonce['Photo'] ?>">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>État</label>
                    <select name="etat" required="required">
                        <option value="1" <?= $rowAnnonce['Etat'] == 1 ? 'selected' : '' ?>>Actif</option>
                        <option value="2" <?= $rowAnnonce['Etat'] == 2 ? 'selected' : '' ?>>Inactif</option>
                    </select>
                    <div class="valid-feedback">Valide</div>
                    <div class="invalid-feedback">État invalide</div>
                </div>
            </div>
            <input type="hidden" id="annonceId" name="annonceId" value="<?= $annonceId ?>">
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