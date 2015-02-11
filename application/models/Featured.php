<?php 

class Featured extends CI_Model {

    function get()
    {
	$log		= $this->logging;
	$log->debug( 'Get featured image' );
	$this->load->model( 'Photos' );
	$this->load->model( 'Mediums' );

	$query		= $this->db->query( "
  SELECT *
    FROM featured
   ORDER BY id desc" );

	$data		= $query->row();
	
	$featured	= (object) [ 'photo'	=> $this->Photos->get( $data->photo_id ),
				     'medium'	=> $this->Mediums->get( $data->medium_id ),
				     'price'	=> $data->price ];
	return $featured;
    }

    function feature_photo( $photo_id = null, $medium_id = null, $price = null )
    {
	$log		= $this->logging;

	if ( ! is_numeric( $photo_id ) ) {
	    $log->error( '{photo_id} is not numeric; return false.' );
	    return false;
	}

	if ( ! is_numeric( $medium_id ) ) {
	    $log->error( '{medium_id} is not numeric; return false.' );
	    return false;
	}

	if ( ! is_numeric( $price ) ) {
	    $log->error( '{medium_id} is not numeric; return false.' );
	    return false;
	}

	$to_insert	= [ 'photo_id'	=> $photo_id,
			    'medium_id'	=> $medium_id,
			    'price'	=> $price
			    ];
	$check		= $this->db->insert( 'featured', $to_insert );

	if ( $check ) {
	    $log->info( 'Featured photo. Photo id: %s. Medium id: %s. Price: %s', $photo_id, $medium_id, $price );
	}
	else {
	    $log->error( 'OOPS! Curiously enough, insert query did not work.' );
	}
	$log->trace( 'Insert array: \n%s', print_r( $to_insert, true ) );

	return (bool) $check;
    }

    function test_get()
    {
	$test_name                      = "<b>Returns object</b>";
	$test				= $this->get();
	$expected                       = 'is_object';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_feature_photo()
    {
	$this->load->model('Photos');
	$this->load->model('Mediums');
	
	$fail_types			= [ null,
					    "This will fail",
					    [1,2,3],
					    (object) [ 'this'=>'will fail' ] ];
	$photo_id			= 0;
	$medium_id			= 0;

	/* Tests */
	$test_name                      = "<b>{photo_id} is an invalid integer</b>";
	$test                           = $this->feature_photo( $photo_id, $medium_id, 199 );
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );

	foreach( $fail_types as $type ) {
	    $test_name                      = "<b>FAIL MODE: {photo_id} is a ". gettype( $type ) ."</b>";
	    $test                           = $this->feature_photo( $type, $medium_id, 199 );
	    $expected                       = 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	foreach( $fail_types as $type ) {
	    $test_name                      = "<b>FAIL MODE: {medium_id} is a ". gettype( $type ) ."</b>";
	    $test                           = $this->feature_photo( $photo_id, $type, 199 );
	    $expected                       = 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	foreach( $fail_types as $type ) {
	    $test_name                      = "<b>FAIL MODE: {price} is a ". gettype( $type ) ."</b>";
	    $test                           = $this->feature_photo( $photo_id, $medium_id, $type );
	    $expected                       = 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	// Cleanup 
	$this->db->where( 'photo_id', 0 );
	$this->db->or_where( 'medium_id', 0 );
	$this->db->delete( 'featured', [ 'photo_id' => 0 ] );

	$this->db->where( 'photo_id', 0 );
	$this->db->or_where( 'medium_id', 0 );
	$query				= $this->db->get( 'featured' );

	$test_name                      = "<b>TESTS CLEANUP</b>";
	$test                           = (bool) count( $query->result() );
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );

    }
}