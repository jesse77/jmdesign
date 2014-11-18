<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;
	$data['title']		= 'Contact';
	$data['active']		= 'contact';
	$this->template->load( ['contact'], $data );
    }

    public function email()
    {
	if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['text']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
 
	    // detect & prevent header injections
	    $test = "/(content-type|bcc:|cc:|to:)/i";
	    foreach ( $_POST as $key => $val ) {
		if ( preg_match( $test, $val ) ) {
		    exit;
		}
	    }

	    // PREPARE THE BODY OF THE MESSAGE
	    $message		= '<html><body>';
	    $message		.= '<table rules="all" style="border-color: #666;" cellpadding="10">';
	    $message		.= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($_POST['name']) . "</td></tr>";
	    $message		.= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($_POST['email']) . "</td></tr>";
	    $message		.= "<tr><td><strong>Message:</strong> </td><td>" . htmlentities($_POST['text']) . "</td></tr>";
	    $message		.= "</table>";
	    $message		.= "</body></html>";
			
	    //   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
	    
	    $to			= CONTACT_EMAIL; //Defined in config.php
	    $subject		= $_POST['subject'];
	    $headers		= "From: " . $_POST['email'] . "\r\n";
	    $headers		.= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
	    $headers		.= "MIME-Version: 1.0\r\n";
	    $headers		.= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	    mail($to, $subject, $message, $headers);
	}
    }
}

