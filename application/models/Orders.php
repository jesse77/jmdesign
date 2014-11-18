<?php
class Orders extends CI_Model {
    function __construct()
    {
	// Call the Model constructor
	parent::__construct();
    
    }
    function charge( $amount, $email )
    {
	// Set your secret key: remember to change this to your live secret key
	// in production
	// See your keys here https://manage.stripe.com/account
	
	Stripe::setApiKey( STRIPE_API_KEY );

	// Get the credit card details submitted by the form
	$token = $_POST['stripeToken'];
	// Create the charge on Stripe's servers - this will charge the user's
	// card
	try {
	    $charge = Stripe_Charge::create( [
					      "amount"		=> $amount,
					      "currency"	=> "cad",
					      "card"		=> $token,
					      "description"	=> $email ]
					     );
	    return true;
	} catch(Stripe_CardError $e) {
	    echo 'The card has been declined <br />';

	    return $e->getMessage();
	}
    }
}
