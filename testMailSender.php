<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
	$mail->SMTPDebug = 2;									 
	$mail->isSMTP();										 
	$mail->Host	 = 'smtp.gfg.com;';				 
	$mail->SMTPAuth = true;							 
	$mail->Username = 'malickcheick12@gmail.com';				 
	$mail->Password = 'password12';					 
	$mail->SMTPSecure = 'tls';							 
	$mail->Port	 = 587; 

	$mail->setFrom('malickcheick12@gmail.com', 'Malick');		 
	$mail->addAddress('malickcheick12@gmail.com');
	$mail->addAddress('malickcheick12@gmail.com', 'Elhaydja');
	
	$mail->isHTML(true);								 
	$mail->Subject = 'Hello world!!';
	$mail->Body = 'Je ne suis point la mauvaise personne et je vais vous donner des conseils. <b>bold</b> ';
	$mail->AltBody = 'Body in plain text for non-HTML mail clients';
	$mail->send();
	echo "Mail has been sent successfully!";
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
