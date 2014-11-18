<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;
	$data['title']		= 'About Me';
	$data['active']		= 'about';
	
	$this->template->load( ['about', 'our-clients'], $data );
    }
}
