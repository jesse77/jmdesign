<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API extends CI_Controller {
    public function get_photos()
    {
	$log			= $this->logging;
	$data['title']		= 'About Me';
	$data['active']		= 'about';
	
	$this->template->load( ['about', 'our-clients'], $data );
    }

    public function get_cart( $test_cart = null )
    {
	$log			= $this->logging;
	
	$browser_cart		= json_decode( $this->input->post( 'cart' ) );
	
	$this->load->model( 'Photos' );

	$cart			= $this->Photos->cart( $browser_cart );

	if( ! $cart )
	    echo '{}';
	else
	    echo json_encode( $cart );

	return true;
    }

}
