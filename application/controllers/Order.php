<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;
	$data['title']		= 'Order Your Photos!';
	$data['active']		= 'order';
	if( $this->input->post( 'stripeToken' ) ) {
	    
	    $check		= $this->confirm_order();
	    if( is_string( $check ) ) {
		$data['validation_errors'] = $check;
	    }
	}
	
	$this->template->load( ['order'], $data );

    }

    function confirm_order()
    {
	$log			= $this->logging;

	$this->load->model( 'Orders' );
	$this->load->model( 'Photos' );
	
	$stripe_data		= $this->input->post( 'stripe' );

	$log->trace( "Data sent from browser: \n". print_r( $this->input->post(), true ) );

	$token			= $stripe_data['id'];
	$email			= $stripe_data['email'];
	$token_type		= $this->input->post( 'stripeTokenType' );
	$browser_cart		= json_decode( $this->input->post( 'cart' ) );
	$cart			= $this->Photos->cart( $browser_cart );

	$log->trace1( "Cart: \n". print_r( $cart, true ) );

	$log->debug( "Token: %s", $token );
	$log->debug( "Amount: %s", $cart['total'] );
	$log->debug( "eMail: %s", $email );
	
	$this->Orders->email_admin( $stripe_data, $cart );
	return $this->Orders->charge( $token, $cart['total'], $email );
    }

}
