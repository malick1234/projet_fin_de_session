<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer-6.8.0/PHPMailer-6.8.0/src/Exception.php';
require 'PHPMailer-6.8.0/PHPMailer-6.8.0/src/PHPMailer.php';
require 'PHPMailer-6.8.0/PHPMailer-6.8.0/src/SMTP.php';

?>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Récupération</title>

<body>
    <?php require_once "navigationPreConnexion.php" ?>
    <br>
    <div class="container col-md-6 jumbotron">
        <h2 class="text-center">Récupération du Mot de Passe</h2>
        <form action="RecupMDP.php" method="POST" id="frmSaisie">
            <div class="has-validation">
                <br>
                <br>
                <div class="input-group mb-3">
                    <input name="txtEmail" id="email" type="text" class="form-control"
                        placeholder="Mettre le email utilisé pour votre identifiant" aria-describedby="basic-addon2"
                        onchange="change()">
                    <span class="input-group-text" id="basic-addon2">@example.com</span>
                </div>
                <div id="erreur" class="p-3 text-danger">
                </div>
                <br>
                <br>

            </div>
            <div class="d-grid gap-2 col-6 mx-auto text-center">
                <input class="btn btn-outline-dark btn-lg" type="button" value="Envoyer" onclick="verify()">
            </div>
        </form>
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

<script>
    function verify() {
        let regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]{2,})*$/
        if (regex.test(document.getElementById("email").value))
            document.getElementById("frmSaisie").submit()
        else {
            document.getElementById("email").className = "form-control is-invalid"
            document.getElementById("erreur").innerHTML = "Le email n'est pas un bon format"
        }
    }
    function change() {
        document.getElementById("email").className = "form-control"
        document.getElementById("erreur").innerHTML = ""
    }
</script>
<?php
if (isset($_POST["txtEmail"])) {
    $email = $_POST["txtEmail"];

    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'robotcupcake420@gmail.com';
    $mail->Password = 'wridpjxrbiywbiqn';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->setFrom('robotcupcake420@gmail.com', 'robotCupcake');

    require $_SERVER['DOCUMENT_ROOT'] . "ConnexionBD.php";
    $cBD = mysqli_connect($SERVER, $USER, $PASSWORD, $DATABASE);
    $query = mysqli_query($cBD, "SELECT * FROM utilisateurs WHERE Courriel='$email'");
    if (mysqli_num_rows($query) == 1) {
        $utilisateur = mysqli_fetch_row($query);
        $mail->addAddress($email, 'recipient');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Récupération du mot de passe';
        $mdp = $utilisateur[2];
        $mail->Body = "
            <html>
            <head>
              <title>Récupération du mot de passe</title>
            </head>
            <body>
              <p>Votre mot de passe : $mdp</p>
            </body>
            </html>
            ";
        $mail->send();
        header("Location: ./ConfirmationEnvoie.php?email=$email");
    } else { // retourner sur l'autre la page d'avant
        ?>
        <script>
            document.getElementById("erreur").innerHTML = "Le email entré n'existe pas"
            document.getElementById("email").className = "form-control is-invalid"
            document.getElementById("email").value = "<?php echo $email ?>"
        </script>
        <?php
    }
}
?>
</head>