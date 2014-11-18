<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Communities extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;

	$data['active']		= 'communities';
	$data['title']		= 'Social Networks';
	
	$this->template->load( 'communities', $data );
    }
}
