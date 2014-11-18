<?php 

class Photos extends CI_Model {
  
    function __construct()
    {
	parent::__construct();
	$this->select_query	= "
  SELECT *
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
   ORDER BY timestamp desc
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
	$where			= "WHERE id = " . $id;
	$select			= sprintf( $this->select_query, $where );
	$query			= $this->db->query( $select );
	$photo			= $query->row();

	$photo->tags	= explode( ',', $photo->tags );
	
	$log->debug( 'Found %s tags for image %s', count( $photo->tags ), $id );
	$log->trace( 'Tags: %s', print_r( $photo->tags, true ) );
	return $photo;
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
				    'comment'	=> $image_info['comment'] ];
	
	$log->info('Saving image to database.');
	$this->db->insert( 'photos', $image_data );

	$log->trace( 'Image title:				%s', $image_info['title'] );
	$log->trace( 'Image comment:			%s', $image_info['comment'] );

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

	$return_output		= true;

	$image_type		= pathinfo( $src_img, PATHINFO_EXTENSION );

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
	$old_umask		= umask(0);
	mkdir( $image_dir );
	umask($old_umask);

	// Upload files to image directory
	$config['upload_path']		= $image_dir;
	$config['file_name']		= 'original';
	$config['allowed_types']	= 'gif|jpg|png';
	$this->load->library('upload', $config);

	if( ! $this->upload->do_upload('file') ) {
	    $log->error( 'Image upload failed.' );
	    return false;
	}

	$original			= $this->upload->data();
	$xsmall				= $this->resize_image( $original['full_path'], 100 );
	$small				= $this->resize_image( $original['full_path'], 260 );
	$medium				= $this->resize_image( $original['full_path'], 800 );
	$large				= $this->resize_image( $original['full_path'], min( 1500, $original['image_width'] ) );

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
	$test_id			= $this->all()[0]->id;

	$test_name                      = "<b>Returns object.</b>";
	$test                           = $this->get( $test_id );
	$expected                       = 'is_object';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>FAIL MODE: Search ID is null</b>";
	$test                           = $this->get();
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
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

	$test_name                      = "<b>FAIL MODE: Null file name given</b>";
	$test                           = $this->save();
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_resize_image()
    {
	$test_name                      = "<b>Returns GD resource</b>";
	$resource                       = $this->resize_image( FCPATH . 'img/logo.png', 100, 100 );
	$test				= get_resource_type( $resource ) === 'gd';
	$expected                       = 'is_true';
	$this->unit->run( $test, $expected, $test_name );

	$test_name                      = "<b>FAIL MODE: Image path is null</b>";
	$test                           = $this->resize_image( null, 100, 100 );
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

}
