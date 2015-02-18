<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;
	$this->load->model( 'Photos' );
	$this->load->model( 'Featured' );
	$data['examples']	= $this->Photos->limit( 0, 6 );
	$data['featured']	= $this->Featured->get();
	$this->template->load( 'home', $data );
    }
}
