<?php
// Inclure la classe PHPMailer
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Chemin vers l'autoloader de PHPMailer

$mail = new PHPMailer(true);

try {
    // Paramètres SMTP pour Gmail
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Serveur SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'malickcheick12@gmail.com'; // Votre adresse email Gmail
    $mail->Password = 'Ma@beni@brayan12'; // Votre mot de passe Gmail
    $mail->SMTPSecure = 'tls'; // Utiliser TLS
    $mail->Port = 587; // Port SMTP de Gmail

    // Expéditeur et destinataire
    $mail->setFrom('malickcheick12@gmail.com', 'Malick');
    $mail->addAddress('moussyabel20@gmail.com', 'Abel');

    // Contenu du message
    $mail->isHTML(true);
    $mail->Subject = 'Test';
    $mail->Body = 'C\'est juste un test d\'envoie email.<br>Merci.';

    // Envoyer le message
    $mail->send();
    echo 'L\'email a été envoyé avec succès.';
} catch (Exception $e) {
    echo 'Erreur lors de l\'envoi de l\'email : ', $mail->ErrorInfo;
}
