<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Unit_test extends CI_Unit_test {

    public function __construct()
    {
	parent::__construct();
    }

    function reset()
    {
	$this->results= array();
    }
}
