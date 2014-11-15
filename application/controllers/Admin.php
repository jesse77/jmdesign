<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin extends CI_Controller {
    function index()
    {
	$log			= $this->logging;
	$this->photos();
    }

    function photos()
    {
	$this->load->model('Photos');
	$this->load->model('Tags');
	$data			= [];
	$data['photos']		= $this->Photos->all();
	$data['tags']		= $this->Tags->all();
	$this->template->admin( 'admin/photos', $data );
    }
    
    function delete_image( $id ) {
	
    }
    
    // Save image that was uploaded to web.
    //     - Create new directory from sql id
    //       and save original image, thumbnail image,
    //       and display image
    function upload()
    {
	$log			= $this->logging;
	$this->load->model( 'Photos' );

	$file			= $_FILES["file"];
	if( ! $file ) {
	    $log->error( 'Image uploading did not work. No file found to upload. Return false.' );
	    return false;
	}

	$info				= [];
	$info['title']			= $this->input->post( 'title' );
	$info['comment']		= $this->input->post( 'comment' );
	$info['tags']			= $this->input->post( 'tags' );

	$this->Photos->save( $file, $info );

	/* redirect('admin/photos'); */
	return true;
    }

    function tags()
    {
	$this->load->model('Tags');
	$data			= [];
	$data['tags']		= $this->Tags->all();
	$this->template->admin( 'admin/tags', $data );
    }

    function add_tag()
    {
	$this->load->model('Tags');
	$this->Tags->add( $this->input->get_post('name') );
	redirect( 'admin/tags' );
    }

    function edit_tag()
    {
	$this->load->model( 'Tags' );
	$this->Tags->edit( $this->input->post('id'),
			  $this->input->post( 'name' ) );
	redirect( 'admin/tags' );
    }
}
