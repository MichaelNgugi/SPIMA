<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if(isset($_POST['button']))
{
    //Load POST data from HTML form
    $sender_name = $_POST["sender_name"]; //sender name
    $reply_to_email = $_POST["sender_email"]; //sender email, it will be used in "reply-to" header
    $subject     = $_POST["subject"]; //subject for the email
    $message     = $_POST["message"]; //body of the email

    $mail = new PHPMailer(true);

    try {
	$mail->SMTPDebug = 2;									
	$mail->isSMTP();											
	$mail->Host	 = 'smtp.spima.com;';					
	$mail->SMTPAuth = true;							
	$mail->Username = 'user@gfg.com';				
	$mail->Password = 'password';						
	$mail->SMTPSecure = 'tls';							
	$mail->Port	 = 587;

	$mail->setFrom('from@gfg.com',  $sender_name);		
	$mail->addAddress($reply_to_email);
	$mail->addAddress($reply_to_email, 'Name');
	
	$mail->isHTML(true);								
	$mail->Subject = $subject;
	$mail->Body = $message;
	$mail->AltBody = $message;
    $file_to_attach = 'upload/' . $_FILES['attachment']['name'];
    move_uploaded_file($_FILES['attachment']['tmp_name'], $file_to_attach);

    $mail->AddAttachment($file_to_attach);
	$mail->send();
	echo "Mail has been sent successfully!";
    } catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
