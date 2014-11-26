<?php 

class Mediums extends CI_Model {
  
    function __construct()
    {
	parent::__construct();
    }

    function all()
    {
	$log			= $this->logging;
	$select			= "SELECT * FROM mediums";
	$query			= $this->db->query( $select );
	$result			= $query->result();

	$log->trace( print_r( $result, true) );
	$log->info( "Found %s mediums in database.", $query->num_rows() );

	return $result;
    }

    function add( $name = null, $price = null )
    {
	$log			= $this->logging;
	if( is_null( $name ) ) {
	    $log->error( "New medium name not provided; return false" );
	    return false;
	}

	if( ! is_numeric( $price ) ) {
	    $log->error( "New medium price is not numeric; return false" );
	    return false;
	}

	$this->db->insert( 'mediums', ['name' => $name, 'price' => $price] );
    }

    function edit( $id = null, $data = null )
    {
	$log			= $this->logging;

	if( is_null( $id ) ) {
	    $log->error( "Given id is null; return false" );
	    return false;
	}

	if( ! is_array( ( $data ) ) ) {
	    $log->error( "Data provided is not an array; returning false" );
	    return false;
	}

	$update_data		= [ 'title'	=> $data['name'],
				    'comment'	=> $data['price'] ] ;

	$this->db->where( ['id' => $id] );
	$this->db->update( 'photos', $update_data );
    }
    
    function delete( $id = null )
    {
	$log			= $this->logging;

	if( is_null( $id ) ) {
	    $log->error( "Given id is null; return false" );
	    return false;
	}
	
	$this->db->where( 'id', $id );
	$this->db->delete( 'mediums' );

	$this->db->where( 'medium_id', $id );

	return true;
    }
}

