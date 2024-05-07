<!DOCTYPE html>
<?php
$email = $_GET["email"];
?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Confirmation</title>

<body>
    <div class="container col-md-6 jumbotron">
        <h2 class="text-center h1">Confirmation de la récupération du mot de passe</h2>
        <br>
        <br>
        <div class="h2 text-center">
            <?php
            if (isset($email) && $email != "") { ?>
                Votre mot de passe à été envoyé au email <?php echo $email; ?> avec succès <?php
            } else { ?>
                Le message n'a pas été envoyé correctement <?php
            }
            ?>
        </div>
        <br>
        <br>
        <div class="text-center">
            <a href="Connexion.php">Revenir à la page de Connexion</a>
        </div>
    </div>

    <footer>
        <div class="text-center">
            <p>
                <img src="../logoCGG.jpg" alt="" srcset="">
                © Département d'informatique G.-G.
            </p>
        </div>
</body>

</head>