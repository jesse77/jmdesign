<?php
class Orders extends CI_Model {
    function __construct()
    {
	// Call the Model constructor
	parent::__construct();
    
    }
    
    function charge( $token, $amount, $email )
    {
	$log			= $this->logging;
	// Set your secret key: remember to change this to your live secret key
	// in production
	// See your keys here https://manage.stripe.com/account

	$log->debug( "Stripe API Key being used: %s", STRIPE_API_KEY );
	$log->trace( "	Application Environment: %s", ENVIRONMENT );
	require_once( APPPATH.'libraries/Stripe.php' );
	Stripe::setApiKey( STRIPE_API_KEY );

	// Create the charge on Stripe's servers - this will charge the user's
	// card
	try {
	    $charge		= Stripe_Charge::create( [
							  "amount"	=> $amount,
							  "currency"	=> "cad",
							  "card"	=> $token,
							  "description"	=> $email
							  ] );

	    return true;
	} catch( Stripe_CardError $e ) {
	    echo 'The card has been declined <br />';

	    return $e->getMessage();
	}
    }

    function record_order( $shipping_json = null, $cart_json = null )
    {
	$log			= $this->logging;
	
	if( ! is_string( $shipping_json ) ) { 
	    $log->error( 'Shipping json given is not a string.' );
	}
	
	if( ! is_string( $cart_json ) ) { 
	    $log->error( 'Cart json given is not a string.' );
	}
	
	$this->db->insert( 'orders', ['shipping_json'	=> $shipping_json,
				      'cart_json'	=> $cart_json ] );
	return true;
    }

    function email_admin( $order = null, $cart = null, $test_page = false )
    {
	$log			= $this->logging;

	$this->load->library( 'email' );

	$log->info( "Order data to be a checkin out:\n %s", print_r( $order, true ) );

	$this->email->from( $order['email'], $order['card']['name'] );
	$this->email->to( CONTACT_EMAIL );
	$this->email->bcc( 'travis@mottershead.biz' );

	$this->email->subject( 'Somebody Bought Something!' );

	$data['order']		= $order;
	$data['cart']		= $cart;

	if( $test_page ){
	    $this->load->view( 'emails/admin', $data );
	    return true;
	}

	$message		= $this->load->view( 'emails/admin', $data, true );

	$this->email->message( $message );

	$this->email->send();	
    }

    function new_customer( $stripe_token = null, $email = null )
    {
	$log			= $this->logging;
	if( empty( $stripe_token ) ) {
	    $log->error( 'No stripe_token number given; returning false.' );
	    return false;
	}

	$this->load->helper( 'email' );

	if( ! valid_email( $email ) ) {
	    $log->error( 'eMail is not a valid email returning false.' );
	    return false;
	}

	// Create a Customer
	$customer		= Stripe_Customer::create( [ "card" => $stripe_token,
					       "description" => $email
					       ] );
    }

    function test_new_customer()
    {
	$test_name                      = "<b>FAIL MODE: NULL stripe token</b>";
	$test				= $this->new_customer( null, "test@testing.com" );
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>FAIL MODE: Invalid email.</b>";
	$test				= $this->new_customer( "TEST_TOKEN", "testtesting.com" );
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }
    
}
