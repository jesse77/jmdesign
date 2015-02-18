<?php 

class Photos extends CI_Model {
  
    function __construct()
    {
	parent::__construct();
	$this->select_query	= "
  SELECT p.*,
         tag_names.tags
    FROM photos p
    LEFT JOIN ( SELECT photo_id,
                  GROUP_CONCAT( t.name ) || '' tags
             FROM photo_has_tag pht
             JOIN tags t
               ON t.id = pht.tag_id
            GROUP BY photo_id )
      AS tag_names
      ON p.id = tag_names.photo_id
        %s
   ORDER BY position desc
";
    }

    function all( $get_inactive = false )
    {
	$log			= $this->logging;
	
	if ( ! $get_inactive )
	    $this->db->where( [ 'active' => 1 ] );

	$where			= ! $get_inactive
	    ? 'WHERE active = 1'
	    : '';
	    
	$select			= sprintf( $this->select_query, $where );
	

	$query			= $this->db->query( $select );
	$result			= $query->result();
	
	$log->trace( print_r( $result, true) );
	$log->info( "Found %s photos in database.", $query->num_rows() );

	$make_tags_array	= function( $value ) {
	    $value->tags	= explode( ',', $value->tags );
	};
	
	array_walk( $result, $make_tags_array );

	return $result;
    }

    function get( $id = null )
    {
	$log			= $this->logging;
	
	if ( is_null( $id ) ) {
	    $log->error( 'id is null; return false' );
	    return false;
	}

	if ( is_array( $id ) ) {
	    $log->debug( 'Found array as id; Calling $this->get_mutliple()' );
	    return $this->get_multiple( $id );
	}
	
	$where			= "WHERE p.id = " . $id;
	
	$select			= sprintf( $this->select_query, $where );
	$query			= $this->db->query( $select );
	$photo			= $query->row();
	
	if( ! $photo ) {
	    $log->error( 'Photo with id %s was not found; return false', $id );
	    return false;
	}

	$photo->tags	= explode( ',', $photo->tags );
	
	$log->debug( 'Found %s tags for image %s', count( $photo->tags ), $id );
	$log->trace( 'Tags: %s', print_r( $photo->tags, true ) );
	return $photo;
    }

    function next_position()
    {
	$log			= $this->logging;
	
	$select			= "SELECT position FROM photos ORDER BY position desc";
	$query			= $this->db->query( $select );
	$result			= $query->row();

	return $result->position+1;
    }

    function get_multiple( $ids )
    {
	$log			= $this->logging;
	
	if ( empty( $ids ) ) {
	    $log->error( '{ids} is empty; return false' );
	    return false;
	}

	if ( ! is_array( $ids ) ) {
	    $log->error( 'Did not find array as {ids}; return false' );
	    return false;
	}
	
	$where			= sprintf( "WHERE p.id IN (%s)", implode( $ids, ',' ) );
	
	$select			= sprintf( $this->select_query, $where );
	$query			= $this->db->query( $select );
	$photos			= $query->result();
	
	$make_tags_array	= function( $value ) {
	    $value->tags	= explode( ',', $value->tags );
	};
	
	array_walk( $photos, $make_tags_array );
	
	$log->debug( 'Found %s images with ids [%s]', count( $query->num_rows ), implode( $ids, ', ' ) );
	return $photos;
    }

    function cart( $data = null )
    {
	$log			= $this->logging;
	$this->load->model( 'Mediums' );
	$this->load->model( 'Featured' );

	if( ! is_array( $data ) ) {
	    $log->error( '{data} was not an array: %s; return false.', print_r( $data, true ) );
	    return false;
	}
	
	if( empty( $data ) ) {
	    $log->debug( "Empty cart; returning false" );
	    return false;
	}

	$log->trace( "Cart data: ", print_r( $data, true ) );
	$items			= array_map( function( $item ) {
		if ( empty( $item->photo_id ) or empty( $item->medium_id ) )
		    return null;

		return [ 'photo'	=> $this->get( $item->photo_id ),
			 'medium'	=> $this->Mediums->get( $item->medium_id )
			 ];
	    }, $data );

	$items			= array_filter( $items );
	$featured		= $this->Featured->get();

	$subtotal		= 0;
	$shipping		= 0;
	$total			= 0;
	
	foreach( $items as $key => $item ) {
	    
	    if( $featured &&
		$item['medium']->id	=== $featured->medium->id
		&& $item['photo']->id	=== $featured->photo->id ) {
		$subtotal		+= (int) ( $featured->price );
		$total			+= (int) ( $featured->price );
		$items[$key]['featured_price']	= $featured->price;
	    }
	    else {
		$subtotal	+= (int) ( $item['medium']->price );
		$total		+= (int) ( $item['medium']->price );
	    }
	    $shipping		+= (int) ( $item['medium']->shipping );
	    $total		+= (int) ( $item['medium']->shipping );
	};
	
	$cart['items']		= $items;
	$cart['total'] 		= $total;
	$cart['shipping']	= $shipping;
	$cart['subtotal'] 	= $subtotal;

	$log->trace( 'Cart: [%s]', print_r( $cart, true ) );
	
	return $cart;
	
    }
    
    function limit( $start = 0, $count = 0 )
    {
	$log			= $this->logging;
	
	$where			= "WHERE active = 1";
	$limit			= sprintf( "LIMIT %s, %s", $start, $count );
	$select_query		= $this->select_query . $limit;
	$select			= sprintf( $select_query, $where );
	$query			= $this->db->query( $select );
	$result			= $query->result();
	
	$make_tags_array	= function( $value ) {
	    $value->tags	= explode( ',', $value->tags );
	};
	
	array_walk( $result, $make_tags_array );
	
	return $result;
    }

    function save( $file = null, $image_info = null )
    {
	$log			= $this->logging;

	if( ! is_array( $file ) ) {
	    $log->error('Bad {file} variable given. Must be an array. Return false.');
	    return false;
	}

	$image_data		= [ 'title'	=> $image_info['title'],
				    'comment'	=> $image_info['comment'],
				    'position'	=> $this->next_position() ];
	
	$log->info('Saving image to database.');
	$this->db->insert( 'photos', $image_data );

	$log->trace( 'Image title:		%s', $image_info['title'] );
	$log->trace( 'Image comment:		%s', $image_info['comment'] );

	$image_id		= $this->db->insert_id();
	if( is_array( $image_info['tags'] ) ) {
	    $this->update_tags( $image_id, $image_info['tags'] );
	}
	
	return $this->Photos->save_image_files( $image_id, $file );
    }

    function update_tags( $id = null, $tags = [] )
    {
	if ( is_null( $id ) ) {
	    $log->error( 'id is null; return false' );
	    return false;
	}

	// Clear old tags
	$this->db->where( [ 'photo_id' => $id ] );
	$this->db->delete( 'photo_has_tag' );

	// Insert new tags.
	foreach( $tags as $key => $tag ) {
	    $this->db->insert( 'photo_has_tag', [ 'photo_id' => $id, 'tag_id' => $tag ] );
	}
    }
    
    /*
     * Returns an image resource to be used with imagejpeg()
     *     {src_img}	- (string) path to image file
     *     {dst_width}	- (int | null) new width for image
     *     {dst_height}	- (int | null) path to image file
    */
    function resize_image( $src_img = null, $dst_width = null, $dst_height = null )
    {
	$log			= $this->logging;

	if( ! is_string( $src_img ) ) {
	    $log->error( '{src_img} is not a string, it is a %s', gettype( $src_img ) );
	    return false;
	}
	
	if( ! file_exists( $src_img ) ) {
	    $log->error( '{src_img} does not exist; return false' );
	    return false;
	}
	
	if( ! is_numeric( $dst_width ) && ! is_null( $dst_width ) ) {
	    $log->error( '{dst_width} is a %s; return false', gettype( $dst_width ) );
	    return false;
	}

	if( ! is_numeric( $dst_height ) && ! is_null( $dst_height ) ) {
	    $log->error( '{dst_height} is a %s; return false', gettype( $dst_height ) );
	    return false;
	}

	$return_output		= true;
	$finfo			= finfo_open( FILEINFO_MIME_TYPE );
	$image_type		= explode( '/', finfo_file( $finfo, $src_img ) )[1];
	finfo_close($finfo);

	$log->debug( 'Source image type: %s', $image_type );
	
	// imagecreatefromjpg is not a function so...
	if ( $image_type == 'jpg' ) {
	    $image_type = 'jpeg';
	    $log->debug( 'Changed image to: %s', $image_type );
	}

	$type_options		= [ 'gif', 'png', 'jpeg' ];

	if ( ! in_array( $image_type, $type_options ) ) {
	    $log->error( 'Invalid image type; Image type given: %s; Supported options: %s',
			 $image_type, implode( $type_options, ', ' ) );
	    return false;
	}

	$src_img		= call_user_func( 'imagecreatefrom' . $image_type, $src_img );
	
	$log->trace1("Source img: %s", print_r( $src_img ) );
	$dst_img		= $src_img;
	    
	if( $dst_width or $dst_height ) {

	    $src_width		= imagesx( $src_img );
	    $src_height		= imagesy( $src_img );

	    if( $dst_width == null )
		$dst_width	= ( $dst_height / $src_height ) * $src_width;
	    if( $dst_height == null )
		$dst_height	= ( $dst_width / $src_width ) * $src_height;

	    $dst_img		= imagecreatetruecolor($dst_width, $dst_height);
	    imagecopyresampled( $dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height );
	    imagedestroy( $src_img );
	}

	$log->trace("Destination image: %s", print_r( $dst_img, true ) );
	return @$dst_img;
    }
    
    function save_image_files( $image_id = null, $image_file = null )
    {
	$log			= $this->logging;

	if( is_null( $image_id ) ) {
	    $log->error( '{image_id} is null; return false' );
	    return false;
	}

	if( ! is_array( $image_file ) ) {
	    $log->error( '{image_file} is not an array; return false' );
	    return false;
	}

	$image_dir		= FCPATH . "img/uploaded/" . $image_id;

	// Create new image directory
	$log->debug( 'Creating directory "%s".', $image_dir, $image_id );
	$log->trace( '	Image uploaded: %s ', print_r( $image_file, true ) );
	$old_umask		= umask( 0 );

	if ( ! is_dir( $image_dir ) ) {
	    mkdir( $image_dir );
	}

	umask( $old_umask );

	// Upload files to image directory
	$config['upload_path']		= $image_dir;
	$config['file_name']		= 'original';
	$config['allowed_types']	= 'gif|jpg|png';

	$original			= $this->resize_image( $image_file['tmp_name'] );
	if( ! $original ) {
	    $log->error( 'Image formatting did not work; return false.' );
	    return false;
	}
	$log->trace( "Original: \n %s", print_r( $original, true ) );

	$width				= imagesx( $original );
	$xsmall				= $this->resize_image( $image_file['tmp_name'], min( 100, $width ) );
	$small				= $this->resize_image( $image_file['tmp_name'], min( 260, $width ) );
	$medium				= $this->resize_image( $image_file['tmp_name'], min( 800, $width ) );
	$large				= $this->resize_image( $image_file['tmp_name'], min( 1500, $width ) );

	imagejpeg( $xsmall, $image_dir	. '/xsmall.jpg' );
	imagejpeg( $small, $image_dir	. '/small.jpg' );
	imagejpeg( $medium, $image_dir	. '/medium.jpg' );
	imagejpeg( $large, $image_dir	. '/large.jpg' );

	return true;
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

	$update_data		= [ 'title'	=> $data['title'],
				    'comment'	=> $data['comment'] ] ;

	$this->db->where( ['id' => $id] );
	$this->db->update( 'photos', $update_data );
	$this->update_tags( $id, $data['tags'] );
    }
    
    function delete( $id = null )
    {
	$log			= $this->logging;

	if( is_null( $id ) ) {
	    $log->error( "Given id is null; return false" );
	    return false;
	}
	
	$this->db->where( 'id', $id );
	$this->db->delete( 'photos' );

	$this->db->where( 'photo_id', $id );
	$this->db->delete( 'photo_has_tag' );

	return true;
    }

    function toggle_active_photo( $id = null )
    {
	$log			= $this->logging;

	if ( is_null( $id ) ) {
	    $log->error( 'No id found... return false', gettype( $src_img ) );
	    return false;
	}

	$photo			= $this->get( $id );

	$this->db->where( [ 'id' => $id ] );
	$this->db->update( 'photos', ['active' => ( ! $photo->active ) ] );
    }

    function change_position( $id = null, $position = null )
    {
	$log			= $this->logging;

	if ( is_null( $id ) ) {
	    $log->error( 'No id found... return false', gettype( $id ) );
	    return false;
	}

	if ( is_null( $position ) ) {
	    $log->error( 'No position found... return false', gettype( $position ) );
	    return false;
	}

	$photo			= $this->get( $id );

	$this->db->where( [ 'id' => $id ] );
	$this->db->update( 'photos', ['position' => $position ] );
    }

    // 
    // Testing Functions
    //
    function test_all()
    {
	$test_string			= "THIS IS FOR TESTING";

	$test_name                      = "<b>Retrieves array</b>";
	$test                           = $this->all();
	$expected                       = 'is_array';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_get()
    {
	$all				= $this->all();
	$test_id			= $all[0]->id;
	$test_array			= [ $all[0]->id, $all[1]->id, $all[2]->id ];

	$test_name                      = "<b>Returns object.</b>";
	$test                           = $this->get( $test_id );
	$expected                       = 'is_object';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>Returns array when {id} is array</b>";
	$test                           = $this->get( $test_array );
	$expected                       = 'is_array';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>FAIL MODE: Search ID is null</b>";
	$test                           = $this->get();
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_get_multiple()
    {
	$all				= $this->all();
	$test_array			= [ $all[0]->id, $all[1]->id, $all[2]->id ];

	$test_name                      = "<b>Returns array when {id} is array</b>";
	$test                           = $this->get( $test_array );
	$expected                       = 'is_array';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>FAIL MODE: Search ID is null</b>";
	$test                           = $this->get();
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_cart()
    {
	$this->load->model( 'Mediums' );
	$photos				= $this->all();
	$mediums			= $this->Mediums->all();
	$cart				= [ (object) [
						      'medium_id'	=> $mediums[0]->id,
						      'photo_id'	=> $photos[0]->id
						      ],
					    (object) [
						      'medium_id'	=> $mediums[0]->id,
						      'photo_id'	=> $photos[1]->id
						      ],
					    (object) [
						      'medium_id'	=> $mediums[1]->id,
						      'photo_id'	=> $photos[2]->id
						      ]
					    ];

	$test_cart			= $this->cart( $cart );

	$test_name                      = "<b>Returns array when given expected browser cart.</b>";
	$test                           = $test_cart;
	$expected                       = 'is_array';
	$this->unit->run( $test, $expected, $test_name );

	
	$test_name                      = "<b>Result contains items array</b>";
	$test                           = $test_cart['items'];
	$expected                       = 'is_array';
	$this->unit->run( $test, $expected, $test_name );

	foreach( [ 'shipping', 'subtotal', 'total'] as $key => $val ) {
	    $test_name                      = "<b>Result contains " . $val . " integer</b>";
	    $test                           = $test_cart[ $val ];
	    $expected                       = 'is_int';
	    $this->unit->run( $test, $expected, $test_name );
	}

	/* Featured Tests */
	$featured			= $this->Featured->get();
	$featured_object		= (object) [ 'medium_id'	=> $featured->medium->id,
						     'photo_id'		=> $featured->photo->id ];
	$featured_cart			= $cart;
	$featured_cart[]		= $featured_object;

	$test_name                      = "<b>When featured photo is in cart, it gets a [featured_price] attribute.</b>";
	$feature_test			= $this->cart( $featured_cart );
	$test				= false;
	foreach( $feature_test['items'] as $item )
	    if( isset( $item['featured_price'] ) )
		$test			= true;
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );
	/* End Featured Tests */
	
	/* Fail Mode tests */
	$fail_types			= [ null,
					    "This will fail",
					    123,
					    (object) [ 'this'=>'will fail' ] ];
	foreach( $fail_types as $key => $val ) {
	    $test_name                      = sprintf( "<b>FAIL MODE: Fails when given {%s} value.</b>", gettype( $val ) );
	    $test                           = $this->cart( $val );
	    $expected                       = 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}
	/* End Fail Mode Tests */


    }
    
    function test_limit()
    {
	$test_count			= count( $this->all() );
	if( $test_count < 5 )
	    echo "<h2>Not enough images uploaded for sufficient test</h2>";

	$test_name                      = "<b>Returns correct number of results</b>";
	$photos				= $this->limit( 0, 5 );
	$test                           = count( $photos ) === 5;
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_save()
    {
	$test_string			= "THIS IS FOR TESTING";
	$file_fail_types		= [ null,
					    "This will fail",
					    [1,2,3],
					    (object) [ 'this'=>'will fail' ] ];

	$test_name                      = "<b>FAIL MODE: Null file name given</b>";
	$test                           = $this->save( null );
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_save_image_files()
    {
	$rand_string			= function( $length = null ) {
	    $chars			= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ /\{}[]-+=!@#$%^&*()~`<>|.,';
	    $string			= substr( str_shuffle( $chars ), 0, $length );
	    return $string;
	};

	$image_types			= [ 'gif', 'jpeg', 'jpg', 'png' ];
	$bad_image_types		= [ 'pdf', 'bmp' ];

	$test_id			= 'test_id';
	$testing_base_path		= FCPATH . "img/testing/test.";
	$uploaded_base_path		= FCPATH . 'img/uploaded/' . $test_id;

	$dummy_upload_data		= function( $file_type = null ) use( $testing_base_path, $rand_string ) {
	    $length			= rand( 1, 50 );
	    $file_path			= $testing_base_path . $file_type;
	    $type			= @finfo_file( finfo_open( FILEINFO_MIME_TYPE ), $file_path );
	    $data			= [ 'name'	=> 'Test_'. $rand_string( $length ),
					    'type'	=> $type || 'image/'.$file_type,
					    'tmp_name'	=> $file_path,
					    'size'	=> @filesize( $file_path ) || rand( 300000, 1000000 ),
					    'error'	=> 0 ];
	    return $data;
	};
	
	foreach( $image_types as $type ) {
	    $test_name			= "<b>Can successfully save ." . $type . " files</b>";
	    
	    $this->save_image_files( $test_id, $dummy_upload_data( $type ) );
	    
	    $test			= file_exists( $uploaded_base_path . '/xsmall.jpg' )
		&& file_exists( $uploaded_base_path . '/small.jpg' )
		&& file_exists( $uploaded_base_path . '/medium.jpg' )
		&& file_exists( $uploaded_base_path . '/large.jpg' );

	    $expected			= 'is_true';
	    $this->unit->run( $test, $expected, $test_name );

	    // Remove the files that were just created.
	    $test_name			= "<b>CLEANED UP previous .". $type ." upload test.";
	    $files			= glob( $uploaded_base_path . '/*'); // get all file names
	    foreach( $files as $file ) {
		if( is_file( $file ) )
		    unlink($file);
	    }
	    $test			= file_exists( $uploaded_base_path . '/xsmall.jpg' )
		|| file_exists( $uploaded_base_path . '/small.jpg' )
		|| file_exists( $uploaded_base_path . '/medium.jpg' )
		|| file_exists( $uploaded_base_path . '/large.jpg' );

	    $expected			= 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	foreach( $bad_image_types as $type ) {
	    $test_name			= "<b>FAIL MODE: Will not save ." . $type . " files</b>";
	    $this->save_image_files( $test_id, $dummy_upload_data( $type ) );
	    $test			= file_exists( $uploaded_base_path . '/xsmall.jpg' )
		|| file_exists( $uploaded_base_path . '/small.jpg' )
		|| file_exists( $uploaded_base_path . '/medium.jpg' )
		|| file_exists( $uploaded_base_path . '/large.jpg' );

	    $expected			= 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	$test_name			= "<b>FAIL MODE: Can not save a file if it doesn't exist.</b>";
	$type				= 'jepg';
	$this->save_image_files( $test_id, $dummy_upload_data( $type ) );
	$test			= file_exists( $uploaded_base_path . '/xsmall.jpg' )
	    || file_exists( $uploaded_base_path . '/small.jpg' )
	    || file_exists( $uploaded_base_path . '/medium.jpg' )
	    || file_exists( $uploaded_base_path . '/large.jpg' );

	$expected			= 'is_false';
	$this->unit->run( $test, $expected, $test_name );
	
    }

    function test_resize_image()
    {
	$src_img_fail_types		= [ null,
					    "This will fail",
					    [1,2,3],
					    (object) [ 'this'=>'will fail' ] ];

	$size_fail_types		= [ 'This will fail',
					    [1,2,3],
					    (object) [ 'this'=>'will fail' ] ];

	$real_img_path			= FCPATH . 'img/logo.png';

	foreach( $src_img_fail_types as $type ) {
	    $print_type			= @( (string) $type )
		? @( (string) $type )
		: gettype($type);

	    $test_name			= "<b>FAIL MODE: Image path is: ". $print_type ."</b>";
	    $test			= $this->resize_image( $type, 100, 100 );
	    $expected			= 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	foreach( $size_fail_types as $type ) {
	    $print_type			= @( (string) $type )
		? @( (string) $type )
		: gettype($type);

	    $test_name			= "<b>FAIL MODE: {dst_width} is: ". $print_type ."</b>";
	    $test			= $this->resize_image( $real_img_path, $type, 100 );
	    $expected			= 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	foreach( $size_fail_types as $type ) {
	    $print_type			= @( (string) $type )
		? @( (string) $type )
		: gettype($type);

	    $test_name			= "<b>FAIL MODE: {dst_height} is: ". $print_type ."</b>";
	    $test			= $this->resize_image( $real_img_path, 100, $type );
	    $expected			= 'is_false';
	    $this->unit->run( $test, $expected, $test_name );
	}

	$test_name                      = "<b>Returns GD resource::: good image path, numeric width, numeric height</b>";
	$resource                       = $this->resize_image( $real_img_path, 100, 100 );
	$test				= get_resource_type( $resource ) === 'gd';
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>Returns GD resource::: good image path, null width, numeric height</b>";
	$resource                       = $this->resize_image( $real_img_path, null, 100 );
	$test				= get_resource_type( $resource ) === 'gd';
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>Returns GD resource::: good image path, numeric width, null height</b>";
	$resource                       = $this->resize_image( $real_img_path, 100, null );
	$test				= get_resource_type( $resource ) === 'gd';
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );
    }

}
