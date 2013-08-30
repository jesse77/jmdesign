<?php 
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$phone = $_POST['phone'];
$message = sprintf('From: %s<br />%s<br />%s<br />%s', $email, $name, $phone, $message);
$recipient = "info@jessemartineau.com";
$subject = "Inquery From jessemartineau.com";
$mailheader = "From: $email \r\n";
mail($recipient, $subject, $message, $mailheader) or die("AHHH Something went wrong!");
header("Location: http://jessemartineau.com/success.html");
?>
