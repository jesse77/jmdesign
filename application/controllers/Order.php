<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
    public function index()
    {
	$log			= $this->logging;
	$data['title']		= 'Buy Photos!';
	$data['active']		= 'order';
	
	$this->template->load( ['order'], $data );
    }
}
