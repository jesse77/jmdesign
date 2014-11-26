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
	$data['title']		= 'Prepare to be AMAZED!';
	$data['active']		= 'gallery';
	
	$this->template->load( ['modals/add-to-cart', 'gallery'], $data );
    }
}
