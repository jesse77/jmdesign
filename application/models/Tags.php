<?php 

class Tags extends CI_Model {
  
    function __construct()
    {
	parent::__construct();
    }

    function all()
    {
	$log			= $this->logging;
	$select			= "SELECT * FROM tags";
	$query			= $this->db->query( $select );
	$result			= $query->result();

	$log->trace( print_r( $result, true) );
	$log->info( "Found %s tags in database.", $query->num_rows() );

	return $result;
    }

    function add( $name = null )
    {
	$log			= $this->logging;
	if( is_null( $name ) ) {
	    $log->error( "New tag name not provided; return false" );
	    return false;
	}

	$this->db->insert( 'tags', ['name' => $name] );

    }

    function edit( $id = null, $name = null )
    {
	$log			= $this->logging;
	if( is_null( $id ) ) {
	    $log->error( "Given id is null; return false" );
	    return false;
	}

	if( is_null( $name ) ) {
	    $log->error( "New tag name not provided; return false" );
	    return false;
	}

	$this->db->where( ['id' => $id] );
	$this->db->update( 'tags', ['name' => $name] );

    }
}

