<?php
// Inclure la classe PHPMailer
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

// Créer une instance de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Paramètres SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Entrez votre serveur SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'maximedrogue@gmail.com'; // Entrez votre adresse email
    $mail->Password = 'cuoa ynzr tgzn zdpx'; // Entrez votre mot de passe
    $mail->SMTPSecure = 'tls'; // TLS ou SSL
    $mail->Port = 587; // Port SMTP

    // Expéditeur et destinataire
    $mail->setFrom('maximedrogue@gmail.com', 'Site Annonce Abel&Malick');
    $mail->addAddress('malickcheick12@gmail.com', 'Malick');

    // Contenu du message
    $mail->isHTML(true);
    $mail->Subject = 'Test';
    $mail->Body    = 'Je voulais juste voir si sa marchais.';
    $mail->AltBody = 'Merde alors, sa marche.';

    // Envoyer le message
    $mail->send();
    echo 'Le message a été envoyé avec succès';
} catch (Exception $e) {
    echo 'Erreur lors de l\'envoi du message : ', $mail->ErrorInfo;
}
?>
