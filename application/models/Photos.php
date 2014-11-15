<?php 

class Photos extends CI_Model {
  
    function __construct()
    {
	parent::__construct();
    }

    function all()
    {
	$log			= $this->logging;
	$select			= "
SELECT *
  FROM photos p
";
	$query			= $this->db->query( $select );
	$result			= $query->result();

	$photos			= [];
	
	$log->trace( print_r( $result, true) );
	$log->info( "Found %s photos in database.", $query->num_rows() );
	
	foreach( $result as $row ) {
	    $row->tags		= [];
	    $tags_obj		= $this->get_photos_tags( $row->id );
	    foreach( $tags_obj as $tag ) {
		$row->tags[]	= $tag->name;
	    }
	    $photos[]		= $row;
	}

	return (object) $photos;
    }

    function get( $id = null )
    {
	$log			= $this->logging;

	$query			= $this->db->get_where('photos', ['id' => $id] );
	$photo			= $query->row();

	$photo['tags']		= $this->get_photos_tags( $id );
	
	$log->debug( 'Found %s tags for image %s', count( $tags ), $id );
	$log->trace( 'Tags: %s', print_r( $tags, true ) );
	return $tags;
    }

    function save( $file = null, $image_info = null )
    {
	$log			= $this->logging;

	if( ! is_array( $file ) ) {
	    $log->error('No file name given; return false');
	    return false;
	}

	$image_data		= [ 'title'	=> $image_info['title'],
				    'comment'	=> $image_info['comment'] ];
	
	$log->info('Saving image to database.');
	$this->db->insert( 'photos', $image_data );

	$log->trace( 'Image title:				%s', $image_info['title'] );
	$log->trace( 'Image comment:			%s', $image_info['comment'] );

	$image_id		= $this->db->insert_id();
	$this->add_tags( $image_id, $image_info['tags'] );
	
	return $this->Photos->save_image_files( $image_id, $file );
    }

    function get_photos_tags( $id )
    {
	$log			= $this->logging;
	$this->db->select('tags.*');
	$this->db->join('tags', 'tag_id = tags.id' );
	$query			= $this->db->get_where('photo_has_tag', ['photo_id' => $id] );
	$tags			= $query->result();
	$log->debug( 'Found %s tags for image %s', count( $tags ), $id );
	$log->trace( 'Tags: %s', print_r( $tags, true ) );
	return $tags;
    }
    
    function add_tags( $id = null, $tags = [] )
    {
	$previous_tags			= $this->get_photos_tags( $id );
	
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
	return $dst_img;
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
	$thumbnail			= $this->resize_image( $original['full_path'], 200 );
	$display			= $this->resize_image( $original['full_path'], 800 );
	imagejpeg( $thumbnail, $image_dir . '/thumbnail.jpg' );
	imagejpeg( $display, $image_dir . '/display.jpg' );

	return true;
    }
    
    function test_all() {
	$test_string			= "THIS IS FOR TESTING";

	$test_name                      = "<b>Retrieves array</b>";
	$test                           = $this->all();
	$expected                       = 'is_array';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_save() {
	$test_string			= "THIS IS FOR TESTING";

	$test_name                      = "<b>FAIL MODE: Null file name given</b>";
	$test                           = $this->save();
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_get() {
	$test_string			= "THIS IS FOR TESTING";

	$test_name                      = "<b>FAIL MODE: Search ID is null</b>";
	$test                           = $this->get();
	$expected                       = 'is_false';
	$this->unit->run( $test, $expected, $test_name );
    }

    function test_resize_image() {
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
