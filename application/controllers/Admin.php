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
	$data['photos']		= $this->Photos->all(true);
	$data['tags']		= $this->Tags->all();
	$this->template->admin( 'admin/photos', $data );
    }

    function photo_json( $id = null )
    {
	$this->load->model('Photos');
	$photo			= $this->Photos->get( $id );
	echo json_encode( $photo );
	return true;
    }
    
    function edit_photo() {
	$this->load->model( 'Photos' );
	
	$data			= [];
	$id			= $this->input->post( 'id' );
	$data['title']		= $this->input->post( 'title' );
	$data['comment']	= $this->input->post( 'comment' );
	$data['tags']		= $this->input->post( 'tags' );
	$this->Photos->edit( $id, $data );
	redirect( 'admin/photos' );
    }
    
    function delete_photo() {
	$this->load->model( 'Photos' );
	$id			= $this->input->post( 'id' );
	echo $id;
	$this->Photos->delete( $id );
	redirect( 'admin/photos' );
    }
    
    function toggle_active_photo( $id = null ) {
	$this->load->model( 'Photos' );

	if ( is_null( $id ) )
	    $id			= $this->input->get_post( 'id' );

	$this->Photos->toggle_active_photo( $id );
	redirect( 'admin/photos' );
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

	$info				= [ 'title'	=> $this->input->post( 'title' ),
					    'comment'	=> $this->input->post( 'comment' ),
					    'tags'	=> $this->input->post( 'tags' ) ];

	$this->Photos->save( $file, $info );

	redirect('admin/photos');
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

    function delete_tag() {
	$this->load->model( 'Tags' );
	$id			= $this->input->post( 'id' );
	$this->Tags->delete( $id );
	redirect( 'admin/tags' );
    }

    function mediums()
    {
	$this->load->model('Mediums');
	$data			= [];
	$data['mediums']	= $this->Mediums->all();
	$this->template->admin( 'admin/mediums', $data );
    }

    function add_medium()
    {
	$this->load->model('Mediums');
	$this->Mediums->add( $this->input->get_post('name'),
			     $this->input->get_post('price'),
			     $this->input->get_post('shipping') );
	redirect( 'admin/mediums' );
    }

    function edit_medium()
    {
	$this->load->model( 'Mediums' );
	$data['name']		= $this->input->post( 'name' );
	$data['price']		= $this->input->post( 'price' );
	$data['shipping']	= $this->input->post( 'shipping' );
	$this->Mediums->edit( $this->input->post('id'), $data );
	redirect( 'admin/mediums' );
    }

    function delete_medium() {
	$this->load->model( 'Mediums' );
	$id			= $this->input->post( 'id' );
	$this->Mediums->delete( $id );
	redirect( 'admin/mediums' );
    }
    
}
