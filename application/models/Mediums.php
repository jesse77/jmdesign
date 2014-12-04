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
	$log->debug( "Found %s mediums in database.", $query->num_rows() );

	return $result;
    }

    function get( $id = null )
    {
	$log			= $this->logging;

	if( empty( $id ) ) {
	    $log->error( 'Empty id given' );
	    return false;
	}
	
	$select			= "
 SELECT *
   FROM mediums m
  WHERE id = " . $id;
	$query			= $this->db->query( $select );
	$medium			= $query->row();

	
	
	$log->trace( print_r( $medium, true) );

	return $medium;
    }

    function add( $name = null, $price = null, $shipping = 0 )
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

	$this->db->insert( 'mediums', [ 'name'		=> $name,
					'price'		=> $price * 100,
					'shipping'	=> $shipping * 100] );
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

	$update_data		= [ 'name'	=> $data['name'],
				    'price'	=> $data['price'] * 100,
				    'shipping'	=> $data['shipping'] * 100
				    ];

	$this->db->where( ['id' => $id] );
	$this->db->update( 'mediums', $update_data );
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

