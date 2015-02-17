<?php 
class Testing extends CI_Controller {

    function __construct()
    {
	error_reporting( E_ERROR );
	parent::__construct();
	error_reporting( E_ALL );

	$log= $this->logging;

	if ( ENVIRONMENT == 'production' ) {
	    echo 'Can not test in production environment.';
	    $log->error( 'Can not test in production environment.' );
	    exit;
	}
	$this->load->library( 'unit_test' );
	$this->load->helpers( 'html' );
    }


    function index( $func = null )
    {
	$log= $this->logging;

	$models		= array( 'photos', 'orders', 'featured' );
	$tests		= array();

	foreach( $models as $model ) {
	    $tests	= array_merge( $tests,
				 $this->run_tests_in_model( $model, $func ) );
	}
	$this->print_report( $models, $tests );
    }

    function example_colors()
    {
	$this->load->model( 'CLI_Colors', 'color' );

	$this->color->examples();
    }

    function model( $model = null, $func = null )
    {
	$log= $this->logging;

	$tests= $this->run_tests_in_model( $model, $func );

	$this->print_report( array( $model ), $tests );
    }

    function run_tests_in_model( $model, $func = null )
    {
	$log= $this->logging;

	$log->info( "Loading model: %s", $model );
	$this->load->model( $model );

	$log->info( "Getting methods for %s", $model );
	$methods= get_class_methods( $model );
	$log->debug( "Found %s methods for %s", count( $methods ), $model );

	$funcs= array();
	$matched_func= false;
	foreach( $methods as $method ) {

	    if( $func ) {
		$regex= sprintf( "/^test_%s$/", $func );
		if( $func == $method or "test_".$func == $method or preg_match( $regex, $method ) ) {
		    $matched_func= true;
		}
		else {
		    continue;
		}
	    }

	    if( $matched_func or
		( substr( $method, 0, 5 ) == "test_"
		  and $method != 'test_debug' ) ) {

		$log->info( "Running method %s in class %s", $method, get_class($this->$model) );
		call_user_func( array( $this->$model, $method ) );
		$log->info( "Finished method %s", $method );

		$funcs[]= (object) array(
					 'name'=> $model .'::'. $method,
					 'tests'=> $this->unit->result()
					 );
		$this->unit->reset();
	    }
	}

	if( $func and ! $matched_func ) {
	    $message= sprintf( "Unable to find function: %s", $func );
	    $this->template->error( 'general', $message );
	    return;
	}

	return $funcs;
    }

    function print_report( $models = array(), $methods = array() )
    {
	if( $this->input->is_cli_request() ) {
	    $this->print_cli( $models, $methods );
	}
	else {
	    $this->print_html( $models, $methods );
	}
    }

    function categorize_results( $methods = array() )
    {
	$passed_count= $failed_count= 0;
	$passed= $failed= array();

	foreach( $methods as $method ) {
	    foreach( $method->tests as $test ) {
		if( $test['Expected Datatype'] == 'bool' ) {
		    $test['Expected Datatype']= "Boolean";
		}

		if( $test['Result'] == 'Passed' ) {
		    $test['note']= '';
		    $passed[$method->name][]= $test;
		    $passed_count++;
		}
		else {
		    $test['note']= $test['Notes']
			? sprintf( '<p>%s</p>', $test['Notes'] )
			: null;
		    $failed[$method->name][]= $test;
		    $failed_count++;
		}
	    }
	}

	$categories[] = (object) array(
				       'name'=> 'Failed',
				       'color'=> 'red',
				       'test_count'=> $failed_count,
				       'method_count'=> count( $failed ),
				       'methods'=> $failed
				       );
	$categories[] = (object) array(
				       'name'=> 'Passed',
				       'color'=> 'green',
				       'test_count'=> $passed_count,
				       'method_count'=> count( $passed ),
				       'methods'=> $passed
				       );

	return $categories;
    }

    function print_html( $models = array(), $methods = array() )
    {
	$categories= $this->categorize_results( $methods );

	$data['title']= "Unit Testing";
	$data['categories']= $categories;
	$data['models']= $models;
	$this->template->admin( 'testing', $data, array() );
    }

    function print_cli( $models = array(), $methods = array() )
    {
	$log= $this->logging;

	$type_color_map = array(
				'Boolean'=> 'cyan',
				'Object'=> 'iblue',
				'String'=> 'red',
				'Array'=> 'igreen',
				'numeric'=> 'iblack',
				'int'=> 'iblack',
				'Float'=> 'ipurple',
				'Integer'=> 'ipurple'
				);

	$this->load->model( 'CLI_Colors', 'color' );

	$color= $this->color;
	$categories= array_reverse( $this->categorize_results( $methods ) );

	foreach( $categories as $category ) {
	    $title= sprintf( "%-143s\n",
			     sprintf( '%s: %s Tests in %s Methods ',
				      $category->name,
				      $category->test_count,
				      $category->method_count )
			     );

	    echo $color->string( repeater( '-', 143)."\n", $category->color );
	    echo $color->string( $title, $category->color );
	    echo $color->string( repeater( '-', 143)."\n", $category->color );

	    foreach( $category->methods as $method => $tests ) {
		echo $color->string( sprintf( "    %s\n", $method ), 'gray' );

		foreach( $tests as $test ) {
		    $status	= $color->string( $test['Result'].':',		$category->color, '' );
		    $name	= $color->string( strip_tags( $test['Test Name'] ), 'igray' );
		    $expected	= $color->string( $test['Expected Datatype'],	$type_color_map[$test['Expected Datatype']] );
		    $result	= $color->string( $test['Test Datatype'],	$type_color_map[$test['Test Datatype']] );
		    printf( "        %s %-120s%-20s%s\n",
			    $status, $name, $expected, $result );
		}
		echo $color->string( repeater( '-', 143)."\n", $category->color );
	    }
	}

	echo $color->string( sprintf( "\n\n%-100s%-20s%-20s\n", 'Report', 'Tests', 'Methods' ), 'white' );
	echo $color->string( repeater( '-', 143)."\n", 'white' );
	foreach( $categories as $category ) {
	    $title= sprintf( "%-100s%-20s%-20s\n",
			     $category->name,
			     $category->test_count,
			     $category->method_count );
	    echo $color->string( $title, $category->color );
	    echo $color->string( "".repeater( '-', 143)."\n", 'white' );
	}
	echo "\n\n";

    }

    function load_view( $view )
    {
	$view= str_replace( '.', '/', $view );
	$this->load->view( $view );
    }


    function load_template( $template, $view )
    {
	$data['title']= "Test Template";
	$data['active']= "";

	$view= str_replace( '.', '/', $view );

	$this->template->{$template}( $view, $data, array( 'browse_categories' ) );
    }
}
