<?php

require_once("PHPMailer/PHPMailerAutoload.php"); //including PHPMailerAutoload
require_once("core/FeedbackNow.php");			 //including FeedbackNow php class

$config  = array("imagesDirectory"=> $_SERVER["DOCUMENT_ROOT"] . "/feedback/uploads/"); //directory that will store the images of the feedback
try{
	$FeedbackNow = FeedbackNow::createSingleton($config);	//instantiating singleton object of class feedbackNow

	$mail             = $FeedbackNow->phpmailer;			//instance of PHPMailer

	$mail->isSMTP();                                      	// Set mailer to use SMTP
	$mail->Host       = 'smtp.gmail.com';  					// Specify main and backup SMTP servers
	$mail->SMTPAuth   = true;                               // Enable SMTP authentication
	$mail->Username   = 'jbusquet@gestinet.com';           // SMTP username
	$mail->Password   = 'criptek2014';                       // SMTP password
	$mail->SMTPSecure = 'tls';                            	// Enable TLS encryption, `ssl` also accepted
	$mail->Port       = 587;                                // TCP port to connect to


	$mail->CharSet = 'UTF-8';
	$mail->From     = "jbusquet@gestinet.com";						
	$mail->FromName = "Supermatic";						
	$mail->addReplyTo($FeedbackNow->email);

	$mail->addAddress("info+supermatic@gestinet.com");
	$mail->addCC($FeedbackNow->email);						//Add a "CC" address.

	$status = $mail->send();

	print json_encode(array( "status"=> $status ));

} catch (Exception $e) {   	 
 	 print json_encode(array( "status"=>false, "message"=> $e->getMessage() ));
}