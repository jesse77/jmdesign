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
	
	$cart		= json_decode( $this->input->post( 'cart' ) );
	
	$this->load->model( 'Photos' );

	// This part is just for testing the api.

	if( ! $this->input->is_ajax_request() ) {
	    $all	= $this->Photos->all();
	    $cart	= [ (object) [
				      'type'		=> 'print-60',
				      'photo_id'	=> $all[0]->id
				      ],
			    (object) [
				      'type'		=> 'print-60',
				      'photo_id'	=> $all[1]->id
				      ],
			    (object) [
				      'type'		=> 'print-60',
				      'photo_id'	=> $all[2]->id
				      ]
			    ];
	}

	if( ! $cart ) {
	    echo '{}';
	    $log->debug( "Empty cart sent from browser. Sending empty JSON object back" );
	    return true;
	}

	$cart		= array_map( function( $item ) { return $item->photo_id; },
				     $cart );

	$cart		= $this->Photos->get( $cart );
	$log->info( 'Ids in cart: [%s]', print_r( $cart, true ) );

	echo json_encode( $cart );
	return true;
	
    }

}
