<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;
	$data['title']		= 'Contact';
	$this->template->load( ['contact'], $data );
    }
}
