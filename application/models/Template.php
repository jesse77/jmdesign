<?php 

class Template extends CI_Model {
  
    function __construct()
    {
	parent::__construct();
    }

    function load( $view, $data = [] )
    {
	$log				= $this->logging;
	$data['active']			= isset( $data['active'] )
	    ? $data['active']
	    : 'home';
	$d				= [ 'data'	=> $data,
					    'view'	=> $view ];
	$log->info( 'Loading page with data: \n %s', print_r( $d, true ) );
	$this->load->view( 'template',	$d );
    }

    function admin( $view, $data = [] )
    {
	$log				= $this->logging;
	$d				= [ 'data'	=> $data,
					    'view'	=> $view ];

	$this->load->view( 'admin/template',	$d );
    }
}
