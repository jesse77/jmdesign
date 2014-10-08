<?php 

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$phone = $_POST['phone'];

$recipient = "info@jessemartineau.com";
$subject = "Inquery From jessemartineau.com";
$message = sprintf('From: %s<br />%s<br />%s<br />%s', $email, $name, $phone, $message);

$headers  = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
$headers .= sprintf( "From: %s\n", $email );

mail( $recipient, $subject, $message, $headers ) or die("AHHH Something went wrong!");
header("Location: http://jessemartineau.com/success.html");
?>
