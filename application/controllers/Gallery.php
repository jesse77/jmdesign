<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;

	$this->load->model( 'Photos' );
	$this->load->model( 'Tags' );

	$data['photos']		= $this->Photos->all();
	$data['tags']		= $this->Tags->all();
	
	$this->template->load( 'gallery', $data );
    }
}
